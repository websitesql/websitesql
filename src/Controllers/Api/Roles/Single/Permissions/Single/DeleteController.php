<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Controllers\Api\Roles\Single\Permissions\Single;

use Error;
use Exception;
use Laminas\Diactoros\Response\EmptyResponse;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Laminas\Diactoros\Response\JsonResponse;
use WebsiteSQL\WebsiteSQL\App;
use WebsiteSQL\WebsiteSQL\Exceptions\PermissionNotFoundException;
use WebsiteSQL\WebsiteSQL\Exceptions\RoleNotFoundException;

class DeleteController implements RequestHandlerInterface
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

            // Get the role data
            $role = $this->app->getDatabase()->get($this->app->getStrings()->getTableRoles(), ['id'], ['uuid' => $args['id']]);

            // Check if the role exists
            if (empty($role)) {
                throw new RoleNotFoundException();
            }

            // Get the permission data
            $permission = $this->app->getDatabase()->get($this->app->getStrings()->getTablePermissions(), ['id'], ['role' => $role['id'], 'id' => $args['permission_id']]);

            // Check if the permission exists
            if (empty($permission)) {
                throw new PermissionNotFoundException();
            }

            // Delete the permission
            $this->app->getDatabase()->delete($this->app->getStrings()->getTablePermissions(), ['id' => $permission['id']]);

            // Return success
            return new JsonResponse([
                'status' => 'success',
                'message' => 'Permission successfully deleted'
            ], 201);
        } catch (RoleNotFoundException $e) {
            return new JsonResponse([
                'status' => 'failed',
                'message' => 'Role not found'
            ], 404);
        } catch (PermissionNotFoundException $e) {
            return new JsonResponse([
                'status' => 'failed',
                'message' => 'Permission not found'
            ], 400);
        } catch (Exception $e) {
            return new JsonResponse([
                'status' => 'failed',
                'message' => 'Failed to insert role: ' . $e->getMessage()
            ], 500);
        }
    }
}