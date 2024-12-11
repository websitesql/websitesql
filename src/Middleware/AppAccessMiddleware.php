<?php

namespace WebsiteSQL\WebsiteSQL\Middleware;

use WebsiteSQL\WebsiteSQL\App;
use League\Route\Http\Exception\UnauthorizedException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;

class AppAccessMiddleware implements MiddlewareInterface
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
        // Get the user from the request
        $user = $request->getAttribute('user');

        // Check if user is logged in
        if (!$user) {
            // Redirect to login if user is not logged in
            throw new UnauthorizedException();
        }

        // Check if the role has app access
        $app_access = $this->app->getPermissions()->checkAppAccess($user['id']);

        // Check if the users role has app access
        if (!$app_access) {
            error_log('User does not have app access');
            throw new UnauthorizedException();
        }
        
        // Continue processing the request
        return $handler->handle($request);
    }   
}