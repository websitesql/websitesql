<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Providers;

use League\Route\Router;
use League\Route\RouteGroup;
use League\Route\Strategy\ApplicationStrategy;
use League\Route\Strategy\JsonStrategy;
use League\Route\Http\Exception\NotFoundException;
use League\Route\Http\Exception\UnauthorizedException;
use Laminas\Diactoros\ResponseFactory;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Diactoros\Response\EmptyResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use WebsiteSQL\WebsiteSQL\App;
use WebsiteSQL\WebsiteSQL\Middleware\AuthMiddleware;
use WebsiteSQL\WebsiteSQL\Middleware\GuestMiddleware;
use WebsiteSQL\WebsiteSQL\Middleware\AccessMiddleware;
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
     * This object holds the request object
     * 
     * @var ServerRequestInterface
     */
    private $request;

    /*
     * This array holds the routes
     * 
     * @var array
     */
    private $routes = [];

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
    }

    /*
     * This method emits the response
     * 
     * @return void
     */
    public function serve(): void
    {
        $this->registerRoutes();

        // Dispatch the request
        try {
            $response = $this->router->dispatch($this->request);

            // Send the response with custom emitter
            $this->emit($response);
        } catch (NotFoundException $e) {
            $response = new EmptyResponse(404);
            $this->emit($response);
        } catch (UnauthorizedException $e) {
            // Redirect to login page
            $response = new RedirectResponse($this->app->getRouter()->getRoute('admin.login'));
            $this->emit($response);
        } catch (Exception $e) {
            $this->app->log('error', $e->getMessage());
            $response = new EmptyResponse(500);
            $this->emit($response);
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
     * This method registers the routes
     * 
     * @return void
     */
    public function registerRoutes(): void
    {
        // Loop through the admin routes
        foreach ($this->routes as $route) {
            // Check if the controller is an array
            if (is_array($route['controller'])) {
                $routeController = new $route['controller'][0]($this->app);
                $mappedRoute = $this->router->map($route['method'], $route['route'], [$routeController, $route['controller'][1]]);
            } else {
                $mappedRoute = $this->router->map($route['method'], $route['route'], $route['controller']);
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
        }
    }

    /*
     * This method registers an app route
     * 
     * @param string $method
     * @param string $route
     * @param callable $controller
     * @param string $name
     * @param array $options (optional)
     * @return void
     */
    public function registerAppRoute(string $method, string $route, callable $controller, string $name, array $options = []): void
    {
        $this->routes[] = [
            'method' => $method,
            'route' => $route,
            'controller' => $controller,
            'name' => $name,
            'options' => $options
        ];
    }

    /*
     * This method registers an admin route
     * 
     * @param string|array $method
     * @param string $route
     * @param callable|array $controller
     * @param string $name
     * @param array $options (optional)
     * @return void
     */
    public function registerAdminRoute(string|array $method, string $route, $controller, string $name, array $options = []): void
    {
        $this->routes[] = [
            'method' => $method,
            'route' => '/' . $this->app->getStrings()->getAdminFilePath() . $route,
            'controller' => $controller,
            'name' => 'admin.' . $name,
            'options' => $options
        ];
    }

    /*
     * This method registers an API route
     * 
     * @param string $method
     * @param string $route
     * @param callable $controller
     * @param string $name
     * @param array $options (optional)
     * @return void
     */
    public function registerApiRoute(string $method, string $route, callable $controller, string $name, array $options = []): void
    {
        $this->routes[] = [
            'method' => $method,
            'route' => '/api' . $route,
            'controller' => $controller,
            'name' => 'api.' .$name,
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
}