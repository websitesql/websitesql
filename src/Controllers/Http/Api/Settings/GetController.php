<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Controllers\Http\Api\Settings;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Laminas\Diactoros\Response\JsonResponse;
use WebsiteSQL\WebsiteSQL\App;

class GetController implements RequestHandlerInterface
{
    /**
     * The main entry point of the application
     *
     * @var App
     */
    private App $app;

    /**
     * Constructor
     *
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * Handle the request
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $response = ['success' => true, 'data' => []];

        $response['data'] = $this->app->getDatabase()->select(
            $this->app->getStrings()->getTableCustomizations(),
            [
                'id',
                'user',
                'name',
                'value',
                'created_at',
                'created_by',
            ],
            [
                'AND' => [
                    'name' => ['interface.application_lm_logo', 'interface.application_dm_logo', 'interface.application_name'],
                    'user' => null,
                ],
            ]
        );

        return new JsonResponse($response, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
}
