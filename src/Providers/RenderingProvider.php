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
     * This object holds the user object
     * 
     * @var int|null
     */
    private $user = null;

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

        // Init functions
        $this->initFunctions();

        // Init folders
        $this->initFolders();

        if ($this->app->getsetup()) {
            // Init setup
            $this->initSetup();
        } else {
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

        // Function: userCheck
        $this->engine->registerFunction('userCheck', function () {
            return $this->user ? true : false;
        });

        // Function: getCurrentUser
        $this->engine->registerFunction('getCurrentUser', function () {
            return $this->user;
        });

        // Function: getRoute
        $this->engine->registerFunction('getRoute', function ($name) {
            return $this->app->getRouter()->getRoute($name);
        });

        // Function: isActive
        $this->engine->registerFunction('isActive', function ($name, $children = false) {
            return $this->app->getRouter()->isActive($name, $children);
        });

        // Function: getMainMenuItems
        $this->engine->registerFunction('getMainMenuItems', function () {
            return $this->app->getCustomization()->getMainMenuItems();
        });

        // Function: getLogo
        $this->engine->registerFunction('getLogo', function () {
            return $this->app->getCustomization()->getLogo();
        });

        // Function: getApplicationName
        $this->engine->registerFunction('getApplicationName', function ($includeVersion = true) {
            return $this->app->getCustomization()->getApplicationName($includeVersion);
        });

        // Function: getCssFiles
        $this->engine->registerFunction('getCssFiles', function ($application = true) {
            $response = '';
             
            // Reverse the array so that the files are loaded in the correct order
            $cssFiles = array_reverse($this->app->getCustomization()->getCssFiles());

            // Remove application CSS files if set to false check array ['name' => 'application', 'path' => '...']
            if (!$application) {
                $cssFiles = array_filter($cssFiles, function ($cssFile) {
                    return $cssFile['name'] !== 'application';
                });
            }

            foreach ($cssFiles as $cssFile) {
                $response .= '<link rel="stylesheet" type="text/css" wsql-id="' . $cssFile['name'] . '" href="' . $cssFile['path'] . '">';
            }

            return $response;
        });

        // Function: getJsFiles
        $this->engine->registerFunction('getJsFiles', function ($application = true) {
            $response = '';

            // Reverse the array so that the files are loaded in the correct order
            $jsFiles = array_reverse($this->app->getCustomization()->getJsFiles());

            // Remove application CSS files if set to false check array ['name' => 'application', 'path' => '...']
            if (!$application) {
                $jsFiles = array_filter($jsFiles, function ($jsFile) {
                    return $jsFile['name'] !== 'application';
                });
            }
            
            foreach ($jsFiles as $jsFile) {
                $response .= '<script wsql-id="' . $jsFile['name'] . '" src="' . $jsFile['path'] . '"></script>';
            }

            return $response;
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
            'app_url' => $this->app->getEnv('PUBLIC_URL'),
            'app_debug' => $this->app->getEnv('DEBUG'),
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
     * This method renders a blank application view
     * 
     * @param string $view
     * @param array $data
     * @return string
     */
    public function renderApplication(string $title, string $content): string
    {
        return $this->app->getRenderer()->render('application::custom', [
            'title' => $title,
            'content' => $content
        ]);
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

    /*
     * This method updates the user object
     * 
     * @param array $user
     * @return void
     */
    public function updateUser(array $user): void
    {
        $this->user = $user;
    }
}