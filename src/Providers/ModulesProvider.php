<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Providers;

use WebsiteSQL\WebsiteSQL\App;
use WebsiteSQL\WebsiteSQL\Exceptions\ModuleNotFoundException;
use WebsiteSQL\WebsiteSQL\Exceptions\CustomPageNotFoundException;
use WebsiteSQL\WebsiteSQL\Modules\Environment;

class ModulesProvider
{
    /*
     * This object holds the Medoo database connection
     * 
     * @var Medoo
     */
    private App $app;

    /*
     * This string holds the basePath for the modules
     * 
     * @var string
     */
    private $modulePath;

    /*
     * Constructor
     * 
     * @param string $realm
     * @param Medoo $database
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->modulePath = $app->getBasePath() . '/public/wsql-contents/modules';
    }

    /*
     * This method initializes the application
     * 
     * @return void
     */
    public function init(): void
    {
        // Detect if the modules are installed or removed
        $this->detectModules();

        // Initialize modules
        $this->initModules();
    }

    /*
     * This method detects if the modules is installed or removed
     * 
     * @return void
     */
    public function detectModules(): void
    {
        // Get all modules from the database
        $modules = $this->app->getDatabase()->select($this->app->getStrings()->getTableModules(), '*');

        // Get all module files
        $moduleFiles = glob($this->modulePath . '/*/*.php');

        // Filter files to match the condition where the folder name matches the PHP file name
        $matchingFiles = array_filter($moduleFiles, function ($filePath) {
            $folderName = basename(dirname($filePath)); // Get the folder name
            $fileNameWithoutExtension = basename($filePath, '.php'); // Get the file name without extension

            // Check if the folder name matches the file name
            return $folderName === $fileNameWithoutExtension;
        });

        // Loop through module files
        foreach ($matchingFiles as $moduleFile) {
            // Get the module slug
            $moduleSlug = basename(dirname($moduleFile));

            // Check if the module is in the database
            if (!in_array($moduleSlug, array_column($modules, 'slug'))) {
                // Insert the module into the database
                $this->app->getDatabase()->insert($this->app->getStrings()->getTableModules(), [
                    'slug' => $moduleSlug,
                    'name' => '',
                    'description' => '',
                    'version' => '',
                    'author' => '',
                ]);
            }
        }

        // Loop through modules in the database
        foreach ($modules as $module) {
            // Check if the module file exists
            if (!file_exists($this->modulePath . '/' . $module['slug'] . '/' . $module['slug'] . '.php')) {
                // Delete the module from the database
                $this->app->getDatabase()->delete($this->app->getStrings()->getTableModules(), ['id' => $module['id']]);
            }
        }
    }

