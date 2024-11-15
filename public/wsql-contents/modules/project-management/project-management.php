<?php /* Website SQL - Project Management Module - V1.0.0 - (C) Copyright Alan Tiller 2024. */

class ProjectManagement
{
    /*
     * This string is used to store the module name
     * 
     * @var string
     */
    private $moduleName = 'Project Management';

    /*
     * This string is used to store the module description
     * 
     * @var string
     */
    private $moduleDescription = 'This module provides project management functionality for the website.';

    /*
     * This string is used to store the module version
     * 
     * @var string
     */
    private $moduleVersion = '0.1.0';

    /*
     * This string is used to store the module author
     * 
     * @var string
     */
    private $moduleAuthor = 'Website SQL';

    /*
     * This object is used to store the environment instance
     * 
     * @var object
     */
    private $environment;

    /*
     * Constructor
     */
    public function __construct($environment)
    {
        $this->environment = $environment;
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
        // Initialise the renderer
        $this->initRenderer();

        // Register custom css files
        $websiteRoot = rtrim($this->environment->getSetting('WebsiteRoot'), '/');
        $this->environment->registerCustomAction('project-management', 'css_file', $websiteRoot . '/wsql-contents/modules/project-management/css/style.css');

        // Register custom menu item for project management
        $this->environment->registerCustomAction('project-management', 'menu_item', null, 'Project Management', '?page=custom&pid=project-management-home');

        // Register custom page for project management
        $this->environment->registerCustomAction('project-management', 'custom_page', [$this, 'homepage'], 'Project Management', 'project-management-home');
    }

    /*
     * This method installs the project management module
     * 
     * @return void
     */
    public function install()
    {
        // Create the projects table
        $this->environment->getDatabase()->create('pm_projects', [
            'id' => ['INT', 'AUTO_INCREMENT', 'PRIMARY KEY'],
            'name' => ['VARCHAR(255)', 'NOT NULL'],
            'description' => ['TEXT', 'NOT NULL'],
            'created_at' => ['DATETIME', 'NOT NULL'],
            'updated_at' => ['DATETIME', 'NOT NULL']
        ]);

        // Create the tasks table
        $this->environment->getDatabase()->create('pm_tasks', [
            'id' => ['INT', 'AUTO_INCREMENT', 'PRIMARY KEY'],
            'project_id' => ['INT', 'NOT NULL'],
            'name' => ['VARCHAR(255)', 'NOT NULL'],
            'description' => ['TEXT', 'NOT NULL'],
            'due_date' => ['DATETIME', 'NOT NULL'],
            'created_at' => ['DATETIME', 'NOT NULL'],
            'updated_at' => ['DATETIME', 'NOT NULL']
        ]);

        // Create the milestones table
        $this->environment->getDatabase()->create('pm_milestones', [
            'id' => ['INT', 'AUTO_INCREMENT', 'PRIMARY KEY'],
            'project_id' => ['INT', 'NOT NULL'],
            'name' => ['VARCHAR(255)', 'NOT NULL'],
            'description' => ['TEXT', 'NOT NULL'],
            'due_date' => ['DATETIME', 'NOT NULL'],
            'created_at' => ['DATETIME', 'NOT NULL'],
            'updated_at' => ['DATETIME', 'NOT NULL']
        ]);

        // Create the project timetracking table
        $this->environment->getDatabase()->create('pm_timetracking', [
            'id' => ['INT', 'AUTO_INCREMENT', 'PRIMARY KEY'],
            'project_id' => ['INT', 'NOT NULL'],
            'task_id' => ['INT', 'NOT NULL'],
            'milestone_id' => ['INT', 'NOT NULL'],
            'comment' => ['TEXT', 'NOT NULL'],
            'start_time' => ['DATETIME', 'NOT NULL'],
            'end_time' => ['DATETIME', 'NOT NULL']
        ]);
    }

    /*
     * This method uninstalls the project management module
     * 
     * @return void
     */
    public function uninstall()
    {
        // Drop the projects table
        $this->environment->getDatabase()->drop('pm_projects');

        // Drop the tasks table
        $this->environment->getDatabase()->drop('pm_tasks');

        // Drop the milestones table
        $this->environment->getDatabase()->drop('pm_milestones');

        // Drop the project timetracking table
        $this->environment->getDatabase()->drop('pm_timetracking');
    }

    /*
     * This method updates the project management module
     * 
     * @return void
     */
    public function update()
    {

    }

    /*
     * This method sets up the renderer for the project management module
     * 
     * @return void
     */
    private function initRenderer()
    {
        $this->environment->getRenderer()->addFolder('project-management', $this->environment->getModulePath() . '/templates');
    }

    /*
     * This method returns the project management homepage
     * 
     * @return void
     */
    public function homepage() {
        // Handle post
        if (isset($_POST['submit'])) {
            // Handle form submission
            $error = 'Form submitted';
        }

        return $this->environment->getRenderer()->render('project-management::homepage', [
            'error' => $error ?? null
        ]);
    }

}