<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Providers;

use League\Route\Router;
use League\Route\Http\Exception\NotFoundException;
use League\Route\Http\Exception\UnauthorizedException;
use League\Route\RouteGroup;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use WebsiteSQL\WebsiteSQL\App;
use WebsiteSQL\WebsiteSQL\Middleware\AuthMiddleware;
use WebsiteSQL\WebsiteSQL\Middleware\GuestMiddleware;
use WebsiteSQL\WebsiteSQL\Exceptions\UserNotFoundException;
use WebsiteSQL\WebsiteSQL\Strategy\ApiStrategy;
use WebsiteSQL\WebsiteSQL\Strategy\AppStrategy;
use Exception;

class RoutingProvider
{
    /*
     * This object holds the Medoo database connection
     * 
     * @var Medoo
     */
    private App $app;

    /*
     * This object holds the League Router
     * 
     * @var Router
     */
    private $router;

    /*
     * This array holds the routes
     * 
     * @var array
     */
    private $routes = [
        'app' => [],
        'api' => [],
        'custom' => []
    ];

    /*
     * This object holds the request object
     * 
     * @var ServerRequestInterface
     */
    private $request;

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
     * This method initializes the router
     * 
     * @return void
     */
    public function init(): void
    {
        $this->request = ServerRequestFactory::fromGlobals(
            $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
        );

        $this->router = new Router;

        // Register global middleware
        $this->router->middleware(new \WebsiteSQL\WebsiteSQL\Middleware\CommonMiddleware($this->app));

        // Check if in setup mode
        if ($this->app->getsetup()) {
            // Init the setup routes
            $this->initSetupRoutes();
        } else {
            // Init the application routes
            $this->initAppRoutes();

            // Init the API routes
            $this->initApiRoutes();
        }
    }

    /*
     * This method emits the response
     * 
     * @return void
     */
    public function serve(): void
    {
        // Dispatch the request
        try {
            $this->registerRoutes();

            $response = $this->router->dispatch($this->request);

            // Send the response with custom emitter
            $this->emit($response);
        } catch (NotFoundException $e) {
            $response = new EmptyResponse(404);
            $this->emit($response);
        } catch (UnauthorizedException $e) {
            // Redirect to login page
            $response = new RedirectResponse($this->app->getRouter()->getRoute('app.login'));
            $this->emit($response);
        } catch (UserNotFoundException $e) {
            // Redirect to login page
            $response = new RedirectResponse($this->app->getRouter()->getRoute('app.login'));
            $this->emit($response);
        } catch (Exception $e) {
            // Check if app is in debug mode
            if ($this->app->getEnv('DEBUG')) {
                throw $e;
            } else {
                $response = new EmptyResponse(500);
                $this->emit($response);
            }
        }
    }

    /*
     * This method returns the route for a given name
     * 
     * @param string $name
     * @return string
     */
    public function getRoute(string $name): string
    {
        return $this->router->getNamedRoute($name)->getPath();
    }

    /*
     * This method checks if a given route name is active
     * 
     * @param string $name
     * @param bool $children Should children also match the route
     * @return bool
     */
    public function isActive(string $name, bool $children): bool
    {
        // Get the current route
        $currentRoute = $this->request->getUri()->getPath();

        // Get the route
        $route = $this->getRoute($name);

        // Check if the route is active
        if ($children) {
            return strpos($currentRoute, $route) !== false;
        }

        return $currentRoute === $route;
    }