    /*
     * This method imports root class files from /public/wsql-contents/modules/{example_module}/{example_module}.php classname ExampleModule
     * 
     * @return void
     */
    private function initModules(): void
    {
        // Get all modules from the database
        $modules = $this->app->getDatabase()->select($this->app->getStrings()->getTableModules(), '*');


        // Loop through module files
        foreach ($modules as $module) {
            // Get the module file
            $moduleFile = $this->modulePath . '/' . $module['slug'] . '/' . $module['slug'] . '.php';

            // Include module file
            include_once $moduleFile;

            // Get module class name (This is the filename without the extension in PascalCase)
            $moduleClass = str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', basename($moduleFile, '.php'))));
            
            // Get module class instance passing the App instance
            $moduleInstance = new $moduleClass($this->app);

            // Get the module meta data
            $moduleMetaData = $moduleInstance->getMetaData();

            // Get the module slug
            $moduleSlug = basename(dirname($moduleFile));

            // Check if the module in database has been seeded
            if ($module['name'] == '' || $module['description'] == '' || $module['version'] == '' || $module['author'] == '') {
                // Update the module in the database
                $this->app->getDatabase()->update($this->app->getStrings()->getTableModules(), [
                    'name' => $moduleMetaData['name'],
                    'description' => $moduleMetaData['description'],
                    'version' => $moduleMetaData['version'],
                    'author' => $moduleMetaData['author'],
                ], ['slug' => $moduleSlug]);

                // Log in the transaction log that the module has been seeded
                // $this->app->log('transaction', 'module.seed', 'Module ' . $moduleMetaData['name'] . ' has been seeded.', null, $this->app->getStrings()->getTableModules(), $module['id']);

                // Reload the module from the database
                $module = $this->app->getDatabase()->get($this->app->getStrings()->getTableModules(), '*', ['slug' => $moduleSlug]);
            }

            // Check if the module has been updated
            if ($module['version'] != $moduleMetaData['version']) {
                // Log in the transaction log that the module has been updated
                // $this->app->log('transaction', 'module.update', 'Module ' . $moduleMetaData['name'] . ' has been updated from ' . $module['version'] . ' to ' . $moduleMetaData['version'], null, $this->app->getStrings()->getTableModules(), $module['id']);

                // Run the modules update script
                $moduleInstance->update();

                // Update the module in the database
                $this->app->getDatabase()->update($this->app->getStrings()->getTableModules(), [
                    'name' => $moduleMetaData['name'],
                    'description' => $moduleMetaData['description'],
                    'version' => $moduleMetaData['version'],
                    'author' => $moduleMetaData['author'],
                ], ['slug' => $moduleSlug]);
            }

            // Check if the module is enabled
            if ($module['enabled'] == 1) {
                // Initialize the module
                $moduleInstance->init();
            }
        }
    }

     
    /*
     * This method gets the module instance by ID
     * 
     * @return object
     */
    public function getModule($moduleId): object
    {
        // Check if the module is already installed
        $ModuleRow = $this->app->getDatabase()->get($this->app->getStrings()->getTableModules(), '*', ['id' => $moduleId]);
        if (!$ModuleRow)
        {
            throw new ModuleNotFoundException();
        }

        // Get the module path
        $moduleFile = $this->modulePath . '/' . $ModuleRow['slug'] . '/' . $ModuleRow['slug'] . '.php';

        // Include the module file
        require_once $moduleFile;

        // Get the module class
        $moduleClass = str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', basename($moduleFile, '.php'))));

        // Return the module instance
        return new $moduleClass($this->app);
    }

    /*
     * This method enables the module
     * 
     * @return bool
     */
    public function enable($moduleId): bool
    {
        // Check if the module is already installed
        $ModuleRow = $this->app->getDatabase()->get($this->app->getStrings()->getTableModules(), '*', ['id' => $moduleId]);
        if (!$ModuleRow)
        {
            throw new ModuleNotFoundException();
        }

        // Install the module
        $this->getModule($ModuleRow['id'])->install();

        // Insert the module into the database
        $this->app->getDatabase()->update($this->app->getStrings()->getTableModules(), ['enabled' => 1], ['id' => $moduleId]);

        return true;
    }

    /*
     * This method disables the module
     * 
     * @return bool
     */
    public function disable($moduleId): bool
    {
        // Check if the module is already installed
        $ModuleRow = $this->app->getDatabase()->get($this->app->getStrings()->getTableModules(), '*', ['id' => $moduleId]);
        if (!$ModuleRow)
        {
            throw new ModuleNotFoundException();
        }

        // Disable the module
        $this->getModule($ModuleRow['id'])->uninstall();

        // Insert the module into the database
        $this->app->getDatabase()->update($this->app->getStrings()->getTableModules(), ['enabled' => 0], ['id' => $moduleId]);

        return true;
    }

    /*
     * This method gets if the module is enabled
     * 
     * @return bool
     */
    public function isEnabled($moduleId): bool
    {
        // Check if the module is already installed
        $ModuleRow = $this->app->getDatabase()->get($this->app->getStrings()->getTableModules(), '*', ['id' => $moduleId]);
        if (!$ModuleRow)
        {
            throw new ModuleNotFoundException();
        }

        return $ModuleRow['enabled'] ? true : false;
    }

    /*
     * This method gets the module base path
     * 
     * @return string
     */
    public function getModuleBasePath(string $name): string
    {
        return $this->modulePath . '/' . $name;
    }

     /*
     * This method gets the module url
     * 
     * @return string
     */
    public function getModuleBaseUrl(string $name): string
    {
        return $this->app->getEnv('PUBLIC_URL') . '/wsql-contents/modules/' . $name;
    }
}