<?php
/**
 * Settings Model
 * Handles all system settings operations
 */

class SettingsModel extends Model {
    
    /**
     * Get all settings as key => value array
     */
    public function getAllSettings() {
        $rows = $this->select("SELECT setting_key, setting_value, setting_group FROM settings ORDER BY setting_group, setting_key");
        $settings = [];
        foreach ($rows as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        return $settings;
    }
    
    /**
     * Get settings by group
     */
    public function getByGroup($group) {
        return $this->select("SELECT * FROM settings WHERE setting_group = ? ORDER BY setting_key", [$group]);
    }
    
    /**
     * Get a single setting value
     */
    public function get($key, $default = null) {
        $result = $this->select("SELECT setting_value FROM settings WHERE setting_key = ?", [$key]);
        return $result ? $result[0]['setting_value'] : $default;
    }
    
    /**
     * Save multiple settings
     */
    public function saveSettings($data) {
        $db = getDbConnection();
        $db->beginTransaction();
        
        try {
            $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) 
                                  ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
            
            foreach ($data as $key => $value) {
                $stmt->execute([$key, $value]);
            }
            
            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            error_log("Settings save error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Set a single setting
     */
    public function set($key, $value) {
        return $this->saveSettings([$key => $value]);
    }
    
    /**
     * Delete a setting
     */
    public function deleteSetting($key) {
        return $this->delete('settings', 'setting_key = ?', [$key]);
    }
}
