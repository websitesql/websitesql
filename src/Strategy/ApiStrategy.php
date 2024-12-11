<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Strategy;

use JsonSerializable;
use League\Route\Http;
use League\Route\Http\Exception\{MethodNotAllowedException, NotFoundException};
use League\Route\Route;
use League\Route\{ContainerAwareInterface, ContainerAwareTrait};
use Psr\Http\Message\{ResponseFactoryInterface, ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\{MiddlewareInterface, RequestHandlerInterface};
use League\Route\Strategy\AbstractStrategy;
use League\Route\Strategy\OptionsHandlerInterface;
use Throwable;

class ApiStrategy extends AbstractStrategy implements ContainerAwareInterface, OptionsHandlerInterface
{
    /*
     * This trait allows the class to access the container
     */
    use ContainerAwareTrait;

    /*
     * Constructor
     * 
     * @param ResponseFactoryInterface $responseFactory
     * @return ResponseInterface
     */
    public function __construct(protected ResponseFactoryInterface $responseFactory, protected int $jsonFlags = 0)
    {
        $this->addResponseDecorator(static function (ResponseInterface $response): ResponseInterface {
            if (false === $response->hasHeader('content-type')) {
                $response = $response->withHeader('content-type', 'application/json');
            }

            return $response;
        });
    }

    /*
     * This method returns the method not allowed decorator (Error 405)
     * 
     * @param MethodNotAllowedException $exception
     * @return MiddlewareInterface
     */
    public function getMethodNotAllowedDecorator(MethodNotAllowedException $exception): MiddlewareInterface
    {
        return $this->buildJsonResponseMiddleware($exception);
    }

    /*
     * This method returns the not found decorator (Error 404)
     * 
     * @param NotFoundException $exception
     * @return MiddlewareInterface
     */
    public function getNotFoundDecorator(NotFoundException $exception): MiddlewareInterface
    {
        return $this->buildJsonResponseMiddleware($exception);
    }

    /*
     * This method returns the options callable
     * 
     * @param array $methods
     * @return callable
     */
    public function getOptionsCallable(array $methods): callable
    {
        return function () use ($methods): ResponseInterface {
            $options  = implode(', ', $methods);
            $response = $this->responseFactory->createResponse();
            $response = $response->withHeader('allow', $options);
            return $response->withHeader('access-control-allow-methods', $options);
        };
    }
    
    /*
     * This method returns the throwable handler
     * 
     * @return MiddlewareInterface
     */
    public function getThrowableHandler(): MiddlewareInterface
    {
        return new class ($this->responseFactory->createResponse()) implements MiddlewareInterface
        {
            protected $response;

            public function __construct(ResponseInterface $response)
            {
                $this->response = $response;
            }

            public function process(
                ServerRequestInterface $request,
                RequestHandlerInterface $handler
            ): ResponseInterface {
                try {
                    return $handler->handle($request);
                } catch (Throwable $exception) {
                    $response = $this->response;

                    if ($exception instanceof Http\Exception) {
                        return $exception->buildJsonResponse($response);
                    }

                    $response->getBody()->write(json_encode([
                        'status_code'   => 500,
                        'reason_phrase' => $exception->getMessage()
                    ]));

                    $response = $response->withAddedHeader('content-type', 'application/json');
                    return $response->withStatus(500, strtok($exception->getMessage(), "\n"));
                }
            }
        };
    }

    /*
     * This method invokes the route callable
     * 
     * @param Route $route
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function invokeRouteCallable(Route $route, ServerRequestInterface $request): ResponseInterface
    {
        $controller = $route->getCallable($this->getContainer());
        $response = $controller($request, $route->getVars());

        if ($this->isJsonSerializable($response)) {
            $body = json_encode($response, $this->jsonFlags);
            $response = $this->responseFactory->createResponse();
            $response->getBody()->write($body);
        }

        return $this->decorateResponse($response);
    }

    /*
     * This method builds the JSON response middleware
     * 
     * @param Http\Exception $exception
     * @return MiddlewareInterface
     */
    protected function buildJsonResponseMiddleware(Http\Exception $exception): MiddlewareInterface
    {
        return new class ($this->responseFactory->createResponse(), $exception) implements MiddlewareInterface
        {
            protected $response;
            protected $exception;

            public function __construct(ResponseInterface $response, Http\Exception $exception)
            {
                $this->response  = $response;
                $this->exception = $exception;
            }

            public function process(
                ServerRequestInterface $request,
                RequestHandlerInterface $handler
            ): ResponseInterface {
                return $this->exception->buildJsonResponse($this->response);
            }
        };
    }

    /*
     * This method checks if the response is JSON serializable
     * 
     * @param mixed $response
     * @return bool
     */
    protected function isJsonSerializable($response): bool
    {
        if ($response instanceof ResponseInterface) {
            return false;
        }

        return (is_array($response) || is_object($response) || $response instanceof JsonSerializable);
    }
}