<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Controllers\Auth;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use WebsiteSQL\WebsiteSQL\Exceptions\InvalidTokenException;
use WebsiteSQL\WebsiteSQL\Exceptions\MissingRequiredFieldsException;
use WebsiteSQL\WebsiteSQL\Exceptions\UserAlreadyExistsException;
use WebsiteSQL\WebsiteSQL\Exceptions\PasswordMismatchException;
use WebsiteSQL\WebsiteSQL\App;
use Exception;

class RegisterController implements RequestHandlerInterface
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
        // Attempt to register the user
        if (isset($_POST['doRegister'])) {
            // Register the user
            try {
                // Check if the CRSF token was passed and is valid
                $csrfToken = htmlspecialchars($_POST['csrf_token']);
                if (!$csrfToken || $csrfToken !== $_SESSION['csrf_token']) {
                    throw new InvalidTokenException();
                }

                // Check if the email and password are set
                if (!isset($_POST['firstname']) || !isset($_POST['lastname']) || !isset($_POST['email']) || !isset($_POST['password']) || !isset($_POST['confirm_password'])) {
                    throw new MissingRequiredFieldsException();
                }

                // Sanitise the email and password with input_filter function
                $firstname = htmlspecialchars($_POST['firstname']);
                $lastname = htmlspecialchars($_POST['lastname']);
                $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
                $password = htmlspecialchars($_POST['password']);
                $confirm_password = htmlspecialchars($_POST['confirm_password']);

                // Check if the passwords match
                if ($password !== $confirm_password) {
                    throw new PasswordMismatchException();
                }


                // Authenticate the user
                $this->app->getUser()->register($firstname, $lastname, $email, $password);

                $success = 'Your account has been created, please check your email to verify your account.';
            } catch (InvalidTokenException $e) {
                $error = $this->app->getStrings()->getMessageInvalidToken();
            } catch (MissingRequiredFieldsException $e) {
                $error = $this->app->getStrings()->getMessageMissingRequiredFields();
            } catch (PasswordMismatchException $e) {
                $error = $this->app->getStrings()->getMessagePasswordMismatch();
            } catch (UserAlreadyExistsException $e) {
                $error = $this->app->getStrings()->getMessageUserAlreadyExists();
            } catch (Exception $e) {
                $error = $this->app->getStrings()->getMessageUnknownErrorOccurred();
            }
        }

		// Render the login page
        $body = $this->app->getRenderer()->render('application::auth/register', [
            'title' => 'Register',
            'error' => $error ?? null,
            'success' => $success ?? null
        ]);
        
        // Return the response
        return new HtmlResponse($body);
    }
}