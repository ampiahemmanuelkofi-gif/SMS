<?php
/**
 * Safeguarding Model
 */

class SafeguardingModel extends Model {
    
    /**
     * Get all safeguarding concerns with filters
     */
    public function getConcerns($filters = []) {
        $sql = "SELECT c.*, s.first_name, s.last_name, s.student_id as student_code, u.full_name as recorder_name 
                FROM safeguarding_concerns c
                JOIN students s ON c.student_id = s.id
                JOIN users u ON c.recorded_by = u.id
                WHERE 1=1";
        $params = [];
        
        if (!empty($filters['status'])) {
            $sql .= " AND c.status = ?";
            $params[] = $filters['status'];
        }
        
        if (!empty($filters['severity'])) {
            $sql .= " AND c.severity = ?";
            $params[] = $filters['severity'];
        }
        
        $sql .= " ORDER BY c.created_at DESC";
        return $this->select($sql, $params);
    }
    
    /**
     * Get a single concern with its actions and referrals
     */
    public function getConcernDetail($id) {
        $concern = $this->selectOne("
            SELECT c.*, s.first_name, s.last_name, s.student_id as student_code, u.full_name as recorder_name 
            FROM safeguarding_concerns c
            JOIN students s ON c.student_id = s.id
            JOIN users u ON c.recorded_by = u.id
            WHERE c.id = ?
        ", [$id]);
        
        if ($concern) {
            $concern['actions'] = $this->select("
                SELECT a.*, u.full_name as action_by_name 
                FROM safeguarding_actions a
                JOIN users u ON a.action_by = u.id
                WHERE a.concern_id = ? 
                ORDER BY a.action_date DESC
            ", [$id]);
            
            $concern['referrals'] = $this->select("SELECT * FROM safeguarding_referrals WHERE concern_id = ?", [$id]);
            $concern['attachments'] = $this->select("SELECT * FROM safeguarding_attachments WHERE concern_id = ?", [$id]);
        }
        
        return $concern;
    }
    
    /**
     * Get student chronology (Safeguarding history)
     */
    public function getStudentChronology($studentId) {
        return $this->select("
            SELECT 'concern' as event_type, id, title, description, severity, status, created_at as event_date
            FROM safeguarding_concerns
            WHERE student_id = ?
            UNION ALL
            SELECT 'action' as event_type, a.id, a.action_type as title, a.description, 'low' as severity, '' as status, a.action_date as event_date
            FROM safeguarding_actions a
            JOIN safeguarding_concerns c ON a.concern_id = c.id
            WHERE c.student_id = ?
            ORDER BY event_date DESC
        ", [$studentId, $studentId]);
    }
    
    /**
     * Record a new concern
     */
    public function addConcern($data) {
        return $this->insert('safeguarding_concerns', $data);
    }
    
    /**
     * Add action to a concern
     */
    public function addAction($data) {
        return $this->insert('safeguarding_actions', $data);
    }
    
    /**
     * Audit log for safeguarding access
     */
    public function logAudit($data) {
        $data['ip_address'] = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        return $this->insert('safeguarding_audit_logs', $data);
    }
    
    /**
     * Pattern detection: check for multiple concerns
     */
    public function checkPattern($studentId, $days = 30) {
        return $this->count('safeguarding_concerns', "student_id = :sid AND created_at >= DATE_SUB(NOW(), INTERVAL :days DAY)", [
            ':sid' => $studentId,
            ':days' => $days
        ]);
    }
    
    /**
     * Anonymize data for GDPR "Right to be Forgotten"
     */
    public function anonymizeStudent($studentId) {
        // Anonymize character data but keep records for statistical/historical audit if needed, 
        // or hard delete based on policy. Here we update to generic values.
        $this->update('safeguarding_concerns', [
            'title' => 'ANONYMIZED',
            'description' => 'Data deleted per Right to be Forgotten request.'
        ], 'student_id = :sid', [':sid' => $studentId]);
        
        $this->update('safeguarding_actions', [
            'description' => 'ANONYMIZED'
        ], 'concern_id IN (SELECT id FROM safeguarding_concerns WHERE student_id = :sid)', [':sid' => $studentId]);
        
        return true;
    }
}