    /*
     * This method registers the routes
     * 
     * @return void
     */
    public function registerRoutes(): void
    {
        // Create response factories
        $responseFactory = new ResponseFactory();
        
        // Create strategies
        $jsonStrategy = (new ApiStrategy($responseFactory));
        $applicationStrategy = (new AppStrategy());

        // Define Application Routes
        $this->router->group('/', function (RouteGroup $routeGroup) {
            foreach ($this->routes['app'] as $route) {
                // Check if the controller is an array
                if (is_array($route['controller'])) {
                    $routeController = new $route['controller'][0]($this->app);
                    $mappedRoute = $routeGroup->map($route['method'], $route['route'], [$routeController, $route['controller'][1]]);
                } else {
                    $mappedRoute = $routeGroup->map($route['method'], $route['route'], $route['controller']);
                }

                // Set the route name
                $mappedRoute->setName($route['name']);

                // Check if auth is required
                if (isset($route['options']['auth']) && $route['options']['auth'] === true) {
                    $mappedRoute->middleware(new AuthMiddleware($this->app));
                }

                // Check if the auth is not required
                if (isset($route['options']['auth']) && $route['options']['auth'] === false) {
                    $mappedRoute->middleware(new GuestMiddleware($this->app));
                }

                // Check if app access is required
                if (isset($route['options']['app_access']) && $route['options']['app_access'] === true) {
                    $mappedRoute->middleware(new \WebsiteSQL\WebsiteSQL\Middleware\AppAccessMiddleware($this->app));
                }

                // Check if permissions are required
                if (isset($route['options']['permissions'])) {
                    $mappedRoute->middleware(new \WebsiteSQL\WebsiteSQL\Middleware\PermissionMiddleware($this->app, $route['options']['permissions']));
                }
            }
        })->setStrategy($applicationStrategy);

        // Define API Routes
        $this->router->group('/api', function (RouteGroup $routeGroup) {
            foreach ($this->routes['api'] as $route) {
                // Check if the controller is an array
                if (is_array($route['controller'])) {
                    $routeController = new $route['controller'][0]($this->app);
                    $mappedRoute = $routeGroup->map($route['method'], $route['route'], [$routeController, $route['controller'][1]]);
                } else {
                    $mappedRoute = $routeGroup->map($route['method'], $route['route'], $route['controller']);
                }

                // Set the route name
                $mappedRoute->setName($route['name']);
                
                // Check if auth is required
                if (isset($route['options']['auth']) && $route['options']['auth'] === true) {
                    $mappedRoute->middleware(new AuthMiddleware($this->app));
                }

                // Check if the auth is not required
                if (isset($route['options']['auth']) && $route['options']['auth'] === false) {
                    $mappedRoute->middleware(new GuestMiddleware($this->app));
                }

                // Check if app access is required
                if (isset($route['options']['app_access']) && $route['options']['app_access'] === true) {
                    $mappedRoute->middleware(new \WebsiteSQL\WebsiteSQL\Middleware\AppAccessMiddleware($this->app));
                }

                // Check if permissions are required
                if (isset($route['options']['permissions'])) {
                    $mappedRoute->middleware(new \WebsiteSQL\WebsiteSQL\Middleware\PermissionMiddleware($this->app, $route['options']['permissions']));
                }
            }
        })->setStrategy($jsonStrategy);

        // Define Custom Routes
        $this->router->group('/', function (RouteGroup $routeGroup) {
            foreach ($this->routes['custom'] as $route) {
                // Check if the controller is an array
                if (is_array($route['controller'])) {
                    $routeController = new $route['controller'][0]($this->app);
                    $mappedRoute = $routeGroup->map($route['method'], $route['route'], [$routeController, $route['controller'][1]]);
                } else {
                    $mappedRoute = $routeGroup->map($route['method'], $route['route'], $route['controller']);
                }

                // Set the route name
                $mappedRoute->setName($route['name']);

                // Check if auth is required
                if (isset($route['options']['auth']) && $route['options']['auth'] === true) {
                    $mappedRoute->middleware(new AuthMiddleware($this->app));
                }

                // Check if the auth is not required
                if (isset($route['options']['auth']) && $route['options']['auth'] === false) {
                    $mappedRoute->middleware(new GuestMiddleware($this->app));
                }

                // Check if app access is required
                if (isset($route['options']['app_access']) && $route['options']['app_access'] === true) {
                    $mappedRoute->middleware(new \WebsiteSQL\WebsiteSQL\Middleware\AppAccessMiddleware($this->app));
                }

                // Check if permissions are required
                if (isset($route['options']['permissions'])) {
                    $mappedRoute->middleware(new \WebsiteSQL\WebsiteSQL\Middleware\PermissionMiddleware($this->app, $route['options']['permissions']));
                }
            }
        })->setStrategy($applicationStrategy);
    }

    /*
     * This method registers an admin route
     * 
     * @param mixed $method
     * @param string $route
     * @param callable|array $controller
     * @param string $name
     * @param array $options (optional)
     * @return void
     */
    public function registerAppRoute(mixed $method, string $route, $controller, string $name, array $options = []): void
    {
        $this->routes['app'][] = [
            'method' => $method,
            'route' => $route,
            'controller' => $controller,
            'name' => 'app.' . $name,
            'options' => $options
        ];
    }

    /*
     * This method registers an API route
     * 
     * @param mixed $method
     * @param string $route
     * @param callable|array $controller
     * @param string $name
     * @param array $options (optional)
     * @return void
     */
    public function registerApiRoute(mixed $method, string $route, $controller, string $name, array $options = []): void
    {
        $this->routes['api'][] = [
            'method' => $method,
            'route' => $route,
            'controller' => $controller,
            'name' => 'api.' .$name,
            'options' => $options
        ];
    }

