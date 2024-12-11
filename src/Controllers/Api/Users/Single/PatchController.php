<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Controllers\Api\Users\Single;

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
        
        // Update the user
        try {
            $this->app->getDatabase()->update($this->app->getStrings()->getTableUsers(), $body, [
                'uuid' => $args['id']
            ]);

            return new EmptyResponse(204);
        } catch (Exception $e) {
            return new JsonResponse([
                'error' => 'Failed to update user',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}