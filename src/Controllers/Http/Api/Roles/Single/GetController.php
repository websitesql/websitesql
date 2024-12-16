<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Controllers\Http\Api\Roles\Single;

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

            // Get the role data
            $role = $this->app->getDatabase()->get(
                $this->app->getStrings()->getTableRoles(),
                [
                    'id',
                    'uuid',
                    'name',
                    'description',
                    'public_access',
                    'app_access',
                    'administrator'
                ],
                ['uuid' => $args['id']]
            );

            // Check if the role exists
            if (empty($role)) {
                return new EmptyResponse(404);
            }

            // Return the response
            return new JsonResponse($role);
        } catch (Exception $e) {
            return new EmptyResponse(500);
        }
    }
}