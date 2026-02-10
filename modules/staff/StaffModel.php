<?php
/**
 * Staff Model
 */

class StaffModel extends Model {
    
    public function getAllStaff() {
        return $this->select("SELECT * FROM users WHERE role != 'parent' ORDER BY full_name");
    }
    
    public function getStaffById($id) {
        return $this->selectOne("SELECT * FROM users WHERE id = ?", [$id]);
    }
    
    public function usernameExists($username) {
        return $this->selectOne("SELECT id FROM users WHERE username = ?", [$username]);
    }
    
    public function addStaff($data) {
        return $this->insert('users', $data);
    }
    
    public function updateStaff($id, $data) {
        return $this->update('users', $data, "id = :id", [':id' => $id]);
    }
}
