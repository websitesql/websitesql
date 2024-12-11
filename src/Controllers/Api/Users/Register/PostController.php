<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Controllers\Api\Users\Register;

use Exception;
use Laminas\Diactoros\Response\EmptyResponse;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use WebsiteSQL\WebsiteSQL\App;
use WebsiteSQL\WebsiteSQL\Exceptions\MissingRequiredFieldsException;
use WebsiteSQL\WebsiteSQL\Exceptions\PasswordMismatchException;
use WebsiteSQL\WebsiteSQL\Exceptions\RoleNotFoundException;
use WebsiteSQL\WebsiteSQL\Exceptions\UserAlreadyExistsException;

class PostController implements RequestHandlerInterface
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
        try {
            // Get the request body
            $body = json_decode($request->getBody()->getContents(), true);

            // Check if the email and password are set
            if (!isset($body['firstname']) || !isset($body['lastname']) || !isset($body['email']) || !isset($body['password'])) {
                throw new MissingRequiredFieldsException();
            }

            // Sanitise the email and password with input_filter function
            $firstname = htmlspecialchars($body['firstname']);
            $lastname = htmlspecialchars($body['lastname']);
            $email = filter_var($body['email'], FILTER_SANITIZE_EMAIL);
            $password = htmlspecialchars($body['password']);

            // Check if the email is already in use
            $userData = $this->app->getDatabase()->get($this->app->getStrings()->getTableUsers(), '*', ['email' => $email]);
            if ($userData)
            {
                throw new UserAlreadyExistsException();
            }

            // Hash the password
            $password = password_hash($password, PASSWORD_ARGON2ID);

            // Generate a random UUID for the user
            $uuid = $this->app->getUtilities()->generateUuid(4);

            // Insert the user into the database
            $this->app->getDatabase()->insert($this->app->getStrings()->getTableUsers(), [
                'uuid' => $uuid,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $email,
                'role' => 0,
                'password' => $password,
                'approved' => 0,
                'locked' => 0,
                'email_verified' => 0,
                'created_at' => $this->app->getUtilities()->getDateTime(),
            ]);

            // Get the ID of the new user
            $id = $this->app->getDatabase()->id();

            // Return success
            return new JsonResponse([
                'status' => 'success',
                'message' => 'User created successfully',
                'id' => (int) $id
            ], 201);
        } catch (MissingRequiredFieldsException $e) {
            return new JsonResponse([
                'status' => 'failed',
                'message' => 'Missing required fields'
            ], 400);
        } catch (UserAlreadyExistsException $e) {
            return new JsonResponse([
                'status' => 'failed',
                'message' => 'A user with the email address already exists'
            ], 400);
        } catch (RoleNotFoundException $e) {
            return new JsonResponse([
                'status' => 'failed',
                'message' => 'The role you specified does not exist'
            ], 400);
        } catch (Exception $e) {
            return new JsonResponse([
                'status' => 'failed',
                'message' => 'Failed to insert user',
                'error' => $e->getMessage()
            ], 500);
        }

        // Return the new user
        return new JsonResponse([
            'id' => $id,
            'firstname' => $body['firstname'],
            'lastname' => $body['lastname'],
            'email' => $body['email']
        ], 201);
    }
}