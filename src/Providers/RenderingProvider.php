<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Providers;

use WebsiteSQL\WebsiteSQL\App;
use League\Plates\Engine;
use Exception;

class RenderingProvider
{
    /*
     * This object holds the Medoo database connection
     * 
     * @var Medoo
     */
    private App $app;

    /*
     * This object holds the Engine class
     * 
     * @var Engine
     */
    private $engine;

    /*
     * This string holds the rendering path
     * 
     * @var string
     */
    private $renderingPath;

    /*
     * Constructor
     * 
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->renderingPath = $this->app->getBasePath() . '/resources/views';
    }

    /*
     * This method initializes the router
     * 
     * @return void
     */
    public function init(): void
    {
        // Create new Plates instance
        $this->engine = new Engine();

        if ($this->app->getSetupMode()) {
            // Init setup
            $this->initSetup();
        } else {
            // Init folders
            $this->initFolders();

            // Init functions
            $this->initFunctions();

            // Init global data
            $this->initGlobalData();
        }
    }

    /*
     * This method initializes folders for the rendering engine
     * 
     * @return void
     */
    private function initFolders(): void
    {
        // Add view folders for templates
        $this->engine->addFolder('application', $this->renderingPath . '/application');
        $this->engine->addFolder('layout', $this->renderingPath . '/layouts');
        $this->engine->addFolder('errors', $this->renderingPath . '/errors');
        $this->engine->addFolder('component', $this->renderingPath . '/components');
    }

    /*
     * This method initializes functions for the rendering engine
     * 
     * @return void
     */
    private function initFunctions(): void
    {
        // Function: getStrings
        $this->engine->registerFunction('getStrings', function () {
            return $this->app->getStrings();
        });

        // Function: getSetting
        $this->engine->registerFunction('getSetting', function ($name) {
            return $this->app->getSetting($name);
        });

        // Function: getCurrentUser
        $this->engine->registerFunction('getCurrentUser', function () {
            $userId = $this->app->getAuth()->getUserID($this->app->getAuth()->getSessionToken());

            if (!$userId) {
                return null;
            }

            return $this->app->getUser()->getUserById($userId);
        });

        // Function: getModuleCustomMenuItems
        $this->engine->registerFunction('getModuleCustomMenuItems', function () {
            return $this->app->getModules()->getModuleCustomMenuItems();
        });

        // Function: getModuleCustomCssFiles
        $this->engine->registerFunction('getModuleCustomCssFiles', function () {
            return $this->app->getModules()->getModuleCustomCssFiles();
        });

        // Function: userCheck
        $this->engine->registerFunction('userCheck', function () {
            return $this->app->getAuth()->check($this->app->getAuth()->getSessionToken());
        });

        // Function: getRoute
        $this->engine->registerFunction('getRoute', function ($name) {
            return $this->app->getRouter()->getRoute($name);
        });
    }

    /*
     * This method initializes global data for the rendering engine
     * 
     * @return void
     */
    private function initGlobalData(): void
    {
        // Add _ENV data
        $this->engine->addData([
            'app_url' => $_ENV['APP_URL'],
            'app_debug' => $_ENV['APP_DEBUG'],
            'app_env' => $_ENV['APP_ENV'],
        ]);
        
        // Add _SESSION data
        $this->engine->addData([
            'csrf_token' => $_SESSION['csrf_token'],
        ]);
    }

    /*
     * This method initializes the setup
     * 
     * @return void
     */
    private function initSetup(): void
    {
        // Register the setup folder
        $this->engine->addFolder('setup', $this->renderingPath . '/setup');

        // Function: getStrings
        $this->engine->registerFunction('getStrings', function () {
            return $this->app->getStrings();
        });

        // Add _SESSION data
        $this->engine->addData([
            'csrf_token' => $_SESSION['csrf_token'],
        ]);
    }

    /*
     * This method renders the view
     * 
     * @param string $view
     * @param array $data
     * @return string
     */
    public function render(string $view, array $data = []): string
    {
        return $this->engine->render($view, $data);
    }

    /*
     * This method adds a folder to the rendering engine
     * 
     * @return void
     */
    public function addFolder(string $name, string $path): void
    {
        $this->engine->addFolder($name, $path);
    }
}