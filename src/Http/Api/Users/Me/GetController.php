<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Http\Api\Users\Me;

use Exception;
use Laminas\Diactoros\Response\EmptyResponse;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Laminas\Diactoros\Response\HtmlResponse;
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
            // Get the current user from the request
            $user = $request->getAttribute('user');

            // Get the users role from the database
            $role = $this->app->getDatabase()->get($this->app->getStrings()->getTableRoles(), ['id', 'uuid', 'name'], ['id' => $user['role']]);

            // Return the user data
            return new JsonResponse([
                'id' => $user['id'],
                'uuid' => $user['uuid'],
                'firstname' => $user['firstname'],
                'lastname' => $user['lastname'],
                'email' => $user['email'],
                'role' => [
                    'id' => $role['id'],
                    'uuid' => $role['uuid'],
                    'name' => $role['name']
                ],
                'approved' => $user['approved'],
                'locked' => $user['locked'],
                'created_at' => $user['created_at'],
            ], 200);
        } catch (Exception $e) {
            return new EmptyResponse(500);
        }
    }
}