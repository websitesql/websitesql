<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Controllers\Api\Users\Single;

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
            // Get admin roles
            $this->getAdminRoles();

            // Get the current user
            $user = $request->getAttribute('user');

            // Get the arguments
            $args = $request->getAttributes();

            // Get the user from the database
            $userData = $this->app->getDatabase()->get($this->app->getStrings()->getTableUsers(), [
                'uuid',
                'email',
                'role'
            ], [
                'uuid' => $args['id']
            ]);

            // Check if the user exists
            if (!$userData) {
                return new JsonResponse([
                    'message' => 'User not found'
                ], 404);
            }

            // Check the user is not the current user
            if ($userData['uuid'] === $user['uuid']) {
                return new JsonResponse([
                    'message' => 'You cannot delete yourself'
                ], 400);
            }

            // Check the user is an administrator
            if (in_array($userData['role'], $this->roles)) {
                if ($this->numberOfAdmins() === 1) {
                    return new JsonResponse([
                        'message' => 'You cannot delete the last administrator'
                    ], 400);
                }
            }

            // Delete the user
            $this->app->getDatabase()->delete($this->app->getStrings()->getTableUsers(), [
                'uuid' => $args['id']
            ]);

            return new EmptyResponse(204);
        } catch (Exception $e) {
            return new JsonResponse([
                'message' => 'Failed to delete user: ' . $e->getMessage()
            ], 500);
        }
    }

    /*
     * Get Admin roles
     * 
     * @return void
     */
    private function getAdminRoles(): void
    {
        // Get all roles where administrator is 1
        $roleIds = $this->app->getDatabase()->select($this->app->getStrings()->getTableRoles(), [
            'id'
        ], [
            'administrator' => 1
        ]);

        // Transform the roles into an array of role IDs
        $this->roles = array_map(function ($role) {
            return $role['id'];
        }, $roleIds);
    }

    /*
     * Check the user being deleted is not the last administrator
     * 
     * @return int
     */
    private function numberOfAdmins(): int
    {
        // Get all users with the administrator role
        return $this->app->getDatabase()->count($this->app->getStrings()->getTableUsers(), [
            'role' => $this->roles
        ]);
    }
}