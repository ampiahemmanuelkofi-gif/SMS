<?php
/**
 * Admin Model
 */

class AdminModel extends Model {
    
    /**
     * Get recent audit logs
     */
    public function getAuditLogs($limit = 50) {
        return $this->select("
            SELECT al.*, u.username, u.full_name
            FROM audit_logs al
            LEFT JOIN users u ON al.user_id = u.id
            ORDER BY al.created_at DESC
            LIMIT :limit
        ", [':limit' => $limit]);
    }
    
    /**
     * Get system statistics
     */
    public function getSystemStats() {
        $stats = [];
        $stats['user_count'] = $this->count('users');
        $stats['active_sessions'] = $this->count('users', 'last_login > DATE_SUB(NOW(), INTERVAL 15 MINUTE)');
        $stats['database_size'] = '45 MB'; // Mock
        $stats['disk_usage'] = '12% Used'; // Mock
        return $stats;
    }
    
    /**
     * Get all users with roles
     */
    public function getAllUsers() {
        return $this->select("SELECT * FROM users ORDER BY full_name ASC");
    }
    
    /**
     * Update user details
     */
    public function updateUser($id, $data) {
        // Map status to is_active if present
        if (isset($data['status'])) {
            $data['is_active'] = ($data['status'] === 'active') ? 1 : 0;
            unset($data['status']);
        }
        return $this->update('users', $data, 'id = :id', [':id' => $id]);
    }
    
    public function getSettings() {
        $settings = $this->select("SELECT * FROM settings");
        $formatted = [];
        foreach ($settings as $s) {
            $formatted[$s['setting_key']] = $s['setting_value'];
        }
        return $formatted;
    }
    
    /**
     * Update system setting
     */
    public function updateSetting($key, $value) {
        $sql = "INSERT INTO settings (setting_key, setting_value) 
                VALUES (:key, :val) 
                ON DUPLICATE KEY UPDATE setting_value = :val";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':key' => $key, ':val' => $value]);
    }

    /**
     * Mock Database Backup
     */
    public function generateBackup() {
        $filename = 'backup_' . date('Y-m-d_His') . '.sql';
        // Mocking log entry for backup
        $this->logAction($_SESSION['user_id'], 'database_backup', 'Generated database backup: ' . $filename);
        return $filename;
    }

    /**
     * Apply a school type profile to active modules
     */
    public function applyProfile($type) {
        $matrix = [
            'small_private' => ['core', 'attendance', 'fees', 'communication', 'parent_portal', 'library', 'transport'],
            'medium_basic' => ['core', 'attendance', 'fees', 'communication', 'parent_portal', 'assessment', 'transport', 'library', 'discipline', 'hostel', 'cafeteria', 'bi'],
            'large_boarding' => ['core', 'attendance', 'fees', 'communication', 'parent_portal', 'assessment', 'transport', 'library', 'discipline', 'hostel', 'cafeteria', 'bi', 'asset', 'medical', 'events', 'hr'],
            'school_group' => ['core', 'attendance', 'fees', 'communication', 'parent_portal', 'assessment', 'transport', 'library', 'discipline', 'hostel', 'cafeteria', 'bi', 'asset', 'medical', 'events', 'hr', 'multi_branch', 'api_platform', 'ai_automation', 'lms'],
            'international' => ['core', 'attendance', 'fees', 'communication', 'parent_portal', 'assessment', 'transport', 'library', 'discipline', 'hostel', 'cafeteria', 'bi', 'asset', 'medical', 'events', 'hr', 'multi_branch', 'api_platform', 'ai_automation', 'lms', 'multi_curriculum', 'admissions_crm', 'agent_management']
        ];

        if (!isset($matrix[$type])) return false;

        $activeModules = $matrix[$type];
        
        // Mocking module activation in system_settings
        $this->updateSetting('enabled_modules', json_encode($activeModules));
        $this->updateSetting('school_profile', $type);
        
        $this->logAction($_SESSION['user_id'], 'apply_profile', 'Applied school profile: ' . $type);
        return true;
    }

    /**
     * Log administrative action
     */
    private function logAction($userId, $action, $detail) {
        $this->insert('audit_logs', [
            'user_id' => $userId,
            'action' => $action,
            'detail' => $detail,
            'ip_address' => $_SERVER['REMOTE_ADDR']
        ]);
    }
}
