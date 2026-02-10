<?php
/**
 * Authentication Class
 * 
 * Handles user authentication and authorization
 */

class Auth {
    
    /**
     * Login user
     * 
     * @param string $username
     * @param string $password
     * @return bool Success status
     */
    public static function login($username, $password) {
        $db = getDbConnection();
        
        try {
            $sql = "SELECT * FROM users WHERE username = :username AND is_active = 1 LIMIT 1";
            $stmt = $db->prepare($sql);
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                // Regenerate session ID to prevent session fixation attacks
                session_regenerate_id(true);
                
                // Set session data
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['last_activity'] = time();
                
                // Log login
                self::logLogin($user['id']);
                
                return true;
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("Login Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Logout user
     */
    public static function logout() {
        session_unset();
        session_destroy();
    }
    
    /**
     * Check if user is logged in
     * 
     * @return bool
     */
    public static function isLoggedIn() {
        if (!isset($_SESSION['user_id'])) {
            return false;
        }
        
        // Check session timeout
        if (isset($_SESSION['last_activity']) && 
            (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT)) {
            self::logout();
            return false;
        }
        
        // Update last activity
        $_SESSION['last_activity'] = time();
        
        return true;
    }
    
    /**
     * Get current user ID
     * 
     * @return int|null
     */
    public static function getUserId() {
        return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    }
    
    /**
     * Get current user role
     * 
     * @return string|null
     */
    public static function getRole() {
        return isset($_SESSION['role']) ? $_SESSION['role'] : null;
    }
    
    /**
     * Get current user full name
     * 
     * @return string|null
     */
    public static function getFullName() {
        return isset($_SESSION['full_name']) ? $_SESSION['full_name'] : null;
    }
    
    /**
     * Check if user has specific role(s)
     * 
     * @param string|array $roles Role(s) to check
     * @return bool
     */
    public static function hasRole($roles) {
        if (!is_array($roles)) {
            $roles = [$roles];
        }
        
        $userRole = self::getRole();
        return in_array($userRole, $roles);
    }
    
    /**
     * Generate CSRF token
     * 
     * @return string
     */
    public static function generateCsrfToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Validate CSRF token
     * 
     * @param string $token
     * @return bool
     */
    public static function validateCsrfToken($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Log user login
     * 
     * @param int $userId
     */
    private static function logLogin($userId) {
        $db = getDbConnection();
        
        try {
            $sql = "INSERT INTO login_logs (user_id, ip_address, user_agent) 
                    VALUES (:user_id, :ip_address, :user_agent)";
            
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':user_id' => $userId,
                ':ip_address' => $_SERVER['REMOTE_ADDR'],
                ':user_agent' => $_SERVER['HTTP_USER_AGENT']
            ]);
        } catch (PDOException $e) {
            error_log("Login Log Error: " . $e->getMessage());
        }
    }
    
    /**
     * Hash password
     * 
     * @param string $password
     * @return string
     */
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT);
    }
}
