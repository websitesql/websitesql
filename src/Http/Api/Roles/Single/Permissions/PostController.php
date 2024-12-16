<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Http\Api\Roles\Single\Permissions;

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
use WebsiteSQL\WebsiteSQL\Exceptions\PermissionAlreadyRegisteredException;
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
            // Get the arguments
            $args = $request->getAttributes();

            // Get the current user
            $user = $request->getAttribute('user');

            // Get the role data
            $role = $this->app->getDatabase()->get($this->app->getStrings()->getTableRoles(), ['id'], ['uuid' => $args['id']]);

            // Check if the role exists
            if (empty($role)) {
                throw new RoleNotFoundException();
            }

            // Get the request body
            $body = json_decode($request->getBody()->getContents(), true);

            // Check if the email and password are set
            if (!isset($body['name']) || !isset($body['enabled'])) {
                throw new MissingRequiredFieldsException();
            }

            // Sanitise the email and password with input_filter function
            $name = htmlspecialchars($body['name']);
            $enabled = filter_var($body['enabled'], FILTER_SANITIZE_NUMBER_INT);

            // Check if a role already exists with the same name
            $permission = $this->app->getDatabase()->select($this->app->getStrings()->getTablePermissions(), 'id', ['name' => $name, 'role' => $role['id']]);

            if (count($permission) > 0) {
                throw new PermissionAlreadyRegisteredException();
            }

            // Insert the user into the database
            $this->app->getDatabase()->insert($this->app->getStrings()->getTablePermissions(), [
                'role' => $role['id'],
                'name' => $name,
                'enabled' => $enabled,
                'filter' => ($body['filter'] ? json_encode($body['filter']) : null),
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
        } catch (RoleNotFoundException $e) {
            return new JsonResponse([
                'status' => 'failed',
                'message' => 'Role not found'
            ], 404);
        } catch (MissingRequiredFieldsException $e) {
            return new JsonResponse([
                'status' => 'failed',
                'message' => 'Missing required fields'
            ], 400);
        } catch (PermissionAlreadyRegisteredException $e) {
            return new JsonResponse([
                'status' => 'failed',
                'message' => 'A permission with the same name already exists'
            ], 400);
        } catch (Exception $e) {
            return new JsonResponse([
                'status' => 'failed',
                'message' => 'Failed to insert role: ' . $e->getMessage()
            ], 500);
        }
    }
}