<?php
/**
 * Notices Model
 */

class NoticesModel extends Model {
    
    /**
     * Get active notices for a target audience
     */
    public function getActiveNotices($audience = 'all') {
        $sql = "SELECT n.*, u.full_name as posted_by_name 
                FROM notices n 
                LEFT JOIN users u ON n.posted_by = u.id 
                WHERE (n.target_audience = ? OR n.target_audience = 'all') 
                AND (n.expires_at IS NULL OR n.expires_at >= CURDATE()) 
                ORDER BY n.created_at DESC";
        return $this->select($sql, [$audience]);
    }
    
    /**
     * Get all notices (for admin)
     */
    public function getAllNotices() {
        $sql = "SELECT n.*, u.full_name as posted_by_name 
                FROM notices n 
                LEFT JOIN users u ON n.posted_by = u.id 
                ORDER BY n.created_at DESC";
        return $this->select($sql);
    }
    
    /**
     * Add a new notice
     */
    public function addNotice($data) {
        return $this->insert('notices', $data);
    }
    
    /**
     * Get notice by ID
     */
    public function getNoticeById($id) {
        $sql = "SELECT n.*, u.full_name as posted_by_name 
                FROM notices n 
                LEFT JOIN users u ON n.posted_by = u.id 
                WHERE n.id = ?";
        $result = $this->select($sql, [$id]);
        return $result ? $result[0] : null;
    }
    
    /**
     * Update notice
     */
    public function updateNotice($id, $data) {
        return $this->update('notices', $data, 'id = :id', [':id' => $id]);
    }
    
    /**
     * Delete notice
     */
    public function deleteNotice($id) {
        return $this->delete('notices', 'id = ?', [$id]);
    }
}
