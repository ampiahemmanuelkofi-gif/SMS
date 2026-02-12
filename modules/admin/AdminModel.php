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
        $stats['active_sessions'] = $this->selectOne("
            SELECT COUNT(DISTINCT user_id) as count 
            FROM login_logs 
            WHERE login_time > DATE_SUB(NOW(), INTERVAL 15 MINUTE)
        ")['count'] ?? 0;
        $stats['database_size'] = '45 MB'; // Mock
        $stats['disk_usage'] = '12% Used'; // Mock
        return $stats;
    }
    
    /**
     * Get all users with roles
     */
    public function getAllUsers() {
        return $this->select("
            SELECT u.*, MAX(ll.login_time) as last_login
            FROM users u
            LEFT JOIN login_logs ll ON u.id = ll.user_id
            GROUP BY u.id
            ORDER BY u.full_name ASC
        ");
    }

    /**
     * Get user by ID
     */
    public function getUserById($id) {
        return $this->selectOne("SELECT * FROM users WHERE id = :id", [':id' => $id]);
    }

    /**
     * Check if username exists
     */
    public function usernameExists($username, $excludeId = null) {
        $sql = "SELECT id FROM users WHERE username = :username";
        $params = [':username' => $username];
        
        if ($excludeId) {
            $sql .= " AND id != :id";
            $params[':id'] = $excludeId;
        }
        
        return $this->selectOne($sql, $params);
    }
    
    public function insertUser($data) {
        return $this->insert('users', $data);
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
                ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)";
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
     * Get all permissions for all roles
     */
    public function getRolePermissions() {
        return $this->select("SELECT * FROM role_permissions ORDER BY role ASC, module_key ASC");
    }

    /**
     * Update a specific permission
     */
    public function updateRolePermission($role, $moduleKey, $canAccess) {
        $sql = "UPDATE role_permissions SET can_access = :can WHERE role = :role AND module_key = :module";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([':can' => $canAccess, ':role' => $role, ':module' => $moduleKey]);
        
        if ($result) {
            $this->logAction($_SESSION['user_id'], 'update_permission', "Updated $moduleKey access for $role to $canAccess");
        }
        return $result;
    }

    /**
     * Get permission overrides for a specific user
     */
    public function getUserPermissions($userId) {
        $permissions = $this->select("SELECT * FROM user_permissions WHERE user_id = :user_id", [':user_id' => $userId]);
        $map = [];
        foreach ($permissions as $p) {
            $map[$p['module_key']] = $p['can_access'];
        }
        return $map;
    }

    /**
     * Update or Insert a user permission override
     */
    public function updateUserPermission($userId, $moduleKey, $canAccess) {
        $sql = "INSERT INTO user_permissions (user_id, module_key, can_access) 
                VALUES (:user_id, :module, :can) 
                ON DUPLICATE KEY UPDATE can_access = VALUES(can_access)";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([':user_id' => $userId, ':module' => $moduleKey, ':can' => $canAccess]);
        
        if ($result) {
            $this->logAction($_SESSION['user_id'], 'update_user_permission', "Updated $moduleKey access for User ID $userId to $canAccess");
        }
        return $result;
    }

    /**
     * Clear all overrides for a user (Reset to role defaults)
     */
    public function clearUserPermissions($userId) {
        $result = $this->delete('user_permissions', 'user_id = :id', [':id' => $userId]);
        if ($result) {
            $this->logAction($_SESSION['user_id'], 'reset_user_permissions', "Reset permissions to defaults for User ID $userId");
        }
        return $result;
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
