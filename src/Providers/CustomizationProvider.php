<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Providers;

use Exception;
use WebsiteSQL\WebsiteSQL\App;

class CustomizationProvider
{
    /*
     * This object holds the Medoo database connection
     * 
     * @var Medoo
     */
    private App $app;

    /*
     * This array contains the main menu items
     * 
     * @var array
     */
    private array $mainMenuItems = [];

    /*
     * This array contains dashboard tiles
     * 
     * @var array
     */
    private array $dashboardTiles = [];

    /*
     * This array contains the dashboard action buttons
     * 
     * @var array
     */
    private array $dashboardActionButtons = [];

    /*
     * This array contains the login notices
     * 
     * @var array
     */
    private array $loginNotices = [];

    /*
     * This array contains the registration notices
     * 
     * @var array
     */
    private array $registrationNotices = [];

    /*
     * This array contains the dashboard notices
     * 
     * @var array
     */
    private array $dashboardNotices = [];

    /*
     * This array contains the css files
     * 
     * @var array
     */
    private array $cssFiles = [];

    /*
     * This array contains the js files
     * 
     * @var array
     */
    private array $jsFiles = [];

    /*
     * Constructor
     * 
     * @param string $realm
     * @param Medoo $database
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /*
     * This method initializes the customization provider
     * 
     * @return void
     */
    public function init(): void
    {
        // Initialize the dashboard tiles
        $this->initDashboardTiles();

        // Initialize the main menu items
        $this->initMainMenuItems();

        // Initialize the css files
        $this->initCssFiles();

        // Initialize the js files
        $this->initJsFiles();
    }

    /*
     * This method initializes the main menu items
     * 
     * @return void
     */
    public function initMainMenuItems(): void
    {
        // Dashboard
        $this->addMainMenuItem('Dashboard', 'app.dashboard', 'fa-solid fa-tachometer-alt', 5000);

        // Updates
        $this->addMainMenuItem('Updates', 'app.updates', 'fa-solid fa-cloud-download-alt', 7500);

        // Media
        $this->addMainMenuItem('Media', 'app.media', 'fa-solid fa-photo-film', 10000);

        // Divider
        $this->addMainMenuItemDivider(15000);

        // Settings
        $this->addMainMenuItem('Settings', 'app.admin.settings', 'fa-solid fa-cog', 20000);

        // Users
        $this->addMainMenuItem('Users', 'app.admin.users', 'fa-solid fa-users', 20100);

        // Access Control
        $this->addMainMenuItem('Access Control', 'app.admin.access-control', 'fa-solid fa-shield', 20200);

        // Modules
        $this->addMainMenuItem('Modules', 'app.admin.modules', 'fa-solid fa-plug', 20300);
    }

    /*
     * This method initializes the dashboard tiles
     * 
     * @return void
     */
    public function initDashboardTiles(): void
    {
        // Media tile
        $this->addDashboardTile('media-usage', function() {
            $mediaUsage = $this->app->getDatabase()->sum($this->app->getStrings()->getTableMedia(), 'size');

            return '<div class="flex justify-between">
                    <div>
                        <h1 class="font-bold mb-3 text-2xl">Media</h1>
                        <p class="font-normal leading-5">' . number_format(($mediaUsage != null ? $mediaUsage : 0) / 1048576, 1) . 'MB used</p>
                    </div>
                    <i class="fas fa-photo-film fa-fw text-3xl"></i>
                </div>';
        }, 'border-orange-500', 'row-span-1', 'col-span-1 sm:col-span-4', 20);

        // Settings tile
        $this->addDashboardTile('settings', function() {
            $activeUsers = $this->app->getDatabase()->count($this->app->getStrings()->getTableUsers(), ['approved' => 1, 'locked' => 0, 'email_verified' => 1]);
            
            return '<div class="flex justify-between">
                    <div>
                        <h1 class="font-bold mb-3 text-2xl">Settings</h1>
                        <p class="font-normal leading-5">Active users: ' . $activeUsers . '</p>
                    </div>
                    <i class="fas fa-gear fa-fw text-3xl"></i>
                </div>';
        }, 'border-green-500', 'row-span-1', 'col-span-1 sm:col-span-4', 30);
    }

    /*
     * This method initializes the css files
     * 
     * @return void
     */
    public function initCssFiles(): void
    {
        // Register the custom css file
        $this->addCssFile('application', $this->app->getEnv('PUBLIC_URL') . '/wsql-contents/assets/css/app.min.css');
    }

