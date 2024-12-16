<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Http\Api\Settings\Logging;

use Laminas\Diactoros\Response\EmptyResponse;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use WebsiteSQL\WebsiteSQL\App;

class PatchController implements RequestHandlerInterface
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
        // Get the request body
        $body = json_decode($request->getBody()->getContents(), true);

        // Check if the body is empty
        if (empty($body)) {
            return new HtmlResponse('The request body is empty.', 400);
        }

        // Get the logging settings
        $trace = $body['trace'] ?? false;
        $performance = $body['performance'] ?? false;
        $transaction = $body['transaction'] ?? false;

        // Construct the settings array
        $settings = [
            'channels' => [
                'trace' => $trace,
                'performance' => $performance,
                'transaction' => $transaction
            ]
        ];

        // Update the settings
        $this->app->getDatabase()->update($this->app->getStrings()->getTableSettings(), ['value' => json_encode($settings)], ['name' => 'loggingConfiguration']);
        
        // Return the response
        return new EmptyResponse(204);
    }
}