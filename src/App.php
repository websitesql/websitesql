<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL;

use WebsiteSQL\WebsiteSQL\Providers\StringsProvider;
use WebsiteSQL\WebsiteSQL\Providers\RenderingProvider;
use WebsiteSQL\WebsiteSQL\Providers\RoutingProvider;
use WebsiteSQL\WebsiteSQL\Providers\ModulesProvider;
use WebsiteSQL\WebsiteSQL\Providers\AuthenticationProvider;
use WebsiteSQL\WebsiteSQL\Providers\UserProvider;
use WebsiteSQL\WebsiteSQL\Providers\UtilitiesProvider;
use WebsiteSQL\WebsiteSQL\Providers\CustomizationProvider;
use WebsiteSQL\WebsiteSQL\Providers\PermissionsProvider;
use Dotenv\Dotenv;
use Medoo\Medoo;
use Exception;
use WebsiteSQL\WebsiteSQL\Providers\MailProvider;
use WebsiteSQL\WebsiteSQL\Providers\MediaProvider;

class App
{
    /*
     * This string holds the basePath for the application
     * 
     * @var string
     */
    private $basePath;

    /*
     * This array holds the environment variables
     * 
     * @var array
     */
    protected $env = [];

    /*
     * This boolean holds whether the application is in setup mode
     * 
     * @var bool
     */
    private $setup = false;

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
     * This object holds the instance of the CustomizationProvider class
     * 
     * @var CustomizationProvider
     */
    private $customization;

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
        // Load environment variables
        $this->initEnv();

        // Connect to database
        $this->initDatabase();

        // Set security features
        $this->initSecurity();

        // Set default timezone
        $this->initTimezone();

        // Initialize customisations
        $this->initCustomisation();

        // Initialize router
        $this->initRouter();

        // Initialize renderer
        $this->initRenderer();

