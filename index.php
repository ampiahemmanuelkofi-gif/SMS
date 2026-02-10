<?php
/**
 * School Management System - Front Controller
 * 
 * All requests are routed through this file
 */

// Start session
session_start();

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define paths
define('ROOT_PATH', __DIR__);
define('CORE_PATH', ROOT_PATH . '/core');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('MODULES_PATH', ROOT_PATH . '/modules');
define('TEMPLATES_PATH', ROOT_PATH . '/templates');
define('ASSETS_PATH', ROOT_PATH . '/assets');
define('UPLOADS_PATH', ROOT_PATH . '/uploads');

// Define URL base
define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/sch/');
define('ASSETS_URL', BASE_URL . 'assets/');
define('UPLOADS_URL', BASE_URL . 'uploads/');

// Load configuration
require_once CONFIG_PATH . '/constants.php';
require_once CONFIG_PATH . '/database.php';

// Load core classes
require_once CORE_PATH . '/Router.php';
require_once CORE_PATH . '/Controller.php';
require_once CORE_PATH . '/Model.php';
require_once CORE_PATH . '/Auth.php';
require_once CORE_PATH . '/Security.php';
require_once CORE_PATH . '/Validator.php';
require_once CORE_PATH . '/FileUpload.php';
require_once CORE_PATH . '/System.php';

// Initialize core components
System::init();

// Initialize router
$router = new Router();

// Get URL from query string
$url = isset($_GET['url']) ? $_GET['url'] : '';

// Route the request
$router->route($url);
