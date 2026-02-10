<?php
/**
 * Communication Model
 * Handles announcements, internal messaging, and comm logs
 */

class CommunicationModel extends Model {
    
    // --- Announcements ---
    public function getAnnouncements($audience = null) {
        $sql = "SELECT a.*, u.full_name as author_name 
                FROM communication_announcements a
                JOIN users u ON a.posted_by = u.id
                WHERE a.is_published = 1";
        $params = [];
        if ($audience && $audience != 'all') {
            $sql .= " AND (a.target_audience = ? OR a.target_audience = 'all')";
            $params[] = $audience;
        }
        $sql .= " ORDER BY a.created_at DESC";
        return $this->select($sql, $params);
    }

    public function addAnnouncement($data) {
        return $this->insert('communication_announcements', $data);
    }

    public function updateAnnouncement($id, $data) {
        return $this->update('communication_announcements', $data, "id = :id", [':id' => $id]);
    }

    // --- Private Messaging ---
    public function getInbox($userId) {
        return $this->select("
            SELECT m.*, u.full_name as sender_name 
            FROM communication_messages m
            JOIN users u ON m.sender_id = u.id
            WHERE m.receiver_id = ?
            ORDER BY m.created_at DESC
        ", [$userId]);
    }

    public function getSentMessages($userId) {
        return $this->select("
            SELECT m.*, u.full_name as receiver_name 
            FROM communication_messages m
            JOIN users u ON m.receiver_id = u.id
            WHERE m.sender_id = ?
            ORDER BY m.created_at DESC
        ", [$userId]);
    }

    public function sendMessage($data) {
        return $this->insert('communication_messages', $data);
    }

    public function markAsRead($id) {
        return $this->update('communication_messages', ['is_read' => 1], "id = :id", [':id' => $id]);
    }

    // --- Communication Logs (Bulk) ---
    public function logCommunication($data) {
        return $this->insert('communication_logs', $data);
    }

    public function getCommLogs() {
        return $this->select("SELECT * FROM communication_logs ORDER BY created_at DESC LIMIT 100");
    }

    // --- Templates ---
    public function getTemplates() {
        return $this->select("SELECT * FROM communication_templates ORDER BY name");
    }

    public function getTemplateById($id) {
        $result = $this->select("SELECT * FROM communication_templates WHERE id = ?", [$id]);
        return $result ? $result[0] : null;
    }
}