    /*
     * This method registers a custom route
     * 
     * @param mixed $method
     * @param string $route
     * @param callable|array $controller
     * @param string $name
     * @param array $options (optional)
     * @return void
     */
    public function registerRoute(mixed $method, string $route, $controller, string $name, array $options = []): void
    {
        $this->routes['custom'][] = [
            'method' => $method,
            'route' => $route,
            'controller' => $controller,
            'name' => $name,
            'options' => $options
        ];
    }

    /*
     * Emit the response by sending headers and the body.
     *
     * @param ResponseInterface $response
     * @return void
     */
    private function emit(ResponseInterface $response): void
    {
        // Send the headers
        $this->sendHeaders($response);

        // Send the body
        $this->sendBody($response);
    }

    /*
     * Sends the headers for the response.
     *
     * @param ResponseInterface $response
     * @return void
     */
    private function sendHeaders(ResponseInterface $response): void
    {
        // Set the HTTP response status code
        http_response_code($response->getStatusCode());

        // Send the headers
        foreach ($response->getHeaders() as $name => $values) {
            // Make sure to send each header
            foreach ($values as $value) {
                header("$name: $value", false);
            }
        }
    }

    /*
     * Sends the body of the response.
     *
     * @param ResponseInterface $response
     * @return void
     */
    private function sendBody(ResponseInterface $response): void
    {
        $body = $response->getBody();

        // If the body is not seekable, we retrieve the contents
        if (!$body->isSeekable()) {
            $body = $body->getContents();
        }

        // Output the response body
        echo $body;
    }

    /*
     * This method initializes all setup routes
     * 
     * @return void
     */
    private function initSetupRoutes(): void
    {
        // Redirect root to setup
        $this->registerAppRoute(['GET'], '', function () {
            return new RedirectResponse($this->app->getRouter()->getRoute('app.setup'));
        }, 'root', []);

        // Register the setup route
        $this->registerAppRoute(['GET', 'POST'], '/setup', [\WebsiteSQL\WebsiteSQL\Controllers\Setup\SetupController::class, 'handle'], 'setup', []);
    }
    
    /*
     * This method initilizes all core application routes
     * 
     * @return void
     */
    private function initAppRoutes(): void
    {
        // Redirect root to dashboard or login depending on auth status
        $this->registerAppRoute(['GET'], '', function () {
            return new RedirectResponse($this->app->getRouter()->getRoute('app.dashboard'));
        }, 'root', [
            'auth' => true
        ]);

        // Register the login route
        $this->registerAppRoute(['GET', 'POST'], '/login', [\WebsiteSQL\WebsiteSQL\Controllers\Auth\LoginController::class, 'handle'], 'login', [
            'auth' => false
        ]);

        // Register the register route
        $this->registerAppRoute(['GET', 'POST'], '/register', [\WebsiteSQL\WebsiteSQL\Controllers\Auth\RegisterController::class, 'handle'], 'register', [
            'auth' => false
        ]);

        // Register the dashboard route
        $this->registerAppRoute('GET', '/dashboard', [\WebsiteSQL\WebsiteSQL\Controllers\App\DashboardController::class, 'handle'], 'dashboard', [
            'auth' => true,
            'app_access' => true
        ]);

        // Register the roles route
        $this->registerAppRoute(['GET', 'POST'], '/media', [\WebsiteSQL\WebsiteSQL\Controllers\App\DashboardController::class, 'handle'], 'media', [
            'auth' => true,
            'app_access' => true,
            'permissions' => 'wsql.roles.read'
        ]);

        // Register route: Updates
        $this->registerAppRoute('GET', '/updates', [\WebsiteSQL\WebsiteSQL\Controllers\App\UpdateController::class, 'handle'], 'updates', [
            'auth' => true
        ]);

        // Register route: Account
        $this->registerAppRoute('GET', '/account', [\WebsiteSQL\WebsiteSQL\Controllers\App\AccountController::class, 'handle'], 'account', [
            'auth' => true
        ]);





        /*----------------------------------------*
         * App: Administration Routes
         *----------------------------------------*/

        // Register route: Allows access to view the settings page
        $this->registerAppRoute('GET', '/admin/settings', [\WebsiteSQL\WebsiteSQL\Controllers\App\Admin\SettingsController::class, 'handle'], 'admin.settings', [
            'app_access' => true,
            'permissions' => 'wsql.settings.read'
        ]);

        // Register route: Allows access to view the user settings page
        $this->registerAppRoute('GET', '/admin/users', [\WebsiteSQL\WebsiteSQL\Controllers\App\Admin\UsersController::class, 'handle'], 'admin.users', [
            'app_access' => true,
            'permissions' => 'wsql.settings.users.read'
        ]);

        // Register route: Allows access to view the user settings page
        $this->registerAppRoute('GET', '/admin/users/{id:uuid}', [\WebsiteSQL\WebsiteSQL\Controllers\App\Admin\Single\UserController::class, 'handle'], 'settings.users.update', [
            'app_access' => true,
            'permissions' => 'wsql.settings.users.update'
        ]);

        // Register route: Allows access to view the access control settings page
        $this->registerAppRoute('GET', '/admin/access-control', [\WebsiteSQL\WebsiteSQL\Controllers\App\Admin\AccessControlController::class, 'handle'], 'admin.access-control', [
            'app_access' => true,
            'permissions' => 'wsql.settings.access-control.read'
        ]);

        // Register route: Allows access to edit access control settings
        $this->registerAppRoute('GET', '/admin/access-control/{id:uuid}', [\WebsiteSQL\WebsiteSQL\Controllers\App\Admin\Single\AccessControlController::class, 'handle'], 'settings.access-control.update', [
            'app_access' => true,
            'permissions' => 'wsql.settings.access-control.update'
        ]);

        // Register route: Allows access to update the settings
        $this->registerAppRoute('GET', '/admin/modules', [\WebsiteSQL\WebsiteSQL\Controllers\App\Admin\ModulesController::class, 'handle'], 'admin.modules', [
            'app_access' => true,
            'permissions' => 'wsql.settings.modules.read'
        ]);

        // Register route: Allows access to update the modules
        $this->registerAppRoute('POST', '/admin/modules', [\WebsiteSQL\WebsiteSQL\Controllers\App\Admin\ModulesController::class, 'handle'], 'settings.modules.post', [
            'app_access' => true,
            'permissions' => 'wsql.settings.modules.update'
        ]);
    }

