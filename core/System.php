<?php
/**
 * Global System Utility
 * Handles core system operations and global configuration
 */

class System {
    
    protected static $settings = [];
    protected static $initialized = false;

    /**
     * Initialize the system and load all settings from DB
     */
    public static function init() {
        if (self::$initialized) return;

        try {
            $db = getDbConnection();
            $stmt = $db->query("SELECT setting_key, setting_value FROM settings");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($rows as $row) {
                self::$settings[$row['setting_key']] = $row['setting_value'];
            }
            
            self::$initialized = true;
        } catch (Exception $e) {
            error_log("System Init Error: " . $e->getMessage());
        }
    }

    /**
     * Get a system setting
     * 
     * @param string $key Setting key
     * @param mixed $default Default value if key not found
     * @return mixed
     */
    public static function getSetting($key, $default = null) {
        if (!self::$initialized) self::init();
        
        return isset(self::$settings[$key]) ? self::$settings[$key] : $default;
    }

    /**
     * Check if a module is enabled
     * 
     * @param string $moduleKey Module setting key (e.g., 'module_library')
     * @return bool
     */
    public static function isModuleEnabled($moduleKey) {
        // Core modules are always enabled
        $coreModules = ['core', 'students', 'admissions'];
        if (in_array($moduleKey, $coreModules)) return true;

        return self::getSetting($moduleKey, '1') === '1';
    }
}
