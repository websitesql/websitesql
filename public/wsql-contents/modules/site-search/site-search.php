<?php /* Website SQL - Site Search Module - V0.0.1 - (C) Copyright Alan Tiller 2024. */

class SiteSearch
{
    /*
     * This string is used to store the module name
     * 
     * @var string
     */
    private $moduleName = 'Site Search';

    /*
     * This string is used to store the module description
     * 
     * @var string
     */
    private $moduleDescription = 'Make site searching easy with the Site Search module, easy searching and information.';

    /*
     * This string is used to store the module version
     * 
     * @var string
     */
    private $moduleVersion = '0.0.3';

    /*
     * This string is used to store the module author
     * 
     * @var string
     */
    private $moduleAuthor = 'Website SQL';

    /*
     * This object is used to store the application instance
     * 
     * @var object
     */
    private $app;

    /*
     * This string is used to store the module path
     * 
     * @var string
     */
    private $modulePath;

    /*
     * Constructor
     */
    public function __construct($appInstance)
    {
        $this->app = $appInstance;
        $this->modulePath = $this->app->getModulePath() . '/site-search';
    }

    /*
     * This method returns the project management module meta data
     * 
     * @return array
     */
    public function getMetaData()
    {
        return array(
            'name' => $this->moduleName,
            'description' => $this->moduleDescription,
            'version' => $this->moduleVersion,
            'author' => $this->moduleAuthor
        );
    }

    /*
     * This method initialises the project management module if it is enabled
     * 
     * @return void
     */
    public function init()
    {

    }

    /*
     * This method installs the project management module
     * 
     * @return void
     */
    public function install()
    {
    }

    /*
     * This method uninstalls the project management module
     * 
     * @return void
     */
    public function uninstall()
    {
    }

    /*
     * This method updates the project management module
     * 
     * @return void
     */
    public function update()
    {

    }
}