<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Providers;

use WebsiteSQL\WebsiteSQL\App;

class UpdatesProvider
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
     * This method gets the latest version of the application from the GitHub repository
     * 
     * @return string
     */
    public function getLatestVersion(): string
    {
        // Get the latest version from the GitHub repository
        $latestVersion = file_get_contents('https://api.github.com/repos/websitesql/websitesql/releases/latest');

        // Return the latest version
        return $latestVersion;
    }

    /*
     * This method checks if the application is up to date
     * 
     * @return bool
     */
    public function isUpToDate(): bool
    {
        // Get the latest version
        $latestVersion = $this->getLatestVersion();

        // Get the current version
        $currentVersion = $this->app->getStrings()->getVersion();

        // Check if the application is up to date
        if (version_compare($currentVersion, $latestVersion, '<')) {
            return false;
        }

        return true;
    }

}