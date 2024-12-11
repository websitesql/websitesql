<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Controllers\App;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use WebsiteSQL\WebsiteSQL\App;

class UpdateController implements RequestHandlerInterface
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
        // Render the updates page
        return new HtmlResponse($this->app->getRenderer()->render('application::updates', [
            'title' => 'Updates',
            'description' => 'Manage updates across the application and its modules.'
        ]));
    }
}