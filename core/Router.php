<?php
/**
 * Simple Router Class
 * 
 * Routes URLs to appropriate controllers and actions
 */

class Router {
    
    /**
     * Route the request to appropriate controller
     * 
     * @param string $url
     */
    public function route($url) {
        // Clean and parse URL
        $url = $this->parseUrl($url);
        
        // Default route (dashboard)
        if (empty($url)) {
            $url = ['dashboard', 'index'];
        }
        
        // Extract module, controller, and action
        $module = isset($url[0]) ? $url[0] : 'dashboard';
        $action = isset($url[1]) ? $url[1] : 'index';
        $params = array_slice($url, 2);
        
        // Special cases: auth module and api routes
        if ($module === 'auth' || $module === 'api') {
            $this->loadController($module, $action, $params);
            return;
        }
        
        // Check for Maintenance Mode
        $this->checkMaintenance($module);

        // Load the controller
        $this->loadController($module, $action, $params);
    }

    /**
     * Check if system is in maintenance mode
     */
    private function checkMaintenance($module) {
        // Skip check for super_admin or auth/api routes
        if ((isset($_SESSION['role']) && $_SESSION['role'] === 'super_admin') || 
            $module === 'auth' || $module === 'api') {
            return;
        }

        try {
            $db = getDbConnection();
            $stmt = $db->prepare("SELECT setting_value FROM settings WHERE setting_key = 'maintenance_mode' LIMIT 1");
            $stmt->execute();
            $maintenance = $stmt->fetchColumn();

            if ($maintenance === '1') {
                require_once TEMPLATES_PATH . '/errors/maintenance.php';
                exit;
            }
        } catch (Exception $e) {
            // Silently fail and proceed if DB check fails
        }
    }
    
    /**
     * Load controller and call action
     * 
     * @param string $module
     * @param string $action
     * @param array $params
     */
    private function loadController($module, $action, $params = []) {
        // Build controller path
        $controllerFile = MODULES_PATH . '/' . $module . '/' . ucfirst($module) . 'Controller.php';
        
        // Check if controller exists
        if (!file_exists($controllerFile)) {
            $this->show404();
            return;
        }
        
        // Load controller
        require_once $controllerFile;
        
        // Build controller class name
        $controllerClass = ucfirst($module) . 'Controller';
        
        // Check if class exists
        if (!class_exists($controllerClass)) {
            $this->show404();
            return;
        }
        
        // Instantiate controller
        $controller = new $controllerClass();
        
        // Check if action exists
        if (!method_exists($controller, $action)) {
            $this->show404();
            return;
        }
        
        // Call action with parameters
        call_user_func_array([$controller, $action], $params);
    }
    
    /**
     * Parse URL into array
     * 
     * @param string $url
     * @return array
     */
    private function parseUrl($url) {
        if (!empty($url)) {
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = rtrim($url, '/');
            $url = explode('/', $url);
            return $url;
        }
        return [];
    }
    
    /**
     * Show 404 error page
     */
    private function show404() {
        http_response_code(404);
        require_once TEMPLATES_PATH . '/errors/404.php';
        exit;
    }
}