    /*
     * This method initializes all core API routes
     * 
     * @return void
     */
    private function initApiRoutes(): void
    {
        // Register Route: Media Upload POST
        $this->registerApiRoute('POST', '/media/upload', [\WebsiteSQL\WebsiteSQL\Controllers\Api\Media\Upload\PostController::class, 'handle'], 'media.upload.post', [
            'auth' => true
        ]);


        // Register the settings routes
        $this->registerApiRoute('PATCH', '/settings/logging', [\WebsiteSQL\WebsiteSQL\Controllers\Api\Settings\Logging\PatchController::class, 'handle'], 'settings.logging.patch', [
            'permissions' => 'wsql.api.settings.logging.update'
        ]);

        // Register Route: Customizations GET
        $this->registerApiRoute('GET', '/customizations', [\WebsiteSQL\WebsiteSQL\Controllers\Api\Customizations\GetController::class, 'handle'], 'customizations.get', [
            'permissions' => 'wsql.api.customizations.read'
        ]);

        // Register Route: Settings Branding GET
        $this->registerApiRoute('GET', '/settings/branding', [\WebsiteSQL\WebsiteSQL\Controllers\Api\Settings\Branding\GetController::class, 'handle'], 'settings.branding.get', [
            'permissions' => 'wsql.api.settings.branding.read'
        ]);
        
        // Register Route: Settings Branding PATCH
        $this->registerApiRoute('PATCH', '/settings/branding', [\WebsiteSQL\WebsiteSQL\Controllers\Api\Settings\Branding\PatchController::class, 'handle'], 'settings.branding.patch', [
            'permissions' => 'wsql.api.settings.branding.update'
        ]);

        /*----------------------------------------*
         * API: User Routes
         *----------------------------------------*/

        // Register Route: Users GET (PLA)
        $this->registerApiRoute('GET', '/users', [\WebsiteSQL\WebsiteSQL\Controllers\Api\Users\GetController::class, 'handle'], 'users.get', [
            'permissions' => 'wsql.api.users.read'
        ]);

        // Register Route: Users POST (PLA)
        $this->registerApiRoute('POST', '/users', [\WebsiteSQL\WebsiteSQL\Controllers\Api\Users\PostController::class, 'handle'], 'users.post', [
            'permissions' => 'wsql.api.users.create'
        ]);

        // Register Route: Users Register POST
        $this->registerApiRoute('POST', '/users/register', [\WebsiteSQL\WebsiteSQL\Controllers\Api\Users\Register\PostController::class, 'handle'], 'users.register.post', [
            'auth' => false
        ]);

        // Register Route: Users Me GET
        $this->registerApiRoute('GET', '/users/me', [\WebsiteSQL\WebsiteSQL\Controllers\Api\Users\Me\GetController::class, 'handle'], 'users.me.get', [
            'auth' => true
        ]);

        // Register Route: Users Me PATCH
        $this->registerApiRoute('PATCH', '/users/me', [\WebsiteSQL\WebsiteSQL\Controllers\Api\Users\Me\PatchController::class, 'handle'], 'users.me.patch', [
            'auth' => true
        ]);

        // Register Route: Users Me Reset Password POST
        $this->registerApiRoute('POST', '/users/me/reset-password', [\WebsiteSQL\WebsiteSQL\Controllers\Api\Users\Me\ResetPassword\PostController::class, 'handle'], 'users.me.reset-password.post', [
            'auth' => true
        ]);

        // Register Route: User GET (PLA)
        $this->registerApiRoute('GET', '/users/{id:uuid}', [\WebsiteSQL\WebsiteSQL\Controllers\Api\Users\Single\GetController::class, 'handle'], 'users.single.get', [
            'permissions' => 'wsql.api.users.single.read'
        ]);

        // Register Route: User PATCH (PLA)
        $this->registerApiRoute('PATCH', '/users/{id:uuid}', [\WebsiteSQL\WebsiteSQL\Controllers\Api\Users\Single\PatchController::class, 'handle'], 'users.patch', [
            'permissions' => 'wsql.api.users.single.update'
        ]);

        // Register Route: User DELETE (PLA)
        $this->registerApiRoute('DELETE', '/users/{id:uuid}', [\WebsiteSQL\WebsiteSQL\Controllers\Api\Users\Single\DeleteController::class, 'handle'], 'users.delete', [
            'permissions' => 'wsql.api.users.single.delete'
        ]);

        // Register Route: User Reset Password POST
        $this->registerApiRoute('POST', '/users/{id:uuid}/reset-password', [\WebsiteSQL\WebsiteSQL\Controllers\Api\Users\ResetPassword\PostController::class, 'handle'], 'users.reset-password.post', [
            'permissions' => 'wsql.api.users.single.reset-password'
        ]);

        /*----------------------------------------*
         * API: Role Routes
         *----------------------------------------*/

        // Register Route: Roles GET
        $this->registerApiRoute('GET', '/roles', [\WebsiteSQL\WebsiteSQL\Controllers\Api\Roles\GetController::class, 'handle'], 'roles.get', [
            'permissions' => 'wsql.api.roles.read'
        ]);

        // Register Route: Roles POST
        $this->registerApiRoute('POST', '/roles', [\WebsiteSQL\WebsiteSQL\Controllers\Api\Roles\PostController::class, 'handle'], 'roles.post', [
            'permissions' => 'wsql.api.roles.create'
        ]);

        // Register Route: Role GET
        $this->registerApiRoute('GET', '/roles/{id:uuid}', [\WebsiteSQL\WebsiteSQL\Controllers\Api\Roles\Single\GetController::class, 'handle'], 'roles.single.get', [
            'permissions' => 'wsql.api.roles.read'
        ]);

        // Register Route: Role PATCH
        $this->registerApiRoute('PATCH', '/roles/{id:uuid}', [\WebsiteSQL\WebsiteSQL\Controllers\Api\Roles\Single\PatchController::class, 'handle'], 'roles.single.patch', [
            'permissions' => 'wsql.api.roles.update'
        ]);

        // Register Route: Role DELETE
        $this->registerApiRoute('DELETE', '/roles/{id:uuid}', [\WebsiteSQL\WebsiteSQL\Controllers\Api\Roles\Single\DeleteController::class, 'handle'], 'roles.single.delete', [
            'permissions' => 'wsql.api.roles.delete'
        ]);

        // Register Route: Role Permissions GET
        $this->registerApiRoute('GET', '/roles/{id:uuid}/permissions', [\WebsiteSQL\WebsiteSQL\Controllers\Api\Roles\Single\Permissions\GetController::class, 'handle'], 'roles.single.permissions.get', [
            'permissions' => 'wsql.api.roles.permissions.read'
        ]);

        /*----------------------------------------*
         * API: Permissions Routes
         *----------------------------------------*/

        // Register Route: Permissions GET
        $this->registerApiRoute('GET', '/permissions', [\WebsiteSQL\WebsiteSQL\Controllers\Api\Permissions\GetController::class, 'handle'], 'permissions.get', [
            'permissions' => 'wsql.api.permissions.read'
        ]);

        /*----------------------------------------*
         * API: Auth Routes
         *----------------------------------------*/

        // Register Route: Logout POST
        $this->registerApiRoute('POST', '/auth/logout', [\WebsiteSQL\WebsiteSQL\Controllers\Api\Auth\Logout\PostController::class, 'handle'], 'auth.logout.post', [
            'auth' => true
        ]);
    }
}