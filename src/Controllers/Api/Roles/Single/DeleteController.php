<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Controllers\Api\Roles\Single;

use Error;
use Exception;
use Laminas\Diactoros\Response\EmptyResponse;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Laminas\Diactoros\Response\JsonResponse;
use WebsiteSQL\WebsiteSQL\App;

class DeleteController implements RequestHandlerInterface
{
    /*
     * This object is the main entry point of the application
     * 
     * @var App
     */
    private App $app;

    /*
     * Admin roles
     * 
     * @param array
     */
    private $roles = [];

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

            // Get the arguments
            $args = $request->getAttributes();

            // Get the user from the database
            $roleData = $this->app->getDatabase()->get($this->app->getStrings()->getTableRoles(), [
                'id',
                'name'
            ], [
                'uuid' => $args['id']
            ]);

            // Check if the user exists
            if (!$roleData) {
                return new JsonResponse([
                    'message' => 'Role not found'
                ], 404);
            }

            // Check the user is not the current user
            if ($roleData['id'] === $user['id']) {
                return new JsonResponse([
                    'message' => 'You cannot delete the role currently assigned to your account'
                ], 400);
            }

            // Check if any other users are assigned the role
            $usersAssigned = $this->app->getDatabase()->select($this->app->getStrings()->getTableUsers(), [
                'id'
            ], [
                'role' => $roleData['id']
            ]);

            // If other users are assigned the role, return an error
            if (count($usersAssigned) > 0) {
                return new JsonResponse([
                    'message' => 'Cannot delete role as it is assigned to other users'
                ], 400);
            }

            // Delete the role
            $this->app->getDatabase()->delete($this->app->getStrings()->getTableRoles(), [
                'id' => $roleData['id']
            ]);

            return new EmptyResponse(204);
        } catch (Exception $e) {
            return new JsonResponse([
                'message' => 'Failed to delete user: ' . $e->getMessage()
            ], 500);
        }
    }
}