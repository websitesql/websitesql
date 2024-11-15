<?php declare(strict_types=1);

namespace WebsiteSQL;

use WebsiteSQL\Providers\StringsProvider;
use WebsiteSQL\Providers\RenderingProvider;
use WebsiteSQL\Providers\RoutingProvider;
use WebsiteSQL\Providers\ModulesProvider;
use WebsiteSQL\Providers\AuthenticationProvider;
use WebsiteSQL\Providers\UserProvider;
use WebsiteSQL\Providers\UtilitiesProvider;
use WebsiteSQL\Controllers\AdminController;
use WebsiteSQL\Controllers\SetupController;
use Dotenv\Dotenv;
use Medoo\Medoo;
use Exception;

class App
{
    /*
     * This string holds the basePath for the application
     * 
     * @var string
     */
    private $basePath;

    /*
     * This array holds the log queue
     * 
     * @var array
     */
    private $logQueue;

    /*
     * This boolean holds the status of the environment variables
     * 
     * @var bool
     */
    private $envStatus = false;

    /*
     * This boolean holds whether the application is in setup mode
     * 
     * @var bool
     */
    private $setupMode = false;

    /*
     * This boolean holds whether the application has initialised the controllers
     * 
     * @var bool
     */
    private $controllerStatus = false;

    /*
     * This object holds the Medoo database connection
     * 
     * @var Medoo
     */
    private $database;

    /*
     * This object holds the renderer
     * 
     * @var Engine
     */
    private $rendering;

    /*
     * This object holds the RoutingProvider class
     * 
     * @var RoutingProvider
     */
    private $routing;

    /*
     * This object holds the instance of the ModulesProvider class
     * 
     * @var ModulesProvider
     */
    private $modules;

    /*
     * This array holds the settings for the application
     * 
     * @var array
     */
    private $settings;

    /*
     * This object holds the instance of the SetupController class

    /*
     * Constructor
     */
    public function __construct()
    {
        $this->basePath = realpath(__DIR__ . '/..');
    }

    /*
     * This method initializes the application
     * 
     * @return void
     */
    public function init(): void
    {
        // Trace Log: init
        $this->log('admin', '[WebsiteSQL\App] [init] Function called');

        // Load environment variables
        $this->initEnv();

        // Connect to database
        $this->initDatabase();

        // Start session
        $this->initSession();

        // Set security features
        $this->initSecurity();

        // Initialize settings
        $this->initSettings();

        // Set default timezone
        $this->initTimezone();

        // Initialize router
        $this->initRouter();

        // Initialize renderer
        $this->initRenderer();

        // Initialize modules
        $this->initModules();

        // Initialize admin app
        $this->initControllers();
    }

    /*
     * This method loads the .env file
     * 
     * @return void
     */
    private function initEnv(): void
    {
        // Trace Log: initEnv
        $this->log('admin', '[WebsiteSQL\App] [initEnv] Function called');

        // Check if the environment variables file exists
        if (!file_exists($this->basePath . '/.env')) {
            $this->setupMode = true;
            return;
        }

        // Load environment variables
        try {
            $dotenv = Dotenv::createImmutable($this->basePath);
            $dotenv->load();

            // Mark environment variables as initialised
            $this->envStatus = true;
        } catch (Exception $e) {
            throw new Exception('Environment variables not loaded: ' . $e->getMessage());
        }
    }

    /*
     * This method initilises the database connection
     * 
     * @return void
     */
    private function initDatabase(): void
    {
        // Trace Log: initDatabase
        $this->log('admin', '[WebsiteSQL\App] [initDatabase] Function called');

        // Skip database connection if in setup mode
        if ($this->setupMode) {
            return;
        }

        // Check if environment variables are initialised
        if (!$this->envStatus) {
            throw new Exception('Environment variables not initialised');
        }

        // Check if database connection is set
        if ($this->database) {
            throw new Exception('Database connection already initialised');
        }

        // Connect to database
        try {
            $database = new Medoo([
                'type' => $_ENV['DB_DRIVER'],
                'host' => $_ENV['DB_HOST'],
                'database' => $_ENV['DB_NAME'],
                'username' => $_ENV['DB_USER'],
                'password' => $_ENV['DB_PASS'],
            ]);
        } catch (Exception $error) {
            throw new Exception('Database connection error: ' . $error->getMessage());
        }

        // Set database connection
        $this->database = $database;
    }

