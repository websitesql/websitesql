<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Http\Api\Users;

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
        $offset = isset($queryParams['offset']) ? (int) $queryParams['offset'] : null;
        $limit = isset($queryParams['limit']) ? (int) $queryParams['limit'] : null;
        $search = $queryParams['search'] ?? null;

        // Get the users from the database
        $users = $this->app->getDatabase()->select(
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
            ]
        );

        // Transform the flat result into a nested structure
        $users = array_map(function($user) {
            return [
                'id' => $user['id'],
                'uuid' => $user['uuid'],
                'firstname' => $user['firstname'],
                'lastname' => $user['lastname'],
                'email' => $user['email'],
                'role' => $user['role_id'] ? [
                    'id' => $user['role_id'],
                    'uuid' => $user['role_uuid'],
                    'name' => $user['role_name']
                ] : null,
                'approved' => $user['approved'],
                'locked' => $user['locked'],
                'email_verified' => $user['email_verified'],
                'created_at' => $user['created_at'],
                'created_by' => $user['created_by']
            ];
        }, $users);

        // Search and paginate the users
        $response = $this->app->getUtilities()->searchAndPaginate($users, $offset, $limit, $search, ['firstname', 'lastname', 'email']);

        // Return the users
        return new JsonResponse($response, 200);
    }
}