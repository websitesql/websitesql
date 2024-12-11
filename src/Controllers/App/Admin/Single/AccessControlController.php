<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Controllers\App\Admin\Single;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use WebsiteSQL\WebsiteSQL\App;

class AccessControlController implements RequestHandlerInterface
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
        // Get arguments
        $args = $request->getAttributes();

        // Get the role name
        $role = $this->app->getDatabase()->get($this->app->getStrings()->getTableRoles(), ['name'], ['uuid' => $args['id']]);

        // Check if the role exists
        if (empty($role)) {
            // Redirect to the roles page
            return new RedirectResponse($this->app->getRouter()->getRoute('app.settings.access-control'));
        }
        
        // Render the dashboard
        $body = $this->app->getRenderer()->render('application::admin/single/access-control', [
            'title' => 'Access Control',
            'subtitle' => $role['name']
        ]);	

        // Return the response
        return new HtmlResponse($body);
    }
}