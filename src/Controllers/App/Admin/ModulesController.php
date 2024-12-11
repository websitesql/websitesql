<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Controllers\App\Admin;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use WebsiteSQL\WebsiteSQL\Exceptions\InvalidTokenException;
use WebsiteSQL\WebsiteSQL\Exceptions\ModuleNotFoundException;
use WebsiteSQL\WebsiteSQL\App;
use Exception;

class ModulesController implements RequestHandlerInterface
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
        // Enable the module
        if (isset($_POST['doEnable'])) {
            try {
                // Check if the CRSF token was passed and is valid
                $csrfToken = htmlspecialchars($_POST['csrf_token']);
                if (!$csrfToken || $csrfToken !== $_SESSION['csrf_token']) {
                    throw new InvalidTokenException();
                }

                // Enable the module
                $module = filter_input(INPUT_POST, 'module', FILTER_SANITIZE_NUMBER_INT);
				$this->app->getModules()->enable((int)$module);
            } catch (InvalidTokenException $e) {
                $error = $this->app->getStrings()->getMessageInvalidToken();
            } catch (ModuleNotFoundException $e) {
                $error = $this->app->getStrings()->getMessageModuleNotFound();
            } catch (Exception $e) {
                $error = $this->app->getStrings()->getMessageUnknownErrorOccurred();
            }
        }

        // Disable the module
        if (isset($_POST['doDisable'])) {
            try {
                // Check if the CRSF token was passed and is valid
                $csrfToken = htmlspecialchars($_POST['csrf_token']);
                if (!$csrfToken || $csrfToken !== $_SESSION['csrf_token']) {
                    throw new InvalidTokenException();
                }

                // Enable the module
				$module = filter_input(INPUT_POST, 'module', FILTER_SANITIZE_NUMBER_INT);
				$this->app->getModules()->disable((int)$module);
            } catch (InvalidTokenException $e) {
                $error = $this->app->getStrings()->getMessageInvalidToken();
            } catch (ModuleNotFoundException $e) {
                $error = $this->app->getStrings()->getMessageModuleNotFound();
            } catch (Exception $e) {
                $error = $this->app->getStrings()->getMessageUnknownErrorOccurred();
            }
        }

        // Get all modules
		$modules = $this->app->getDatabase()->select($this->app->getStrings()->getTableModules(), "*", ["ORDER" => ["ID" => "ASC"]]);

        // Render the settings
        $body = $this->app->getRenderer()->render('application::admin/modules', [
            'title' => 'Administration',
            'subtitle' => 'Modules',
            'description' => 'Here you can manage your modules. Modules are extensions that add functionality to the application. You can enable or disable them here.',
            'modules' => $modules,
            'error' => $error ?? null
        ]);	

        // Return the response
        return new HtmlResponse($body);
    }
}