    /*
     * This method initilises the session
     * 
     * @return void
     */
    private function initSession(): void
    {
        // Trace Log: initSession
        $this->log('admin', '[WebsiteSQL\App] [initSession] Function called');

        // Check a session is started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /*
     * This method initilises application security features (https://www.getastra.com/blog/cms/php-security/php-security-guide/)
     * 
     * @return void
     */
    private function initSecurity(): void
    {
        // Trace Log: initSecurity
        $this->log('admin', '[WebsiteSQL\App] [initSecurity] Function called');

        // Check a session is started
        if (session_status() == PHP_SESSION_NONE) {
            throw new Exception('PHP Session not started');
        }

        // Set a CSFR token (https://www.phptutorial.net/php-tutorial/php-csrf/)
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        // Set security headers
        header_remove('X-Powered-By');
    }

    /*
     * This method initilises the application settings
     * 
     * @return void
     */
    private function initSettings(): void
    {
        // Trace Log: initSettings
        $this->log('admin', '[WebsiteSQL\App] [initSettings] Function called');

        // Skip settings if in setup mode
        if ($this->setupMode) {
            return;
        }

        // Check if database connection is set
        if (!$this->database) {
            throw new Exception('Database connection not set');
        }

        // Get settings from database
        $settings = $this->database->select($this->getStrings()->getTableSettings(), '*');

        // Set settings
        $this->settings = array_column($settings, 'Value', 'ID');
    }

    /*
     * This method initilises the router
     * 
     * @return void
     */
    private function initRouter(): void
    {
        // Trace Log: initRouter
        $this->log('admin', '[WebsiteSQL\App] [initRouter] Function called');

        // Check if router is set
        if ($this->routing) {
            throw new Exception('Router already initialised');
        }

        // Create new instance of the RouterProvider class
        $this->routing = new RoutingProvider($this);

        // Initialize the router
        $this->routing->init();
    }

    /*
     * This method initilises the rendering engine
     * 
     * @return void
     */
    private function initRenderer(): void
    {
        // Trace Log: initRenderer
        $this->log('admin', '[WebsiteSQL\App] [initRenderer] Function called');

        // Check if renderer is set
        if ($this->rendering) {
            throw new Exception('Renderer already initialised');
        }

        // Create new instance of the RenderingProvider class
        $this->rendering = new RenderingProvider($this);

        // Initialize the renderer
        $this->rendering->init();
    }

    /*
     * This method initilises the modules
     * 
     * @return void
     */
    private function initModules(): void
    {
        // Trace Log: initModules
        $this->log('admin', '[WebsiteSQL\App] [initModules] Function called');

        // Skip modules if in setup mode
        if ($this->setupMode) {
            return;
        }

        // Check if modules are set
        if ($this->modules) {
            throw new Exception('Modules already initialised');
        }

        // Create new instance of the ModulesProvider class
        $this->modules = new ModulesProvider($this);

        // Load modules
        $this->modules->init();
    }

    /*
     * This method sets the default timezone
     * 
     * @return void
     */
    private function initTimezone(): void
    {
        // Trace Log: initTimezone
        $this->log('admin', '[WebsiteSQL\App] [initTimezone] Function called');

        // Skip timezone if in setup mode
        if ($this->setupMode) {
            return;
        }

        // Check if settings are set
        if (!$this->settings) {
            throw new Exception('Settings not initialised');
        }

        // Set default timezone
        date_default_timezone_set($this->settings['DefaultTimezone']);
    }

    /*
     * This method initializes the admin app
     * 
     * @return void
     */
    private function initControllers(): void
    {
        // Trace Log: initAdmin
        $this->log('admin', '[WebsiteSQL\App] [initControllers] Function called');

        // Check if controllers are set
        if ($this->controllerStatus) {
            throw new Exception('Controllers already initialised');
        }

        // Check if setup mode is enabled
        if ($this->setupMode) {
            // Create new instance of the SetupController class
            $setup = new SetupController($this);

            // Initialize setup
            $setup->init();
        } else {
            // Create new instance of the AdminController class
            $admin = new AdminController($this);

            // Initialize admin
            $admin->init();
        }

        // Set controller status
        $this->controllerStatus = true;
    }

    /*
     * This method serves the application
     * 
     * @return void
     */
    public function serve(): void
    {
        // Trace Log: serve
        $this->log('admin', '[WebsiteSQL\App] [serve] Function called');

        // Check if router is set
        if (!$this->routing) {
            throw new Exception('Router not initialised');
        }

        // Serve the application
        $this->routing->serve();
    }

    /*
     * This method returns the basePath string
     * 
     * @return string
     */
    public function getBasePath(): string
    {
        // Return the basePath string
        return $this->basePath;
    }

    /*
     * This method returns the setup mode boolean
     * 
     * @return bool
     */
    public function getSetupMode(): bool
    {
        // Return the setup mode boolean
        return $this->setupMode;
    }

    /*
     * This method returns the database connection
     * 
     * @return Medoo
     */
    public function getDatabase(): Medoo
    {
        // Return database connection
        return $this->database;
    }

    /*
     * This method returns the renderer
     * 
     * @return Engine
     */
    public function getRenderer(): RenderingProvider
    {
        // Return renderer
        return $this->rendering;
    }

    /*
     * This method returns the modules provider
     * 
     * @return ModulesProvider
     */
    public function getModules(): ModulesProvider
    {
        // Return modules provider
        return $this->modules;
    }

    /*
     * This method gets all the settings and returns them as an array
     * 
     * @return array
     */
    public function getSettings(): array
    {
        // Return settings
        return $this->settings;
    }

    /*
     * This method gets a singular setting and returns it as a string
     * 
     * @return string
     */
    public function getSetting($setting): string
    {
        // Check if settings are set
        if (!$this->settings) {
            throw new Exception('Settings not initialised');
        }

        // Check if setting exists
        if (!isset($this->settings[$setting])) {
            throw new Exception('Setting not found');
        }

        // Return setting
        return $this->settings[$setting];
    }

    /*
     * This method gets the StringsProvider class and returns an instance of it
     * 
     * @return StringsProvider
     */
    public function getStrings(): StringsProvider
    {
        // Return an instance of the StringsProvider class
        return new StringsProvider();
    }

    /*
     * This method imports the AuthenticationProvider class and returns an instance of it
     * 
     * @return AuthenticationProvider
     */
    public function getAuth($realm = null): AuthenticationProvider
    {
        // Check if database connection is set
        if (!$this->database) {
            throw new Exception('Database connection not set');
        }

        // Return an instance of the AuthenticationProvider class
        return new AuthenticationProvider($realm, $this);
    }

    /*
     * This method imports the UserProvider class and returns an instance of it
     * 
     * @return UserProvider
     */
    public function getUser($realm = null): UserProvider
    {
        // Check if database connection is set
        if (!$this->database) {
            throw new Exception('Database connection not set');
        }

        // Return an instance of the UserProvider class
        return new UserProvider($realm, $this);
    }

    /*
     * This method imports the UtilitiesProvider class and returns an instance of it
     * 
     * @return UtilitiesProvider
     */
    public function getUtilities(): UtilitiesProvider
    {
        // Return an instance of the UtilitiesProvider class
        return new UtilitiesProvider();
    }

    /*
     * This method returns the router object
     * 
     * @return RouterProvider
     */
    public function getRouter(): RoutingProvider
    {
        // Return the router object
        return $this->routing;
    }

    /*
     * This method reloads the settings
     * 
     * @return void
     */
    public function reloadSettings(): void
    {
        // Check if database connection is set
        if (!$this->database) {
            throw new Exception('Database connection not set');
        }

        // Clear settings
        $this->settings = null;

        // Initialize settings
        $this->initSettings();
    }

    /*
     * This method handles logging for the application
     * 
     * @param string $channel Channel to log to
     * @param string $message Message to log
     * @param string $action Action to log
     * @param int $user User to log
     * @return void
     */
    public function log(string $channel, string $message, string $action = null, int $user = null): void
    {
        // Add the log to the queue
        $this->logQueue[] = [
            'channel' => $channel,
            'message' => $message,
            'action' => $action,
        ];

        // Check if database connection is set
        if (!$this->database) {
            error_log('[Website SQL Logger] [' . date('d/m/Y h:i:s') . ']: ' . 'Database connection not set yet, there are ' . count($this->logQueue) . ' items waiting to be saved.', 0);
            return;
        }

        // Check if settings is set
        if (!$this->settings) {
            error_log('[Website SQL Logger] [' . date('d/m/Y h:i:s') . ']: ' . 'Settings not initialised yet, there are ' . count($this->logQueue) . ' items waiting to be saved.', 0);
            return;
        }

        // Save the log to the database
        foreach ($this->logQueue as $log) {
            // Save the log to the database
            switch ($log['channel']) {
                case 'messages':
                    break;
                case 'website':
                    if ($this->getSetting('loggingWebsiteLogs') == 'true') {
                        $this->database->insert($this->getStrings()->getTableWebsiteLog(), [
                            'Value' => $log['message'],
                            'Version' => $this->getStrings()->getVersion(),
                        ]);
                    }
                    break;
                case 'admin':
                    if ($this->getSetting('loggingAdminLogs') == 'true') {
                        $this->database->insert($this->getStrings()->getTableAdminLog(), [
                            'Value' => $log['message'],
                            'Version' => $this->getStrings()->getVersion(),
                        ]);
                    }
                    break;
                case 'transaction':
                    $action = $log['action'] ?? 'Not specified';
                    $user = $user ?? 0;
                    $this->database->insert($this->getStrings()->getTableTransactionLog(), [
                        'UserID' => $user,
                        'Action' => $action,
                        'Content' => $log['message']
                    ]);
                    break;
                default:
                    break;
            }

            // Remove the log from the queue
            unset($this->logQueue[array_search($log, $this->logQueue)]);
        }
    }
}