        // Initialize modules
        $this->initModules();
    }

    /*
     * This method loads the .env file
     * 
     * @return void
     */
    protected function initEnv(): void
    {
        // Check if the environment variables file exists
        if (!file_exists($this->basePath . '/.env')) {
            // Set setup mode
            $this->setup = true;

            // Set environment variables
            $this->env = [
                'PUBLIC' => [
                    'DEBUG' => false,
                    'PUBLIC_URL' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http' . '://' . $_SERVER['HTTP_HOST'],
                ],
                'PRIVATE' => []
            ];

            // Return
            return;
        }

        // Load environment variables
        try {
            // Load environment variables into an array
            $dotenv = Dotenv::createArrayBacked($this->basePath);
            $environment = $dotenv->load();

            // Generate the Public URL
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
            $port = ($environment['PORT'] == 80 || $environment['PORT'] == 443) ? '' : ':' . $environment['PORT'];
            $publicUrl = $protocol . '://' . $environment['HOST'] . $port;

            // Set environment variables
            $this->env = [
                'PUBLIC' => [
                    'HOST' => $environment['HOST'],
                    'PORT' => $environment['PORT'],
                    'DEBUG' => $environment['DEBUG'],
                    'TIMEZONE' => $environment['TIMEZONE'],
                    'SERVE_APP' => $environment['SERVE_APP'],
                    'SERVE_LOCATION' => $environment['SERVE_LOCATION'],
                    'PUBLIC_URL' => $publicUrl
                ],
                'PRIVATE' => [
                    'DB_DRIVER' => $environment['DB_DRIVER'],
                    'DB_HOST' => $environment['DB_HOST'],
                    'DB_NAME' => $environment['DB_NAME'],
                    'DB_USER' => $environment['DB_USER'],
                    'DB_PASS' => $environment['DB_PASS']
                ],
            ];
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
        // Skip database connection if in setup mode
        if ($this->setup) {
            return;
        }

        // Check if environment variables are initialised
        if (!$this->env) {
            throw new Exception('Environment variables not initialised');
        }

        // Check if database connection is set
        if ($this->database) {
            throw new Exception('Database connection already initialised');
        }

        // Connect to database
        try {
            $database = new Medoo([
                'type' => $this->env['PRIVATE']['DB_DRIVER'],
                'host' => $this->env['PRIVATE']['DB_HOST'],
                'database' =>  $this->env['PRIVATE']['DB_NAME'],
                'username' =>  $this->env['PRIVATE']['DB_USER'],
                'password' =>  $this->env['PRIVATE']['DB_PASS']
            ]);
        } catch (Exception $error) {
            throw new Exception('Database connection error: ' . $error->getMessage());
        }

        // Set database connection
        $this->database = $database;
    }

    /*
     * This method initilises application security features (https://www.getastra.com/blog/cms/php-security/php-security-guide/)
     * 
     * @return void
     */
    private function initSecurity(): void
    {
        // Check a session is started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Set a CSFR token (https://www.phptutorial.net/php-tutorial/php-csrf/)
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        // Set security headers
        header_remove('X-Powered-By');
    }

    /*
     * This method sets the default timezone
     * 
     * @return void
     */
    private function initTimezone(): void
    {
        // Skip timezone if in setup mode
        if ($this->setup) {
            return;
        }

        // Check if environment variables are set
        if (!$this->env) {
            throw new Exception('Environment variables not initialised');
        }

        // Set default timezone
        date_default_timezone_set($this->env['PUBLIC']['TIMEZONE']);
    }

    /*
     * This method initializes the application customisation
     * 
     * @return void
     */
    private function initCustomisation(): void
    {
        // Create new instance of the CustomizationProvider class
        $this->customization = new CustomizationProvider($this);

        // Initialize customisation
        $this->customization->init();
    }

    /*
     * This method initilises the router
     * 
     * @return void
     */
    private function initRouter(): void
    {
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
        // Skip modules if in setup mode
        if ($this->setup) {
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
     * This method serves the application
     * 
     * @return void
     */
    public function serve(): void
    {
        // Set debug mode
        if ($this->env['PUBLIC']['DEBUG']) {
            // Show errors
            error_reporting(E_ALL);
            ini_set('display_errors', '1');

            // Set error handler
            // $whoops = new \Whoops\Run;
            // $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
            // $whoops->register();
        } else {
            // Hide errors
            error_reporting(0);
            ini_set('display_errors', '0');
        }

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
    public function getsetup(): bool
    {
        // Return the setup mode boolean
        return $this->setup;
    }

    /*
     * This method returns the database connection
     * 
     * @return Medoo
     */
    public function getDatabase(): Medoo
    {
        // Check if database connection is set
        if (!$this->database) {
            throw new Exception('Database connection not set');
        }

        // Return database connection
        return $this->database;
    }
    
    /*
     * This method gets the environment variable and returns it as a string
     * 
     * @param string $env This is the environment variable you want to get
     * @return string|bool
     */
    public function getEnv($env): string|bool
    {
        // Check if environment variables are set
        if (!$this->env) {
            throw new Exception('Environment variables not initialised');
        }

        // Check if environment variable exists
        if (!isset($this->env['PUBLIC'][$env])) {
            throw new Exception('Environment variable ' . $env . ' does not exist');
        }

        // Return environment variable
        return $this->env['PUBLIC'][$env];
    }

    /*
     * This method imports the AuthenticationProvider class and returns an instance of it
     * 
     * @return AuthenticationProvider
     */
    public function getAuth(): AuthenticationProvider
    {
        // Check if database connection is set
        if (!$this->database) {
            throw new Exception('Database connection not set');
        }

        // Return an instance of the AuthenticationProvider class
        return new AuthenticationProvider($this);
    }

    /*
     * This method returns the customization provider
     * 
     * @return CustomizationProvider
     */
    public function getCustomization(): CustomizationProvider
    {
        // Return the customisation provider
        return $this->customization;
    }

    // MigrationsProvider

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
     * This method imports the PermissionsProvider class and returns an instance of it
     * 
     * @return PermissionsProvider
     */
    public function getPermissions(): PermissionsProvider
    {
        // Check if database connection is set
        if (!$this->database) {
            throw new Exception('Database connection not set');
        }

        // Return an instance of the PermissionsProvider class
        return new PermissionsProvider($this);
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
     * This method imports the UserProvider class and returns an instance of it
     * 
     * @return UserProvider
     */
    public function getUser(): UserProvider
    {
        // Check if database connection is set
        if (!$this->database) {
            throw new Exception('Database connection not set');
        }

        // Return an instance of the UserProvider class
        return new UserProvider($this);
    }

    /*
     * This method imports the MediaProvider class and returns an instance of it
     * 
     * @return UserProvider
     */
    public function getMedia(): MediaProvider
    {
        // Check if database connection is set
        if (!$this->database) {
            throw new Exception('Database connection not set');
        }

        // Return an instance of the UserProvider class
        return new MediaProvider($this);
    }

    /*
     * This method imports the MailProvider class and returns an instance of it
     * 
     * @return MailProvider
     */
    public function getMail(): MailProvider
    {
        // Check if database connection is set
        if (!$this->database) {
            throw new Exception('Database connection not set');
        }

        // Return an instance of the MailProvider class
        return new MailProvider($this);
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
}