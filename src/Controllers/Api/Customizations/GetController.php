<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Controllers\Api\Customizations;

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
        // Get pagination and search parameters from the request
        $queryParams = $request->getQueryParams();
        $page = isset($queryParams['page']) ? (int) $queryParams['page'] : null;
        $limit = isset($queryParams['limit']) ? (int) $queryParams['limit'] : null;
        $search = $queryParams['search'] ?? null;

        // Prepare base conditions for the query
        $conditions = [
            'ORDER' => ['id' => 'ASC']
        ];

        // Add search conditions if a search term is provided
        if ($search) {
            $conditions[] = [
                'name[~]' => $search
            ];
        }

        // Handle limit and pagination logic
        if ($limit !== null) {
            if ($page !== null) {
                // Apply full pagination (limit + page)
                $conditions['LIMIT'] = [($page - 1) * $limit, $limit];
            } else {
                // Apply limit independently of page
                $conditions['LIMIT'] = $limit;
            }
        }

        // Get the users from the database
        $roles = $this->app->getDatabase()->select(
            $this->app->getStrings()->getTableCustomizations(),
            [
                'id',
                'user',
                'name',
                'value',
                'created_at',
                'created_by',
            ],
            $conditions
        );

        // Count total results only if search is provided (optional for performance)
        $total = $search ? $this->app->getDatabase()->count($this->app->getStrings()->getTableCustomizations(), $conditions['AND'] ?? []) : count($roles);

        // Build the response
        $response = [];

        // Add limit details if provided
        if ($limit !== null) {
            $response['limit'] = $limit;
        }

        // Add pagination details only if both page and limit are provided
        if ($page !== null && $limit !== null) {
            $response['page'] = $page;
            $response['total'] = $total;
        }

        // Add search term only if it is provided
        if ($search) {
            $response['search'] = $search;
        }

        // Add the users to the response
        $response['data'] = $roles;

        // Return the JSON response
        return new JsonResponse($response, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
}