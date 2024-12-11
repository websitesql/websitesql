<?php

namespace WebsiteSQL\WebsiteSQL\Middleware;

use Error;
use Exception;
use WebsiteSQL\WebsiteSQL\App;
use League\Route\Http\Exception\UnauthorizedException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;

class CommonMiddleware implements MiddlewareInterface
{
    /*
     * This object holds the database connection
     * 
     * @var Medoo
     */
    private App $app;

    /*
     * Constructor
     * 
     * @param Medoo $database
     */
    public function __construct(App $app) {
        $this->app = $app;
    }

    /*
     * This method processes the request
     * 
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * 
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
        // Get the token from the Authorization header if it exists
        $authorizationHeader = $request->getHeaderLine('Authorization');
        $authorizationToken = $this->app->getUtilities()->parseAuthorization($authorizationHeader);

        // Get the token from the Cookie header if it exists
        $parsedCookies = $this->app->getUtilities()->parseCookies($request->getHeaderLine('Cookie'));
        $cookiesToken = $parsedCookies['access_token'] ?? null;

        // Get the token
        $token = $authorizationToken ?? $cookiesToken;

        // Verify the token
        $cookieValue = $this->app->getAuth()->check($token);

        // Check if the check method returned a string
        if ($cookieValue) {
            // Get user details from token
            $userId = $this->app->getAuth()->getUserID($token);
            $user = $this->app->getUser()->getUserById($userId);

            // Update user details in the rendering engine
            $this->app->getRenderer()->updateUser($user);

            // Add user details to request
            $request = $request->withAttribute('user', $user);

            // Add token to request
            $request = $request->withAttribute('token', $token);

            // Proceed with the request handling
            $response = $handler->handle($request);

            // Add the Set-Cookie header to the response
            return $response->withAddedHeader('Set-Cookie', $cookieValue);
        } else {
            // Continue processing the request
            return $handler->handle($request);
        }
    }
}