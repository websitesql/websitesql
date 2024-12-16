<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Http\Api\Auth\Logout;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use WebsiteSQL\WebsiteSQL\App;

class PostController
{
    /*
     * This object holds the App class
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
     * This method logs out the user
     * 
     * @return void
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // Get the token from the request
        $token = $request->getAttribute('token');

        // Get the user from the request
        $user = $request->getAttribute('user');

        // If the token is not found, return an error
        if (empty($token)) {
            return new JsonResponse(['status' => 'error', 'message' => 'The token is missing.'], 400);
        }

        // Get the token from the database
        $tokenData = $this->app->getDatabase()->get($this->app->getStrings()->getTableTokens(), ['action', 'user'], ['token' => $token]);

        // Check the token is an authencation token
        if (empty($tokenData) || $tokenData['action'] !== 'authentication') {
            return new JsonResponse(['status' => 'error', 'message' => 'The token is invalid.'], 400);
        }

        // Check the token belongs to the user
        if ($tokenData['user'] !== $user['id']) {
            return new JsonResponse(['status' => 'error', 'message' => 'The token does not belong to the user.'], 400);
        }

        // Delete the token from the database
        $this->app->getDatabase()->delete($this->app->getStrings()->getTableTokens(), ['token' => $token]);

        // Return a response
        return new JsonResponse(['status' => 'success', 'message' => 'The user session has been terminated successfully.']);
    }
}