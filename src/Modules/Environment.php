<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Modules;

use WebsiteSQL\WebsiteSQL\Exceptions\ModuleNotEnabledException;
use WebsiteSQL\WebsiteSQL\Providers\RenderingProvider;
use WebsiteSQL\WebsiteSQL\App;
use Medoo\Medoo;

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
     * This method gets settings from the settings table
     * 
     * @return string
     */
    public function getSetting($name)
    {
        return $this->app->getSetting($name);
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
     * This method allows the module to register custom actions
     * 
     * @return array
     */
    public function registerCustomAction($module, $action, $callback = null, $pageName = null, $pageSlug = null): void
    {
        // Get the module by slug
        $module = $this->app->getDatabase()->get($this->app->getStrings()->getTableModules(), '*', ['slug' => $module]);
        
        // Check if the module is enabled
        if (!$this->app->getModules()->isEnabled((int)$module['id'])) {
            throw new ModuleNotEnabledException();
        }

        // Register the custom action
        switch ($action) {
            case 'custom_page':
                $this->app->getModules()->setModuleCustomPage($pageSlug, [
                    'module' => $module['id'],
                    'name' => $pageName,
                    'callback' => $callback,
                ]);
                break;
            case 'menu_item':
                $this->app->getModules()->setModuleCustomMenuItem([
                    'module' => $module['id'],
                    'name' => $pageName,
                    'slug' => $pageSlug,
                ]);
                break;
            case 'dashboard_widget':
                $this->app->getModules()->setModuleCustomDashboardWidget($callback);
                break;
            case 'css_file':
                $this->app->getModules()->setModuleCustomCssFile($callback);
                break;
            case 'script_file':
                $this->app->getModules()->setModuleCustomScriptFile($callback);
                break;
            default:
                throw new Exception('Invalid custom action');
        }
    }
}