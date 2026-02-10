<?php
/**
 * Security Utilities
 * 
 * XSS prevention, CSRF protection, input sanitization
 */

class Security {
    
    /**
     * Sanitize input to prevent XSS
     * 
     * @param string $data
     * @return string
     */
    public static function clean($data) {
        return htmlspecialchars((string)$data, ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Sanitize array of data
     * 
     * @param array $data
     * @return array
     */
    public static function cleanArray($data) {
        $cleaned = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $cleaned[$key] = self::cleanArray($value);
            } else {
                $cleaned[$key] = self::clean($value);
            }
        }
        return $cleaned;
    }
    
    /**
     * Validate email address
     * 
     * @param string $email
     * @return bool
     */
    public static function isValidEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Validate phone number (Ghana format)
     * 
     * @param string $phone
     * @return bool
     */
    public static function isValidPhone($phone) {
        // Ghana phone: +233XXXXXXXXX or 0XXXXXXXXX
        $pattern = '/^(\+233|0)[0-9]{9}$/';
        return preg_match($pattern, $phone) === 1;
    }
    
    /**
     * Validate date format
     * 
     * @param string $date
     * @param string $format
     * @return bool
     */
    public static function isValidDate($date, $format = 'Y-m-d') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
    
    /**
     * Generate random string
     * 
     * @param int $length
     * @return string
     */
    public static function generateRandomString($length = 32) {
        return bin2hex(random_bytes($length / 2));
    }
    
    /**
     * Validate file upload
     * 
     * @param array $file $_FILES array element
     * @param array $allowedTypes Allowed file extensions
     * @param int $maxSize Maximum file size in bytes
     * @return array ['valid' => bool, 'error' => string]
     */
    public static function validateFileUpload($file, $allowedTypes, $maxSize) {
        // Check if file was uploaded
        if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
            return ['valid' => false, 'error' => 'No file uploaded'];
        }
        
        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['valid' => false, 'error' => 'File upload error'];
        }
        
        // Check file size
        if ($file['size'] > $maxSize) {
            $maxSizeMB = $maxSize / (1024 * 1024);
            return ['valid' => false, 'error' => "File size exceeds {$maxSizeMB}MB limit"];
        }
        
        // Check file extension
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $allowedTypes)) {
            return ['valid' => false, 'error' => 'Invalid file type. Allowed: ' . implode(', ', $allowedTypes)];
        }
        
        // Check MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        $allowedMimes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];
        
        if (isset($allowedMimes[$extension]) && $mimeType !== $allowedMimes[$extension]) {
            return ['valid' => false, 'error' => 'File type mismatch'];
        }
        
        return ['valid' => true, 'error' => null];
    }
    
    /**
     * Sanitize filename
     * 
     * @param string $filename
     * @return string
     */
    public static function sanitizeFilename($filename) {
        // Remove special characters
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
        
        // Add timestamp to prevent collisions
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $basename = pathinfo($filename, PATHINFO_FILENAME);
        
        return $basename . '_' . time() . '.' . $extension;
    }
    
    /**
     * Generate CSRF hidden input field
     * 
     * @return string HTML hidden input
     */
    public static function csrfInput() {
        $token = Auth::generateCsrfToken();
        return '<input type="hidden" name="csrf_token" value="' . self::clean($token) . '">';
    }
    
    /**
     * Validate CSRF token from POST request
     * 
     * @return bool
     */
    public static function validateCsrf() {
        $token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
        return Auth::validateCsrfToken($token);
    }
    
    /**
     * Check rate limit for an action
     * 
     * @param string $key Unique identifier (e.g., IP + action)
     * @param int $maxAttempts Maximum attempts allowed
     * @param int $windowSeconds Time window in seconds
     * @return bool True if within limit, false if exceeded
     */
    public static function checkRateLimit($key, $maxAttempts = 5, $windowSeconds = 300) {
        $cacheKey = 'rate_limit_' . md5($key);
        
        if (!isset($_SESSION[$cacheKey])) {
            $_SESSION[$cacheKey] = ['attempts' => 0, 'first_attempt' => time()];
        }
        
        $data = $_SESSION[$cacheKey];
        
        // Reset if window expired
        if (time() - $data['first_attempt'] > $windowSeconds) {
            $_SESSION[$cacheKey] = ['attempts' => 1, 'first_attempt' => time()];
            return true;
        }
        
        // Increment and check
        $_SESSION[$cacheKey]['attempts']++;
        
        return $_SESSION[$cacheKey]['attempts'] <= $maxAttempts;
    }
    
    /**
     * Validate password strength
     * 
     * @param string $password
     * @return array ['valid' => bool, 'errors' => array]
     */
    public static function validatePasswordStrength($password) {
        $errors = [];
        
        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters long';
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = 'Password must contain at least one uppercase letter';
        }
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = 'Password must contain at least one lowercase letter';
        }
        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = 'Password must contain at least one number';
        }
        
        return ['valid' => empty($errors), 'errors' => $errors];
    }
}
