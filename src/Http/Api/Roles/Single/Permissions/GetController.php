<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Http\Api\Roles\Single\Permissions;

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
            // Get the query parameters
            $queryParams = $request->getQueryParams();
            $offset = isset($queryParams['offset']) ? (int) $queryParams['offset'] : null;
            $limit = isset($queryParams['limit']) ? (int) $queryParams['limit'] : null;
            $search = $queryParams['search'] ?? null;

            // Get the arguments
            $args = $request->getAttributes();

            // Get the role data
            $role = $this->app->getDatabase()->get($this->app->getStrings()->getTableRoles(), ['id'], ['uuid' => $args['id']]);

            // Check if the role exists
            if (empty($role)) {
                return new EmptyResponse(404);
            }

            // Get the permissions
            $permissions = $this->app->getDatabase()->select($this->app->getStrings()->getTablePermissions(),
                [
                    'id',
                    'name',
                    'enabled',
                    'filter',
                    'created_at',
                    'created_by'
                ],
                [
                    'role' => $role['id']
                ]
            );

            // Search and paginate the users
            $response = $this->app->getUtilities()->searchAndPaginate($permissions, $offset, $limit, $search, ['name']);

            // Return the users
            return new JsonResponse($response, 200);
        } catch (Exception $e) {
            return new EmptyResponse(500);
        }
    }
}