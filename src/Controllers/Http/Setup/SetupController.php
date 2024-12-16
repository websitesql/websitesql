<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Controllers\Http\Setup;

use WebsiteSQL\WebsiteSQL\App;
use WebsiteSQL\WebsiteSQL\Exceptions\InvalidTokenException;
use WebsiteSQL\WebsiteSQL\Exceptions\MissingRequiredFieldsException;
use WebsiteSQL\WebsiteSQL\Exceptions\DatabaseConnectionFailedException;
use WebsiteSQL\WebsiteSQL\Exceptions\PasswordMismatchException;
use WebsiteSQL\WebsiteSQL\Providers\MigrationsProvider;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Exception;

class SetupController implements RequestHandlerInterface
{
    /*
     * This object is the main entry point of the application
     * 
     * @var App
     */
    private App $app;

    /*
     * Constructor
     * 
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /*
     * Handle the request
     * 
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // Setup the application
        if (isset($_POST['doSetup'])) {
            // Setup the application
            try {
                // Check if the CRSF token was passed and is valid
                $csrfToken = htmlspecialchars($_POST['csrf_token']);
                if (!$csrfToken || $csrfToken !== $_SESSION['csrf_token']) {
                    throw new InvalidTokenException();
                }

                // Check if the host, username, password, and database are set
                if (!isset($_POST['host']) || !isset($_POST['username']) || !isset($_POST['password']) || !isset($_POST['database'])) {
                    throw new MissingRequiredFieldsException();
                }

                // Check if the email and password are set
                if (!isset($_POST['admin_email']) || !isset($_POST['admin_password'])) {
                    throw new MissingRequiredFieldsException();
                }

                // Create the .env file
                $env = fopen($this->app->getBasePath() . '/.env', 'w');
                fwrite($env, '######################################' . PHP_EOL);
                fwrite($env, '## General' . PHP_EOL);
                fwrite($env, PHP_EOL);
                fwrite($env, 'HOST="' . preg_replace('/:\d+$/', '', $_SERVER['HTTP_HOST']) . '"' . PHP_EOL); // Automatically use the server host
                fwrite($env, 'PORT=' . ($_SERVER['SERVER_PORT'] ?? 443) . PHP_EOL); // Write port, default to 443 if empty
                fwrite($env, 'DEBUG=false' . PHP_EOL); // Default to production
                fwrite($env, 'TIMEZONE="Europe/London"' . PHP_EOL); // Default timezone
                fwrite($env, 'SERVE_APP=true' . PHP_EOL); // Default to using PHP's built-in server
                fwrite($env, 'SERVE_LOCATION="/"' . PHP_EOL); // Default to root location
                fwrite($env, PHP_EOL);
                fwrite($env, '######################################' . PHP_EOL);
                fwrite($env, '## Database' . PHP_EOL);
                fwrite($env, PHP_EOL);
                fwrite($env, 'DB_DRIVER="mysql"' . PHP_EOL);
                fwrite($env, 'DB_HOST="' . htmlspecialchars($_POST['host']) . '"' . PHP_EOL);
                fwrite($env, 'DB_NAME="' . htmlspecialchars($_POST['database']) . '"' . PHP_EOL);
                fwrite($env, 'DB_USER="' . htmlspecialchars($_POST['username']) . '"' . PHP_EOL);
                fwrite($env, 'DB_PASS="' . htmlspecialchars($_POST['password']) . '"' . PHP_EOL);
                fwrite($env, PHP_EOL);
                fwrite($env, '######################################' . PHP_EOL);
                fwrite($env, '## Mail' . PHP_EOL);
                fwrite($env, PHP_EOL);
                fwrite($env, 'MAIL_DRIVER="mail"' . PHP_EOL); // Default mail driver
                fwrite($env, 'MAIL_HOST=""' . PHP_EOL); // Leave blank for user configuration
                fwrite($env, 'MAIL_PORT=""' . PHP_EOL);
                fwrite($env, 'MAIL_USERNAME=""' . PHP_EOL);
                fwrite($env, 'MAIL_PASSWORD=""' . PHP_EOL);
                fwrite($env, 'MAIL_ENCRYPTION=""' . PHP_EOL);
                fclose($env);

                // Sanitise the email and password with input_filter function
                $email = filter_input(INPUT_POST, 'admin_email', FILTER_SANITIZE_EMAIL);
                $password = htmlspecialchars($_POST['admin_password']);
                $repeat_password = htmlspecialchars($_POST['admin_repeat_password']);

                // Check if the passwords match
                if ($password !== $repeat_password) {
                    throw new PasswordMismatchException();
                }

                // Check the database connection with Medoo
                try {
                    $database = new \Medoo\Medoo([
                        'type' => 'mysql',
                        'host' => htmlspecialchars($_POST['host']),
                        'database' => htmlspecialchars($_POST['database']),
                        'username' => htmlspecialchars($_POST['username']),
                        'password' => htmlspecialchars($_POST['password']),
                    ]);
                } catch (\PDOException $e) {
                    throw new DatabaseConnectionFailedException();
                }

                // Create a new MigrationsProvider class
                $migrationsProvider = new MigrationsProvider($database);

                // Initialise the MigrationsProvider
                $migrationsProvider->init();

                // Run the migrations
                $migrationsProvider->run();

                // Hash the password
                $password = password_hash($password, PASSWORD_ARGON2ID);

                // Create 'administrator' role
                $database->insert('wsql_roles', [
                    'uuid' => $this->app->getUtilities()->generateUuid(4),
                    'name' => 'Administrator',
                    'description' => 'The default administrator role has full access to the application.',
                    'app_access' => 1,
                    'administrator' => 1,
                ]);

                // Create 'public' role
                $database->insert('wsql_roles', [
                    'uuid' => $this->app->getUtilities()->generateUuid(4),
                    'name' => 'Public',
                    'description' => 'The default public role defines what the public can access. This is useful for defining what the public can access without logging in such as APIs.',
                    'public_access' => 1,
                ]);

                // Get the admin role from the database
                $adminRole = $database->get('wsql_roles', '*', ['name' => 'Administrator', 'administrator' => 1]);

                // Get the table name
                $database->insert($this->app->getStrings()->getTableUsers(), [
                    'uuid' => $this->app->getUtilities()->generateUuid(4),
                    'firstname' => 'Admin',
                    'lastname' => 'User',
                    'email' => $email,
                    'role' => $adminRole['id'],
                    'password' => $password,
                    'approved' => 1,
                    'locked' => 0,
                    'email_verified' => 1,
                ]);

                // Redirect to root
                header('Location: /');

                // Exit the script
                exit;
            } catch (InvalidTokenException $e) {
                $error = $this->app->getStrings()->getMessageInvalidToken();
            } catch (MissingRequiredFieldsException $e) {
                $error = $this->app->getStrings()->getMessageMissingRequiredFields();
            } catch (PasswordMismatchException $e) {
                $error = $this->app->getStrings()->getMessagePasswordMismatch();
            } catch (DatabaseConnectionFailedException $e) {
                $error = 'TODO STRINGS: Sorry, we were unable to connect to the database. Please check your connection details and try again.';
            } catch (Exception $e) {
                $error = $this->app->getStrings()->getMessageUnknownErrorOccurred();
                $error_details = $e->getMessage();
            } finally {
                // Delete the .env file if it exists
                if (file_exists($this->app->getBasePath() . '/.env')) {
                    unlink($this->app->getBasePath() . '/.env');
                }
            }
        }

        // Render the setup page
        $body = $this->app->getRenderer()->render('setup::index', [
            'title' => 'Setup',
            'error' => $error ?? null,
            'error_details' => $error_details ?? null
        ]);

        return new HtmlResponse($body);
    }
}