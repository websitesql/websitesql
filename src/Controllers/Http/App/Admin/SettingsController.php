<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Controllers\Http\App\Admin;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use WebsiteSQL\WebsiteSQL\App;

class SettingsController implements RequestHandlerInterface
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
        // Render the dashboard
        return new HtmlResponse($this->app->getRenderer()->render('application::admin/settings', [
            'title' => 'Administration',
            'subtitle' => 'Settings',
            'description' => 'Manage your application configuration, settings, and preferences.',
        ]));
    }
}