<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Controllers\Api\Users\Me;

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
        try {
            // Get the current user from the request
            $user = $request->getAttribute('user');

            // Get the request body
            $body = json_decode($request->getBody()->getContents(), true);
            
            // Sanitise the email and with var_filter function
            $firstname = isset($body['firstname']) ? htmlspecialchars($body['firstname']) : null;
            $lastname = isset($body['lastname']) ? htmlspecialchars($body['lastname']) : null;
            $email = isset($body['email']) ? filter_var($body['email'], FILTER_SANITIZE_EMAIL) : null;

            // Check if the email is already in use
            if ($email !== null) {
                $existingUser = $this->app->getDatabase()->get($this->app->getStrings()->getTableUsers(), ['id'], ['email' => $email]);
                if ($existingUser !== false && $existingUser['id'] !== $user['id']) {
                    return new JsonResponse([
                        'error' => 'Email already in use'
                    ], 400);
                }
            }

            // Create update array
            $update = [];
            if ($firstname !== null) {$update['firstname'] = $firstname;}
            if ($lastname !== null) {$update['lastname'] = $lastname;}
            if ($email !== null) {$update['email'] = $email;}

            $this->app->getDatabase()->update($this->app->getStrings()->getTableUsers(), $update, [
                'id' => $user['id']
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