    /*
     * This method initializes the js files
     * 
     * @return void
     */
    public function initJsFiles(): void
    {
        // Register the custom js file
        $this->addJsFile('application', $this->app->getEnv('PUBLIC_URL') . '/wsql-contents/assets/js/app.min.js');
    }

    /*
     * This method returns the main menu items
     * 
     * @return array
     */
    public function getMainMenuItems(): array
    {
        // Sort the main menu items by position
        usort($this->mainMenuItems, function($a, $b) {
            return $a['position'] <=> $b['position'];
        });

        // Return the main menu items
        return $this->mainMenuItems;
    }

    /*
     * This method returns the dashboard tiles
     * 
     * @return array
     */
    public function getDashboardTiles(): array
    {
        // Sort the dashboard tiles by position
        usort($this->dashboardTiles, function($a, $b) {
            return $a['position'] <=> $b['position'];
        });

        // Return the dashboard tiles
        return $this->dashboardTiles;
    }

    /*
     * This method returns the dashboard action buttons
     * 
     * @return array
     */
    public function getDashboardActionButtons(): array
    {
        // Sort the dashboard action buttons by position
        usort($this->dashboardActionButtons, function($a, $b) {
            return $a['position'] <=> $b['position'];
        });

        return $this->dashboardActionButtons;
    }

    /*
     * This method returns the login notices
     * 
     * @return array
     */
    public function getLoginNotices(): array
    {
        return $this->loginNotices;
    }

    /*
     * This method returns the registration notices
     * 
     * @return array
     */
    public function getRegistrationNotices(): array
    {
        return $this->registrationNotices;
    }

    /*
     * This method returns the dashboard notices
     * 
     * @return array
     */
    public function getDashboardNotices(): array
    {
        return $this->dashboardNotices;
    }

    /*
     * This method returns the css files
     * 
     * @return array
     */
    public function getCssFiles(): array
    {
        return $this->cssFiles;
    }

    /*
     * This method returns the js files
     * 
     * @return array
     */
    public function getJsFiles(): array
    {
        return $this->jsFiles;
    }

    /*
     * This method gets a customisation value from the database or returns null if it does not exist
     * 
     * @return string
     */
    public function getValue($name, $user = null): string
    {
        // Check if the database is initialized
        if (!$this->app->getDatabase())
        {
            throw new \Exception('The database is not initialized');
        }
            
        // Get the light and dark mode logos from the database
        $value = $this->app->getDatabase()->select($this->app->getStrings()->getTableCustomizations(), [
            'name',
            'value'
        ], [
            'name' => $name,
            'user' => $user
        ]);

        // Check both logos exist
        if (!$value)
        {
            throw new \Exception('The value does not exist');
        }

        return $value['value'];
    }

    /*
     * This method gets a custom logo from the database
     * 
     * @return mixed
     */
    public function getLogo(): mixed
    {
        try {
            // Check if the database is initialized
            if (!$this->app->getDatabase())
            {
                throw new \Exception('The database is not initialized'); 
            }
            
            // Get the light and dark mode logos from the database
            $logo = $this->app->getDatabase()->select($this->app->getStrings()->getTableCustomizations(), [
                'name',
                'value'
            ], [
                'name' => ['interface.application_lm_logo', 'interface.application_dm_logo'],
                'user' => null
            ]);

            // Check both logos exist
            if (count($logo) !== 2)
            {
                throw new \Exception('The logos do not exist'); 
            }

            // Loop through the logos and get the URLs
            foreach ($logo as $row)
            {
                $value = json_decode($row['value'], true);

                switch ($value['type'])
                {
                    case 'media':
                        $file = $this->app->getMedia()->get((int) $value['id']);
                        $imgSrc = $this->app->getEnv('PUBLIC_URL') . $file['path'];
                        break;
                    case 'image':
                        $imgSrc = $value['url'];
                        break;
                }

                // Check if the image source is valid
                if (!filter_var($imgSrc, FILTER_VALIDATE_URL))
                {
                    throw new \Exception('The image source is not valid'); 
                }

                if ($row['name'] === 'interface.application_lm_logo')
                {
                    $response['light'] = $imgSrc;
                }
                else
                {
                    $response['dark'] = $imgSrc;
                }
            }

            return $response;
        } catch (\Exception $e) {
            return null;
        }
    }

