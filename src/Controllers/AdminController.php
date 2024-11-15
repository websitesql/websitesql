<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Controllers;

use WebsiteSQL\WebsiteSQL\App;
use WebsiteSQL\WebsiteSQL\Controllers\Auth\LoginController;
use WebsiteSQL\WebsiteSQL\Controllers\Auth\RegisterController;
use Exception;

class AdminController
{
    /*
     * This object holds the Medoo database connection
     * 
     * @var Medoo
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
     * This method initializes the router
     * 
     * @return void
     */
    public function init(): void
    {
        // Register the login route
        $this->app->getRouter()->registerAdminRoute(['GET', 'POST'], '/login', [LoginController::class, 'handle'], 'login', [
            'auth' => false
        ]);

        // Register the register route
        $this->app->getRouter()->registerAdminRoute(['GET', 'POST'], '/register', [RegisterController::class, 'handle'], 'register', [
            'auth' => false
        ]);

        // Register the dashboard route
        $this->app->getRouter()->registerAdminRoute(['GET'], '/', [$this, 'dashboard'], 'dashboard', [
            'auth' => true,
            'permissions' => ['websitesql.admin.access']
        ]);

        // Register the modules route
        $this->app->getRouter()->registerAdminRoute(['GET', 'POST'], '/modules', [$this, 'modules'], 'modules', [
            'auth' => true,
            'permissions' => ['websitesql.admin.access']
        ]);

        // Register the logout route
        $this->app->getRouter()->registerAdminRoute(['GET'], '/logout', [$this, 'logout'], 'logout');
    }

    /*
     * This method renders the dashboard page
     * 
     * @return string
     */
    public function dashboard(): string
    {
        // Calculate the content count for the dashboard
        $contentCountPublished = $this->app->getDatabase()->count($this->app->getStrings()->getTableContent(), ['PostStatus' => 'published']);
        $contentCountDraft = $this->app->getDatabase()->count($this->app->getStrings()->getTableContent(), ['PostStatus' => 'draft']);
        $mediaUsage = $this->app->getDatabase()->sum($this->app->getStrings()->getTableUploads(), 'FileSize');
        $activeUsers = $this->app->getDatabase()->count($this->app->getStrings()->getTableUsers(), ['approved' => 1, 'locked' => 0, 'email_verified' => 1]);
        $licenceKey = explode('/', (string)openssl_decrypt(base64_decode($this->app->getSetting('ApplicationLicenceKey')), 'AES-256-CBC', '6db407e07779b58df95aadae1a1a4d24a685c5101c7dcb7c998a87356f0b554f', 0, '2b09f666e23fc970'));

        // Render the dashboard
        return $this->app->getRenderer()->render('application::beta', [
            'title' => 'Dashboard',
            'contentCountPublished' => $contentCountPublished,
            'contentCountDraft' => $contentCountDraft,
            'mediaUsage' => ($mediaUsage != null ? $mediaUsage : 0),
            'activeUsers' => $activeUsers,
            'licenceKey' => $licenceKey
        ]);	
    }

    /*
     * This method renders the modules page
     * 
     * @return string
     */
    public function modules(): string
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
        return $this->app->getRenderer()->render('application::modules', [
            'title' => 'Modules',
            'modules' => $modules,
            'error' => $error ?? null
        ]);	
    }

    /*
     * This method logs the user out
     * 
     * @return void
     */
    public function logout(): void
    {

    }
}