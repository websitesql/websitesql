<?php

namespace WebsiteSQL\WebsiteSQL\Middleware;

use WebsiteSQL\WebsiteSQL\App;
use League\Route\Http\Exception\UnauthorizedException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;

class GuestMiddleware implements MiddlewareInterface
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
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
        // Check if user is logged in from the request
        if ($request->getAttribute('user')) {
            // Redirect to dashboard if user is logged in
            return new \Laminas\Diactoros\Response\RedirectResponse('/dashboard');
        }

        // Continue processing the request
        return $handler->handle($request);
    }
}