<?php

namespace WebsiteSQL\WebsiteSQL\Middleware;

use Error;
use WebsiteSQL\WebsiteSQL\App;
use League\Route\Http\Exception\UnauthorizedException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;

class AuthMiddleware implements MiddlewareInterface
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
        // Initialize token variable
        $token = null;

        // Check for token in Authorization header (Bearer token)
        $authorizationHeader = $request->getHeaderLine('Authorization');
        if (!empty($authorizationHeader)) {
            $token = substr($authorizationHeader, 7); // Remove "Bearer "
        }

        // If Bearer token is not found, check for token in Cookie header
        if (empty($token)) {
            $token = $this->getTokenFromCookieHeader($request->getHeaderLine('Cookie'));
        }
        
        // Check if token is still empty
        if (empty($token)) {
            throw new UnauthorizedException();
        }

        // Try to verify the token
        try {
            $cookieValue = $this->app->getAuth()->check($token);
        } catch (Error $e) {
            throw new UnauthorizedException();
        }

        // Get user details from token
        $userId = $this->app->getAuth()->getUserID($token);
        $user = $this->app->getUser()->getUserById($userId);

        // Add user details to request
        $request = $request->withAttribute('user', $user);

        // Proceed with the request handling
        $response = $handler->handle($request);

        // Add the Set-Cookie header to the response
        return $response->withAddedHeader('Set-Cookie', $cookieValue);
    }

    /*
     * This method gets a token from the Cookie header
     * 
     * @param string $cookieHeader
     * @return string|null
     */
    private function getTokenFromCookieHeader($cookieHeader) {
        $cookies = explode(';', $cookieHeader);
        foreach ($cookies as $cookie) {
            $cookieParts = explode('=', trim($cookie), 2);
            if (count($cookieParts) === 2) {
                list($name, $value) = $cookieParts;
                if ($name === 'access_token') {
                    return $value;
                }
            }
        }
        return null;
    }    
}