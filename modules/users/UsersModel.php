<?php
/**
 * Users Model
 * Core user data access
 */

class UsersModel extends Model {
    
    /**
     * Get users by role
     * 
     * @param string $role User role
     * @param int $isActive Active status
     * @return array
     */
    public function getUsersByRole($role, $isActive = 1) {
        return $this->select("
            SELECT id, username, full_name, role, email, phone 
            FROM users 
            WHERE role = ? AND is_active = ?
            ORDER BY full_name
        ", [$role, $isActive]);
    }

    /**
     * Get single user by ID
     * 
     * @param int $id
     * @return array|null
     */
    public function getUserById($id) {
        return $this->selectOne("SELECT * FROM users WHERE id = ?", [$id]);
    }
}
