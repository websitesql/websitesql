<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Controllers\Auth;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use WebsiteSQL\WebsiteSQL\Exceptions\InvalidTokenException;
use WebsiteSQL\WebsiteSQL\Exceptions\MissingRequiredFieldsException;
use WebsiteSQL\WebsiteSQL\Exceptions\UserNotFoundException;
use WebsiteSQL\WebsiteSQL\Exceptions\IncorrectPasswordException;
use WebsiteSQL\WebsiteSQL\Exceptions\EmailNotVerifiedException;
use WebsiteSQL\WebsiteSQL\Exceptions\UserNotApprovedException;
use WebsiteSQL\WebsiteSQL\Exceptions\UserLockedOutException;
use WebsiteSQL\WebsiteSQL\App;
use Exception;
use Laminas\Diactoros\Response\RedirectResponse;

class LoginController implements RequestHandlerInterface
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
        // Login the user
        if (isset($_POST['doLogin'])) {
            // Login the user
            try {
                // Check if the CRSF token was passed and is valid
                $csrfToken = htmlspecialchars($_POST['csrf_token']);
                if (!$csrfToken || $csrfToken !== $_SESSION['csrf_token']) {
                    throw new InvalidTokenException();
                }

                // Check if the email and password are set
                if (!isset($_POST['email']) || !isset($_POST['password'])) {
                    throw new MissingRequiredFieldsException();
                }

                // Sanitise the email and password with input_filter function
                $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
                $password = htmlspecialchars($_POST['password']);

                // Authenticate the user
                $cookieValue = $this->app->getAuth()->authenticate($email, $password);

                // Return an redirect response
                $response = new RedirectResponse($this->app->getRouter()->getRoute('app.dashboard'));
                return $response->withAddedHeader('Set-Cookie', $cookieValue);
            } catch (InvalidTokenException $e) {
                $error = $this->app->getStrings()->getMessageInvalidToken();
            } catch (MissingRequiredFieldsException $e) {
                $error = $this->app->getStrings()->getMessageMissingRequiredFields();
            } catch (UserNotFoundException $e) {
                $error = $this->app->getStrings()->getMessageEmailOrPasswordIncorrect();
            } catch (IncorrectPasswordException $e) {
                $error = $this->app->getStrings()->getMessageEmailOrPasswordIncorrect();
            } catch (EmailNotVerifiedException $e) {
                $error = $this->app->getStrings()->getMessageLoginAccountEmailNotVerified();
            } catch (UserNotApprovedException $e) {
                $error = $this->app->getStrings()->getMessageLoginAccountInactive();
            } catch (UserLockedOutException $e) {
                $error = $this->app->getStrings()->getMessageLoginAccountLocked();
            } catch (Exception $e) {
                $error = $this->app->getStrings()->getMessageUnknownErrorOccurred();
            }
        }

        // Render the login page
        $body = $this->app->getRenderer()->render('application::auth/login', [
            'title' => 'Login',
            'notices' => $this->app->getCustomization()->getLoginNotices(),
            'error' => $error ?? null
        ]);	
        
        // Return the response
        return new HtmlResponse($body);
    }
}