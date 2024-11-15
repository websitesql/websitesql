<?php declare(strict_types=1);

namespace WebsiteSQL\Providers;

use WebsiteSQL\App;

class LoggingProvider
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
        
    }
}