<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Controllers\Http\Api\Users\Single;

use Exception;
use Laminas\Diactoros\Response\EmptyResponse;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Laminas\Diactoros\Response\JsonResponse;
use WebsiteSQL\WebsiteSQL\App;

class GetController implements RequestHandlerInterface
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

            // Get the user and role data (JOIN)
            $user = $this->app->getDatabase()->get(
                $this->app->getStrings()->getTableUsers(),
                [
                    "[>]" . $this->app->getStrings()->getTableRoles() => ["role" => "id"]
                ],
                [
                    $this->app->getStrings()->getTableUsers() . '.id',
                    $this->app->getStrings()->getTableUsers() . '.uuid',
                    $this->app->getStrings()->getTableUsers() . '.firstname',
                    $this->app->getStrings()->getTableUsers() . '.lastname',
                    $this->app->getStrings()->getTableUsers() . '.email',
                    $this->app->getStrings()->getTableRoles() . '.id(role_id)',
                    $this->app->getStrings()->getTableRoles() . '.uuid(role_uuid)',
                    $this->app->getStrings()->getTableRoles() . '.name(role_name)',
                    $this->app->getStrings()->getTableUsers() . '.approved',
                    $this->app->getStrings()->getTableUsers() . '.locked',
                    $this->app->getStrings()->getTableUsers() . '.email_verified',
                    $this->app->getStrings()->getTableUsers() . '.created_at',
                    $this->app->getStrings()->getTableUsers() . '.created_by',
                ], 
                [
                    $this->app->getStrings()->getTableUsers() . '.uuid' => $args['id']
                ]
            );
            
            // Check if the user exists
            if (!$user) {
                return new JsonResponse([
                    'status' => 'failed',
                    'message' => 'User not found'
                ], 404);
            }

            // Return the user data
            return new JsonResponse([
                'id' => $user['id'],
                'uuid' => $user['uuid'],
                'firstname' => $user['firstname'],
                'lastname' => $user['lastname'],
                'email' => $user['email'],
                'role' => ($user['role_id'] !== null) ? [
                    'id' => $user['role_id'],
                    'uuid' => $user['role_uuid'],
                    'name' => $user['role_name']
                ] : null,
                'approved' => $user['approved'],
                'locked' => $user['locked'],
                'created_at' => $user['created_at'],
            ], 200);
        } catch (Exception $e) {
            return new EmptyResponse(500);
        }
    }
}