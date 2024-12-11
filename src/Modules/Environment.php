<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Modules;

use Exception;
use Laminas\Diactoros\Response\HtmlResponse;
use WebsiteSQL\WebsiteSQL\Exceptions\ModuleNotEnabledException;
use WebsiteSQL\WebsiteSQL\Providers\RenderingProvider;
use WebsiteSQL\WebsiteSQL\App;
use Medoo\Medoo;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use WebsiteSQL\WebsiteSQL\Providers\MailProvider;

class Environment
{
    /*
     * This object is an instance of the App class
     * 
     * @var App
     */
    private $app;

    /*
     * This string contains the module array
     * 
     * @var array
     */
    private $module;

    /*
     * Constructor
     */
    public function __construct(App $app, array $module)
    {
        $this->app = $app;
        $this->module = $module;
    }

    /*
     * Get the database connection
     * 
     * @return Medoo
     */
    public function getDatabase(): Medoo
    {
        return $this->app->getDatabase();
    }

    /*
     * This method gets the renderer object
     * 
     * @return Engine
     */
    public function getRenderer(): RenderingProvider
    {
        return $this->app->getRenderer();
    }

    /*
     * This method gets the module path
     * 
     * @return string
     */
    public function getModulePath(): string
    {
        return $this->app->getModules()->getModuleBasePath() . '/' . $this->module['slug'];
    }

    /*
     * This method gets the module url
     * 
     * @return string
     */
    public function getModuleUrl(): string
    {
        return $this->app->getEnv('PUBLIC_URL') . '/wsql-contents/modules/' . $this->module['slug'];
    }

    /*
     * This method allows a module to register a App route
     * 
     * @param string $method
     * @param string $route
     * @param callable $callback
     * @param string $name
     * @param array $options
     * @return void
     */
    public function registerAppRoute($method, $route, $callback, $name, $options = []): void
    {

        // Set the callback to return a HtmlResponse
        $callback = function ($request, $args = []) use ($callback) {
            return $callback($request, $args);
        };

        // Register the route
        $this->app->getRouter()->registerAppRoute($method, $route, $callback, $name, $options);
    }

    /*
     * This method allows a module to register an API route
     * 
     * @param string $method
     * @param string $route
     * @param callable $callback
     * @param string $name
     * @param array $options
     * @return void
     */
    public function registerApiRoute($method, $route, $callback, $name, $options = []): void
    {
        // Set the callback to return a setting the request
        $callback = function ($request, $args = []) use ($callback) {
            return $callback($request, $args);
        };

        // Register the route
        $this->app->getRouter()->registerApiRoute($method, $route, $callback, $name, $options);
    }

    /*
     * This method allows a module to register a custom route
     * 
     * @param string $method
     * @param string $route
     * @param callable $callback
     * @param string $name
     * @param array $options
     * @return void
     */
    public function registerRoute($method, $route, $callback, $name, $options = []): void
    {
        // Set the callback to return a HtmlResponse
        $callback = function ($request, $args = []) use ($callback) {
            return $callback($request, $args);
        };

        $this->app->getRouter()->registerRoute($method, $route, $callback, $name, $options);
    }

    /*
     * This method adds an action to the customisation provider
     * 
     * @param string $id
     * @param callable $callback
     * @param array $options
     * @return void
     */
    public function registerDashboardTile(string $id, callable $callback, array $options = null): void
    {
        // Set default options
        $options = [
            'borderColor' => $options['borderColor'] ?? 'border-gray-200',
            'height' => $options['height'] ?? 'col-span-1',
            'width' => $options['width'] ?? 'row-span-1',
            'position' => $options['position'] ?? 100,
            'permissions' => $options['permissions'] ?? []
        ];

        // Add the dashboard tile
        $this->app->getCustomization()->addDashboardTile($id, $callback, $options['borderColor'], $options['height'], $options['width'], $options['position'], $options['permissions']);
    }

    /*
     * This method allows you to register a menu item with the customisation provider
     * 
     * @param string $title
     * @param string $route
     * @param string $icon
     * @param int $position
     * @param string $permission
     * @return void
     */
    public function registerMenuItem(string $title, string $route, string $icon, int $position, string $permission = ''): void
    {
        $this->app->getCustomization()->addMainMenuItem($title, $route, $icon, $position, $permission);
    }

    /*
     * This method allows you to register a menu divider with the customisation provider
     * 
     * @param int $position
     * @param string $title
     * @return void
     */
    public function registerMenuDivider(int $position, string $title = null): void
    {
        $this->app->getCustomization()->addMainMenuItemDivider($position, $title);
    }

    /*
     * This method allows you to register a css file with the customisation provider
     * 
     * @param string $name
     * @param string $path
     * @return void
     */
    public function registerCssFile(string $name, string $path): void
    {
        $this->app->getCustomization()->addCssFile($name, $this->getModuleUrl() . $path);
    }

    /*
     * This method allows you to register a js file with the customisation provider
     * 
     * @param string $name
     * @param string $path
     * @return void
     */
    public function registerJsFile(string $name, string $path): void
    {
        $this->app->getCustomization()->addJsFile($name, $this->getModuleUrl() . $path);
    }

    /*
     * This method adds the ability to register a folder with the rendering engine
     * 
     * @return void
     */
    public function registerFolder(string $name, string $path): void
    {
        $this->app->getRenderer()->addFolder($name, $path);
    }

    /*
     * This method adds the ability to render with the rendering engine
     * 
     * @return void
     */
    public function render(string $view, array $data = []): string
    {
        return $this->app->getRenderer()->render($view, $data);
    }


    /*
     * This method allows you to get the MailProvider
     * 
     * @return MailProvider
     */
    public function getMail(): MailProvider
    {
        return $this->app->getMail();
    }

    /*
     * This method generates a uuid
     * 
     * @param string $version
     * @return string
     */
    public function generateUuid($version = 4): string
    {
        return $this->app->getUtilities()->uuid($version);
    }
}