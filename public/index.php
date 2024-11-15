<?php /* Website SQL - Index - V2.1.0 - (C) Copyright Alan Tiller 2024. */

// Import autoload
require '../vendor/autoload.php';

use WebsiteSQL\WebsiteSQL\App;

// Create a new instance of the App
$app = new App();

// Initialize the application
$app->init();

// Serve the application
$app->serve();