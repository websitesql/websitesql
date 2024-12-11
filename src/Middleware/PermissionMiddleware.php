<?php

namespace WebsiteSQL\WebsiteSQL\Middleware;

use Exception;
use WebsiteSQL\WebsiteSQL\App;
use League\Route\Http\Exception\UnauthorizedException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;

class PermissionMiddleware implements MiddlewareInterface
{
    /*
     * This object holds the database connection
     * 
     * @var Medoo
     */
    private App $app;

    /*
     * This string holds the permission
     * 
     * @var string
     */
    private string $permission; 

    /*
     * Constructor
     * 
     * @param Medoo $database
     */
    public function __construct(App $app, string $permission) {
        $this->app = $app;
        $this->permission = $permission;
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

        // The array of permissions if the user has any one of these permissions, they can access the page
        if ($this->app->getPermissions()->checkPermission($user['id'], $this->permission)) {
            return $handler->handle($request);
        }

        // If the user does not have any of the permissions, throw an exception
        throw new UnauthorizedException();
    }   
}