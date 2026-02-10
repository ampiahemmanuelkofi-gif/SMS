<?php
/**
 * Support Model
 */

class SupportModel extends Model {
    
    /**
     * Get user tickets
     */
    public function getTickets($userId = null) {
        $sql = "SELECT st.*, u.full_name as author 
                FROM support_tickets st 
                JOIN users u ON st.user_id = u.id";
        $params = [];
        
        if ($userId) {
            $sql .= " WHERE st.user_id = :uid";
            $params[':uid'] = $userId;
        }
        
        $sql .= " ORDER BY st.created_at DESC";
        return $this->select($sql, $params);
    }
    
    /**
     * Create a new ticket
     */
    public function createTicket($data) {
        return $this->insert('support_tickets', $data);
    }
    
    /**
     * Get ticket details
     */
    public function getTicket($id) {
        return $this->select("SELECT st.*, u.full_name FROM support_tickets st JOIN users u ON st.user_id = u.id WHERE st.id = ?", [$id])[0] ?? null;
    }
    
    /**
     * Get FAQ
     */
    public function getFAQs($category = null) {
        $sql = "SELECT * FROM support_faq";
        $params = [];
        if ($category) {
            $sql .= " WHERE category = :cat";
            $params[':cat'] = $category;
        }
        return $this->select($sql, $params);
    }
    
    /**
     * Get Feature Requests with user vote status
     */
    public function getFeatureRequests($userId) {
        return $this->select("
            SELECT fr.*, (SELECT COUNT(*) FROM feature_votes fv WHERE fv.request_id = fr.id AND fv.user_id = ?) as has_voted
            FROM feature_requests fr
            ORDER BY fr.votes DESC
        ", [$userId]);
    }
    
    /**
     * Vote for a feature
     */
    public function voteForFeature($requestId, $userId) {
        $check = $this->select("SELECT * FROM feature_votes WHERE request_id = ? AND user_id = ?", [$requestId, $userId]);
        if (empty($check)) {
            $this->insert('feature_votes', ['request_id' => $requestId, 'user_id' => $userId]);
            $this->db->query("UPDATE feature_requests SET votes = votes + 1 WHERE id = $requestId");
            return true;
        }
        return false;
    }
}
