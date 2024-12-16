<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Http\Api\Roles;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Laminas\Diactoros\Response\JsonResponse;
use WebsiteSQL\WebsiteSQL\App;

class GetController implements RequestHandlerInterface
{
    private App $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $offset = isset($queryParams['offset']) ? (int) $queryParams['offset'] : null;
        $limit = isset($queryParams['limit']) ? (int) $queryParams['limit'] : null;
        $search = $queryParams['search'] ?? null;

        $roles = $this->app->getDatabase()->select(
            $this->app->getStrings()->getTableRoles(),
            [
                'id',
                'uuid',
                'name',
                'description',
                'public_access',
                'app_access',
                'administrator',
                'created_at',
                'created_by',
            ]
        );

        // Search and paginate the users
        $response = $this->app->getUtilities()->searchAndPaginate($roles, $offset, $limit, $search, ['name', 'description']);

        // Return the users
        return new JsonResponse($response, 200);
    }
}