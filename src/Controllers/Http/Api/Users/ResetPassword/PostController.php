<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Controllers\Http\Api\Users\ResetPassword;

use Exception;
use Laminas\Diactoros\Response\EmptyResponse;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use WebsiteSQL\WebsiteSQL\App;
use WebsiteSQL\WebsiteSQL\Exceptions\MissingRequiredFieldsException;
use WebsiteSQL\WebsiteSQL\Exceptions\PasswordMismatchException;
use WebsiteSQL\WebsiteSQL\Exceptions\RoleNotFoundException;
use WebsiteSQL\WebsiteSQL\Exceptions\UserAlreadyExistsException;

class PostController implements RequestHandlerInterface
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
        
    }
}