    /*
     * This method gets a custom name from the database
     * 
     * @param bool $includeVersion (optional) Whether to include the version number, only applicable if the application name does not exist
     * @return string
     */
    public function getApplicationName($includeVersion = true): string
    {
        $response = null;

        try {
            // Check if the database is initialized
            if (!$this->app->getDatabase())
            {
                throw new Exception('The database is not initialized'); 
            }
            
            // Get the application name from the database
            $name = $this->app->getDatabase()->get($this->app->getStrings()->getTableCustomizations(), 'value', [
                'name' => 'interface.application_name',
                'user' => null
            ]);

            // Check the application name exists
            if (!$name)
            {
                throw new Exception('The application name does not exist'); 
            }

            // Push the application name to the response
            $response = $name;
        } catch (Exception $e) {
            $response = 'Website SQL' . ($includeVersion ? ' v' . $this->app->getStrings()->getVersion() : '');
        }

        return $response;
    }

    /*
     * This method adds a main menu item
     * 
     * @param string $title
     * @param string $url
     * @param string $icon
     * @param int $position
     * @param string $permission (optional)
     * @return void
     */
    public function addMainMenuItem(string $title, string $route, string $icon, int $position, string $permission = ''): void
    {
        $this->mainMenuItems[] = [
            'title' => $title,
            'route' => $route,
            'icon' => $icon,
            'position' => $position,
            'permission' => $permission,
            'is_divider' => false
        ];
    }

    /*
     * This method adds a main menu item divider
     * 
     * @param int $position
     * @param string $title (optional)
     * @return void
     */
    public function addMainMenuItemDivider(int $position): void
    {
        $this->mainMenuItems[] = [
            'position' => $position,
            'is_divider' => true
        ];
    }

    /*
     * This method adds a dashboard tile
     * 
     * @param string $id
     * @param callable $content
     * @param string $borderColor This should be presented as a tailwindcss border color or as raw CSS
     * @param string $height This should be presented as either the row span in tailwindcss or as raw CSS (Make sure to include sizing for mobile)
     * @param string $width This should be presented as either the column span in tailwindcss or as raw CSS (Make sure to include sizing for mobile)
     * @param int $position This should be presented as a number
     * @param array $permission (optional)
     * @return void
     */
    public function addDashboardTile(string $id, callable $content, string $borderColor, string $height, string $width, int $position, array $permission = []): void
    {
        // Create null variables
        $borderClass = null;
        $borderStyle = null;
        $heightClass = null;
        $heightStyle = null;
        $widthClass = null;
        $widthStyle = null;

        // Check the ID does not already exist
        foreach ($this->dashboardTiles as $tile)
        {
            if ($tile['id'] === $id)
            {
                throw new \Exception('The ID already exists');
            }
        }

        // Check if the content is callable
        if (!is_callable($content))
        {
            throw new \Exception('The content parameter must be a callable function');
        }

        // Check if the border color is a tailwindcss class
        if (strpos($borderColor, 'border-') !== false)
        {
            $borderClass = $borderColor;
        }
        else
        {
            $borderStyle = $borderColor;
        }
        
        // Check if the height is a tailwindcss class
        if (strpos($height, 'row-span-') !== false)
        {
            $heightClass = $height;
        }
        else
        {
            $heightStyle = $height;
        }

        // Check if the width is a tailwindcss class
        if (strpos($width, 'col-span-') !== false)
        {
            $widthClass = $width;
        }
        else
        {
            $widthStyle = $width;
        }

        // Add the dashboard tile
        $this->dashboardTiles[] = [
            'id' => $id,
            'content' => $content,
            'borderColor' => [
                'class' => $borderClass,
                'style' => $borderStyle
            ],
            'height' => [
                'class' => $heightClass,
                'style' => $heightStyle
            ],
            'width' => [
                'class' => $widthClass,
                'style' => $widthStyle
            ],
            'position' => $position,
            'permission' => $permission
        ];
    }
    
