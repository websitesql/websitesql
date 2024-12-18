<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Http\Api\Roles;

use Exception;
use Laminas\Diactoros\Response\EmptyResponse;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use WebsiteSQL\WebsiteSQL\App;
use WebsiteSQL\WebsiteSQL\Exceptions\DuplicateRoleNameException;
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
            // Get the current user
            $user = $request->getAttribute('user');

            // Get the request body
            $body = json_decode($request->getBody()->getContents(), true);

            // Check if the email and password are set
            if (!isset($body['name']) || !isset($body['description']) || !isset($body['app_access']) || !isset($body['administrator']) || !isset($body['public_access'])) {
                throw new MissingRequiredFieldsException();
            }

            // Sanitise the email and password with input_filter function
            $name = htmlspecialchars($body['name']);
            $description = htmlspecialchars($body['description']);
            $app_access = filter_var($body['app_access'], FILTER_SANITIZE_NUMBER_INT);
            $administrator = filter_var($body['administrator'], FILTER_SANITIZE_NUMBER_INT);
            $public_access = filter_var($body['public_access'], FILTER_SANITIZE_NUMBER_INT);

            // Check if a role already exists with the same name
            $role = $this->app->getDatabase()->select($this->app->getStrings()->getTableRoles(), 'id', ['name' => $name]);

            if (count($role) > 0) {
                throw new DuplicateRoleNameException();
            }

            // Generate a random UUID for the user
            $uuid = $this->app->getUtilities()->generateUuid(4);

            // Insert the user into the database
            $this->app->getDatabase()->insert($this->app->getStrings()->getTableRoles(), [
                'uuid' => $uuid,
                'name' => $name,
                'description' => $description,
                'app_access' => $app_access,
                'administrator' => $administrator,
                'public_access' => $public_access,
                'created_at' => $this->app->getUtilities()->getDateTime(),
                'created_by' => $user['id']
            ]);

            // Get the ID of the new user
            $id = $this->app->getDatabase()->id();

            // Return success
            return new JsonResponse([
                'status' => 'success',
                'message' => 'Role created successfully',
                'id' => (int) $id
            ], 201);
        } catch (MissingRequiredFieldsException $e) {
            return new JsonResponse([
                'status' => 'failed',
                'message' => 'Missing required fields'
            ], 400);
        } catch (DuplicateRoleNameException $e) {
            return new JsonResponse([
                'status' => 'failed',
                'message' => 'A role with the same name already exists'
            ], 400);
        } catch (Exception $e) {
            return new JsonResponse([
                'status' => 'failed',
                'message' => 'Failed to insert role: ' . $e->getMessage()
            ], 500);
        }
    }
}