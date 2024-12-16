<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Controllers\Http\App;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use WebsiteSQL\WebsiteSQL\App;

class DashboardController implements RequestHandlerInterface
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
        // Get the dashboard tiles
        $dashboardTiles = $this->app->getCustomization()->getDashboardTiles();

        // Loop through the dashboard tiles and call the content function
        foreach ($dashboardTiles as $key => $tile) {
            try {
                $dashboardTiles[$key]['content_html'] = $tile['content']();
            } catch (\Exception $e) {
                
                $dashboardTiles[$key]['content_html'] = '<h1 class="font-bold mb-3 text-2xl text-red-700">Fatal Error</h1><p class="font-normal leading-5 text-xs text-red-500">' . $e->getMessage() . '</p>';
            }
        }

        // Render the dashboard
        $body = $this->app->getRenderer()->render('application::dashboard', [
            'title' => 'Dashboard',
            'dashboardTiles' => $dashboardTiles
        ]);	

        // Return the response
        return new HtmlResponse($body);
    }
}