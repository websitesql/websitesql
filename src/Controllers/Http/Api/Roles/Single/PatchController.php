<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Controllers\Http\Api\Roles\Single;

use Exception;
use Laminas\Diactoros\Response\EmptyResponse;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Laminas\Diactoros\Response\JsonResponse;
use WebsiteSQL\WebsiteSQL\App;

class PatchController implements RequestHandlerInterface
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
        // Get the arguments
        $args = $request->getAttributes();

        // Get the request body
        $body = json_decode($request->getBody()->getContents(), true);

        // Check if the body is empty
        if (empty($body)) {
            return new JsonResponse([
                'status' => 'failed',
                'message' => 'The request body is empty'
            ], 400);
        }

        // Check if the body contains the required fields
        if (!isset($body['name']) || !isset($body['description']) || !isset($body['app_access']) || !isset($body['administrator']) || !isset($body['public_access'])) {
            return new JsonResponse([
                'status' => 'failed',
                'message' => 'The request body is missing required fields'
            ], 400);
        }

        // Update the user
        try {
            $this->app->getDatabase()->update($this->app->getStrings()->getTableRoles(), [
                'name' => $body['name'],
                'description' => $body['description'],
                'app_access' => $body['app_access'],
                'administrator' => $body['administrator'],
                'public_access' => $body['public_access']
            ], [
                'uuid' => $args['id']
            ]);

            return new EmptyResponse(204);
        } catch (Exception $e) {
            return new JsonResponse([
                'stauts' => 'failed',
                'message' => 'Failed to update role: ' . $e->getMessage()
            ], 500);
        }
    }
}