    /*
     * This method adds a dashboard action button
     * 
     * @param string $id
     * @param string $route
     * @param string $icon
     * @param string $title
     * @param int $position
     * @param array $permission (optional)
     * @return void
     */
    public function addDashboardActionButton(string $id, string $route, string $icon, string $title, int $position, array $permission = []): void
    {
        // Check the ID does not already exist
        foreach ($this->dashboardActionButtons as $button)
        {
            if ($button['id'] === $id)
            {
                throw new \Exception('The ID already exists');
            }
        }

        // Add the dashboard action button
        $this->dashboardActionButtons[] = [
            'id' => $id,
            'route' => $route,
            'icon' => $icon,
            'title' => $title,
            'position' => $position,
            'permission' => $permission
        ];
    }

    /*
     * This method adds a css file
     * 
     * @param string $name
     * @param string $path
     * @return void
     */
    public function addCssFile(string $name, string $path): void
    {
        $this->cssFiles[] = [
            'name' => $name,
            'path' => $path
        ];
    }

    /*
     * This method adds a js file
     * 
     * @param string $name
     * @param string $path
     * @return void
     */
    public function addJsFile(string $name, string $path): void
    {
        $this->jsFiles[] = [
            'name' => $name,
            'path' => $path
        ];
    }

    /*
     * This method adds a login notice
     * 
     * @param string $title
     * @param string $content
     * @param string $type
     * @param int $position (optional)
     * @param array $permission (optional)
     * @return void
     */
    public function addLoginNotice(string $title, string $content, string $type, int $position = 0, array $permission = []): void
    {
        $this->loginNotices[] = [
            'title' => $title,
            'content' => $content,
            'type' => $type,
            'position' => $position,
            'permission' => $permission
        ];
    }

    /*
     * This method adds a registration notice
     * 
     * @param string $title
     * @param string $content
     * @param string $type
     * @param int $position (optional)
     * @param array $permission (optional)
     * @return void
     */
    public function addRegistrationNotice(string $title, string $content, string $type, int $position = 0, array $permission = []): void
    {
        $this->registrationNotices[] = [
            'title' => $title,
            'content' => $content,
            'type' => $type,
            'position' => $position,
            'permission' => $permission
        ];
    }

    /*
     * This method adds a dashboard notice
     * 
     * @param string $title
     * @param string $content
     * @param string $type
     * @param int $position (optional)
     * @param array $permission (optional)
     * @return void
     */
    public function addDashboardNotice(string $title, string $content, string $type, int $position = 0, array $permission = []): void
    {
        $this->dashboardNotices[] = [
            'title' => $title,
            'content' => $content,
            'type' => $type,
            'position' => $position,
            'permission' => $permission
        ];
    }

    /*
     * This method gets a customizations value from the database
     * 
     * @param string $name
     * @param string $user
     * @return mixed
     */
    public function getCustomization(string $name, string $user = null)
    {
        // Get the customization from the database
        $customization = $this->app->getDatabase()->get($this->app->getStrings()->getTableCustomizations(), 'value', [
            'name' => $name,
            'user' => $user
        ]);

        // Check if the customization exists
        if (!$customization)
        {
            throw new \Exception('The customization does not exist'); 
        }

        return $customization['value'];
    }

    /*
     * This method creates a customization value in the database
     * 
     * @param string $name
     * @param string $value
     * @param string $user
     * @return void
     */
    public function createCustomization(string $name, string $value, string $user = null): void
    {
        // Check if the customization already exists
        $existingCustomization = $this->app->getDatabase()->get($this->app->getStrings()->getTableCustomizations(), 'id', [
            'name' => $name,
            'user' => $user
        ]);

        // Check if the customization already exists
        if ($existingCustomization)
        {
            throw new \Exception('The customization already exists');
        }

        // Insert the customization into the database
        $this->app->getDatabase()->insert($this->app->getStrings()->getTableCustomizations(), [
            'name' => $name,
            'value' => $value,
            'user' => $user
        ]);
    }

    /*
     * This method updates a customization value in the database
     * 
     * @param string $name
     * @param string $value
     * @param string $user
     * @return void
     */
    public function updateCustomization(string $name, string $value, string $user = null): void
    {
        // Check if the customization already exists
        $existingCustomization = $this->app->getDatabase()->get($this->app->getStrings()->getTableCustomizations(), 'id', [
            'name' => $name,
            'user' => $user
        ]);

        // Check if the customization already exists
        if (!$existingCustomization)
        {
            throw new \Exception('The customization does not exist');
        }

        // Update the customization in the database
        $this->app->getDatabase()->update($this->app->getStrings()->getTableCustomizations(), [
            'value' => $value
        ], [
            'name' => $name,
            'user' => $user
        ]);
    }
}