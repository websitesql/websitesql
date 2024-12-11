<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Controllers\Api\Customizations;

use Exception;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\JsonResponse;
use League\Route\Http\Exception\BadRequestException;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use WebsiteSQL\WebsiteSQL\App;

class PostController implements RequestHandlerInterface
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
        // Get the request body
        $body = json_decode($request->getBody()->getContents(), true);

        

        // Check if the request body is empty
        if (empty($body)) {
            return new JsonResponse([
                'status_code'   => 400,
                'reason_phrase' => 'The request body is empty.',
            ], 400);
        }

        // Check if the required fields are present in the request body
        if (!isset($body['name']) || !isset($body['value'])) {
            return new JsonResponse([
                'status_code'   => 400,
                'reason_phrase' => 'The request body is missing required fields.',
            ], 400);
        }

        // Check if the name already exists
        $existingRecord = $this->app->getDatabase()->get($this->app->getStrings()->getTableCustomizations(), '*', [
            'name' => $body['name'],
            'user' => $body['user'] ?? null
        ]);

        if ($existingRecord) {
            return new JsonResponse([
                'status_code'   => 400,
                'reason_phrase' => 'The name already exists.',
            ], 400);
        }

        // Create the new record
        $this->app->getDatabase()->insert($this->app->getStrings()->getTableCustomizations(), [
            'name' => $body['name'],
            'value' => $body['value']
        ]);

        // Return a success response
        return new EmptyResponse(201);
    }
}