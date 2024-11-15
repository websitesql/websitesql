<?php declare(strict_types=1);

namespace WebsiteSQL\Controllers;

use WebsiteSQL\App;
use WebsiteSQL\Exceptions\InvalidTokenException;
use WebsiteSQL\Exceptions\MissingRequiredFieldsException;
use WebsiteSQL\Exceptions\DatabaseConnectionFailedException;
use WebsiteSQL\Exceptions\PasswordMismatchException;
use WebsiteSQL\Providers\MigrationsProvider;
use Exception;

class SetupController
{
    /*
     * This object holds the Medoo database connection
     * 
     * @var Medoo
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
     * This method initializes the setup
     * 
     * @return void
     */
    public function init(): void
    {
        // Register redirect on / to setup
        $this->app->getRouter()->registerAppRoute('GET', '/', function () {
            header('location: /admin/setup');
        }, 'setup_redirect');

        // Register setup routes
        $this->app->getRouter()->registerAdminRoute(['GET', 'POST'], '/setup', [$this, 'setup'], 'setup');
    }

    /*
     * This method renders the setup page
     * 
     * @return string
     */
    public function setup(): string
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

                // Create the .env file
                $env = fopen($this->app->getBasePath() . '/.env', 'w');
                fwrite($env, '######################################' . PHP_EOL);
                fwrite($env, '## General' . PHP_EOL);
                fwrite($env, PHP_EOL);
                fwrite($env, 'APP_ENV=production' . PHP_EOL);
                fwrite($env, 'APP_DEBUG=false' . PHP_EOL);
                fwrite($env, 'APP_URL=' . $_SERVER['HTTP_HOST'] . PHP_EOL);
                fwrite($env, PHP_EOL);
                fwrite($env, '######################################' . PHP_EOL);
                fwrite($env, '## Database' . PHP_EOL);
                fwrite($env, PHP_EOL);
                fwrite($env, 'DB_DRIVER=mysql' . PHP_EOL);
                fwrite($env, 'DB_HOST=' . htmlspecialchars($_POST['host']) . PHP_EOL);
                fwrite($env, 'DB_NAME=' . htmlspecialchars($_POST['database']) . PHP_EOL);
                fwrite($env, 'DB_USER=' . htmlspecialchars($_POST['username']) . PHP_EOL);
                fwrite($env, 'DB_PASS=' . htmlspecialchars($_POST['password']) . PHP_EOL);
                fclose($env);

                // Create a new MigrationsProvider class
                $migrationsProvider = new MigrationsProvider($database);

                // Initialise the MigrationsProvider
                $migrationsProvider->init();

                // Run the migrations
                $migrationsProvider->run();

                // Data to insert
                $data = [
                    ['ID' => 'ApplicationProgrammingInterfaceMode', 'Value' => 'false'],
                    ['ID' => 'DatabaseVersion', 'Value' => '1.0.0'],
                    ['ID' => 'DateFormat', 'Value' => 'jS F Y'],
                    ['ID' => 'DefaultTimezone', 'Value' => 'Europe/London'],
                    ['ID' => 'GroupsModuleSQLArguments', 'Value' => ''],
                    ['ID' => 'GroupsModuleSQLTable', 'Value' => ''],
                    ['ID' => 'MailserverHost', 'Value' => 'mail.example.com'],
                    ['ID' => 'MailserverPassword', 'Value' => 'Password1'],
                    ['ID' => 'MailserverPort', 'Value' => '465'],
                    ['ID' => 'MailserverUsername', 'Value' => 'no-reply@example.com'],
                    ['ID' => 'MaintenanceMode', 'Value' => 'false'],
                    ['ID' => 'WebsiteAnalyticsCode', 'Value' => ''],
                    ['ID' => 'WebsiteLogo', 'Value' => ''],
                    ['ID' => 'WebsiteName', 'Value' => 'Example'],
                    ['ID' => 'WebsiteRoot', 'Value' => 'https://www.example.com/'],
                    ['ID' => 'WebsiteTheme', 'Value' => 'example-theme'],
                    ['ID' => 'WebsiteSalt', 'Value' => ''],
                    ['ID' => 'TinyMCEBodyClass', 'Value' => ''],
                    ['ID' => 'TinyMCECSSPath', 'Value' => ''],
                    ['ID' => 'WebsiteUserApproval', 'Value' => 0],
                    ['ID' => 'ApplicationLicenceKey', 'Value' => ''],
                    ['ID' => 'MailserverUseSSL', 'Value' => ''],
                    ['ID' => 'loggingWebsiteLogs', 'Value' => '1'],
                    ['ID' => 'loggingTransactionLogs', 'Value' => '1'],
                    ['ID' => 'loggingAdminLogs', 'Value' => '0'],
                    ['ID' => 'UserAuthenticatedRedirectPath', 'Value' => ''],
                    ['ID' => 'UserUnauthenticatedRedirectPath', 'Value' => ''],
                    ['ID' => 'UserAutoApproval', 'Value' => '0'],
                    ['ID' => 'DisableContentManagementSystem', 'Value' => '0']
                ];

