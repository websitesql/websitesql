<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Http\Api\Users\Me\ResetPassword;

use Exception;
use Laminas\Diactoros\Response\EmptyResponse;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Laminas\Diactoros\Response\JsonResponse;
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
        // Get the current user from the request
        $user = $request->getAttribute('user');

        // Get the user from the database
        $userData = $this->app->getDatabase()->get($this->app->getStrings()->getTableUsers(), ['id', 'password'], ['id' => $user['id']]);

        // Get the request body
        $body = json_decode($request->getBody()->getContents(), true);
        
        // Sanitise the email and with var_filter function
        $currentPassword = isset($body['currentPassword']) ? htmlspecialchars($body['currentPassword']) : null;
        $newPassword = isset($body['newPassword']) ? htmlspecialchars($body['newPassword']) : null;
        
        // Check the passwords do not match
        if ($currentPassword === $newPassword) {
            return new JsonResponse([
                'message' => 'The new password cannot be the same as the current password'
            ], 400);
        }

        // Check the current password is correct
        if (!password_verify($currentPassword, $userData['password'])) {
            return new JsonResponse([
                'message' => 'The current password is incorrect'
            ], 400);
        }

        // Hash the new password
        $newPasswordHash = password_hash($newPassword, PASSWORD_ARGON2ID);

        // Update the password
        $this->app->getDatabase()->update($this->app->getStrings()->getTableUsers(), [
            'password' => $newPasswordHash
        ], [
            'id' => $user['id']
        ]);

        // Return a success response
        return new EmptyResponse(204);
    }
}