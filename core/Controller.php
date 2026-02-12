<?php
/**
 * Base Controller Class
 * 
 * All controllers extend this class
 */

class Controller {
    
    /**
     * Load a view file
     * 
     * @param string $view Path to view file (relative to templates/)
     * @param array $data Data to pass to view
     * @param bool $useLayout Whether to use main layout
     */
    protected function view($view, $data = [], $useLayout = true) {
        // Extract data to variables
        extract($data);
        
        // Start output buffering
        ob_start();
        
        // Load view file
        $viewFile = TEMPLATES_PATH . '/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            die("View not found: " . $view);
        }
        
        // Get view content
        $content = ob_get_clean();
        
        // Use layout if requested
        if ($useLayout) {
            $layoutName = isset($data['layout']) ? $data['layout'] : 'layout';
            $layoutFile = TEMPLATES_PATH . '/' . $layoutName . '.php';
            
            // Check if specific layout directory exists (e.g., iteacher/layout_mobile)
            if (isset($data['layout_path'])) {
                 $layoutFile = TEMPLATES_PATH . '/' . $data['layout_path'] . '.php';
            }

            if (file_exists($layoutFile)) {
                require $layoutFile;
            } else {
                require TEMPLATES_PATH . '/layout.php';
            }
        } else {
            echo $content;
        }
    }
    
    /**
     * Load a model
     * 
     * @param string $model Model name
     * @return object Model instance
     */
    protected function model($model) {
        // Build model path
        $modelFile = MODULES_PATH . '/' . strtolower($model) . '/' . ucfirst($model) . 'Model.php';
        
        if (file_exists($modelFile)) {
            require_once $modelFile;
            $modelClass = ucfirst($model) . 'Model';
            return new $modelClass();
        }
        
        die("Model not found: " . $model);
    }
    
    /**
     * Redirect to another URL
     * 
     * @param string $url URL to redirect to (relative to BASE_URL)
     */
    protected function redirect($url) {
        header('Location: ' . BASE_URL . $url);
        exit;
    }
    
    /**
     * Return JSON response
     * 
     * @param array $data Data to return
     * @param int $statusCode HTTP status code
     */
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    /**
     * Set flash message
     * 
     * @param string $type Message type (success, error, warning, info)
     * @param string $message Message text
     */
    protected function setFlash($type, $message) {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message
        ];
    }
    
    /**
     * Get and clear flash message
     * 
     * @return array|null Flash message or null
     */
    protected function getFlash() {
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        return null;
    }
    
    /**
     * Check if user has required role
     * 
     * @param string|array $roles Required role(s)
     * @return bool
     */
    protected function requireRole($roles) {
        if (!is_array($roles)) {
            $roles = [$roles];
        }
        
        if (!Auth::hasRole($roles)) {
            $this->setFlash('error', 'You do not have permission to access this page.');
            $this->redirect('dashboard');
            return false;
        }
        
        return true;
    }

    /**
     * Require user to be logged in
     * 
     * @return bool
     */
    protected function requireLogin() {
        if (!Auth::isLoggedIn()) {
            $this->redirect('auth/login');
            return false;
        }
        return true;
    }

    /**
     * Check if user has permission for a module
     * 
     * @param string $moduleKey
     * @return bool
     */
    protected function requirePermission($moduleKey) {
        if (!Auth::canAccess($moduleKey)) {
            $this->setFlash('error', 'You do not have permission to access the ' . ucfirst($moduleKey) . ' module.');
            $this->redirect('dashboard');
            return false;
        }
        return true;
    }
    
    /**
     * Get POST data
     * 
     * @param string $key Key to get
     * @param mixed $default Default value
     * @return mixed
     */
    protected function post($key = null, $default = null) {
        if ($key === null) {
            return $_POST;
        }
        return isset($_POST[$key]) ? $_POST[$key] : $default;
    }
    
    /**
     * Get GET data
     * 
     * @param string $key Key to get
     * @param mixed $default Default value
     * @return mixed
     */
    protected function get($key = null, $default = null) {
        if ($key === null) {
            return $_GET;
        }
        return isset($_GET[$key]) ? $_GET[$key] : $default;
    }
    
    /**
     * Check if request is POST
     * 
     * @return bool
     */
    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
    
    /**
     * Check if request is AJAX
     * 
     * @return bool
     */
    protected function isAjax() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    /**
     * Validate CSRF token for POST requests
     * Redirects back with error if invalid
     */
    protected function requireCsrf() {
        if ($this->isPost() && !Security::validateCsrf()) {
            $this->setFlash('error', 'Security validation failed. Please try again.');
            $this->redirect($_SERVER['HTTP_REFERER'] ?? 'dashboard');
        }
    }
    
    /**
     * Get sanitized POST data
     * 
     * @param string $key Key to get
     * @param mixed $default Default value
     * @return mixed Sanitized value
     */
    protected function sanitizedPost($key = null, $default = null) {
        if ($key === null) {
            return Security::cleanArray($_POST);
        }
        $value = isset($_POST[$key]) ? $_POST[$key] : $default;
        return is_array($value) ? Security::cleanArray($value) : Security::clean($value);
    }
    
    /**
     * Get sanitized GET data
     * 
     * @param string $key Key to get
     * @param mixed $default Default value
     * @return mixed Sanitized value
     */
    protected function sanitizedGet($key = null, $default = null) {
        if ($key === null) {
            return Security::cleanArray($_GET);
        }
        $value = isset($_GET[$key]) ? $_GET[$key] : $default;
        return is_array($value) ? Security::cleanArray($value) : Security::clean($value);
    }

    /**
     * Prevent destructive actions when in demo mode
     * 
     * @return bool
     */
    protected function requireNotDemo() {
        if (defined('APP_DEMO_MODE') && APP_DEMO_MODE) {
            if ($this->isAjax()) {
                $this->json(['error' => 'This action is restricted in demo mode.'], 403);
            }
            $this->setFlash('warning', 'This action is restricted in demo mode.');
            $this->redirect($_SERVER['HTTP_REFERER'] ?? 'dashboard');
            return false;
        }
        return true;
    }
}