                // Insert into the WebSQL_Settings table
                $database->insert('WebSQL_Settings', $data);

                // Check if the email and password are set
                if (!isset($_POST['admin_email']) || !isset($_POST['admin_password'])) {
                    throw new MissingRequiredFieldsException();
                }

                // Sanitise the email and password with input_filter function
                $email = filter_input(INPUT_POST, 'admin_email', FILTER_SANITIZE_EMAIL);
                $password = htmlspecialchars($_POST['admin_password']);
                $repeat_password = htmlspecialchars($_POST['admin_repeat_password']);

                // Check if the passwords match
                if ($password !== $repeat_password) {
                    throw new PasswordMismatchException();
                }

                // Hash the password
                $password = password_hash($password, PASSWORD_ARGON2ID);

                // Get the table name
                $database->insert($this->app->getStrings()->getTableUsers(), [
                    'firstname' => 'Admin',
                    'lastname' => 'User',
                    'email' => $email,
                    'realm' => 'websitesql',
                    'password' => $password,
                    'approved' => 1,
                    'locked' => 0,
                    'email_verified' => 1,
                ]);

                // Redirect to the login page
                header('location: /' . $this->app->getStrings()->getAdminFilePath() . '/login');

                // Exit the script
                exit;
            } catch (InvalidTokenException $e) {
                // Delete the .env file if it exists
                if (file_exists($this->app->getBasePath() . '/.env')) {
                    unlink($this->app->getBasePath() . '/.env');
                }
                $error = $this->app->getStrings()->getMessageInvalidToken();
            } catch (MissingRequiredFieldsException $e) {
                // Delete the .env file if it exists
                if (file_exists($this->app->getBasePath() . '/.env')) {
                    unlink($this->app->getBasePath() . '/.env');
                }
                $error = $this->app->getStrings()->getMessageMissingRequiredFields();
            } catch (PasswordMismatchException $e) {
                // Delete the .env file if it exists
                if (file_exists($this->app->getBasePath() . '/.env')) {
                    unlink($this->app->getBasePath() . '/.env');
                }
                $error = $this->app->getStrings()->getMessagePasswordMismatch();
            } catch (DatabaseConnectionFailedException $e) {
                // Delete the .env file if it exists
                if (file_exists($this->app->getBasePath() . '/.env')) {
                    unlink($this->app->getBasePath() . '/.env');
                }
                $error = 'TODO STRINGS: Sorry, we were unable to connect to the database. Please check your connection details and try again.';
            } catch (Exception $e) {
                // Delete the .env file if it exists
                if (file_exists($this->app->getBasePath() . '/.env')) {
                    unlink($this->app->getBasePath() . '/.env');
                }
                $error = $this->app->getStrings()->getMessageUnknownErrorOccurred();
                $error_details = $e->getMessage();
            }
        }

        // Render the setup page
        return $this->app->getRenderer()->render('setup::index', [
            'title' => 'Setup',
            'error' => $error ?? null,
            'error_details' => $error_details ?? null
        ]);
    }
}