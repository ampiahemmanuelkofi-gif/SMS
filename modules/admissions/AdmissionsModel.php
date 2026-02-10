<?php
/**
 * AdmissionsModel Class
 * Handles database operations for student applications and recruitment
 */
class AdmissionsModel extends Model {
    
    /**
     * Get all applications with filters
     */
    public function getApplications($status = null) {
        $sql = "SELECT a.*, c.name as class_name 
                FROM admissions a 
                LEFT JOIN classes c ON a.class_id = c.id";
        
        $params = [];
        if ($status) {
            $sql .= " WHERE a.status = ?";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY a.created_at DESC";
        return $this->select($sql, $params);
    }
    
    /**
     * Get single application by ID
     */
    public function getApplication($id) {
        $sql = "SELECT a.*, c.name as class_name 
                FROM admissions a 
                LEFT JOIN classes c ON a.class_id = c.id 
                WHERE a.id = ?";
        return $this->selectOne($sql, [$id]);
    }
    
    /**
     * Create new application
     */
    public function createApplication($data) {
        return $this->insert('admissions', $data);
    }
    
    /**
     * Update application status
     */
    public function updateStatus($id, $status) {
        return $this->update('admissions', ['status' => $status], 'id = :id', [':id' => $id]);
    }
    
    /**
     * Add admission document
     */
    public function addDocument($data) {
        return $this->insert('admission_documents', $data);
    }
    
    /**
     * Get documents for an application
     */
    public function getDocuments($admissionId) {
        return $this->select("SELECT * FROM admission_documents WHERE admission_id = ?", [$admissionId]);
    }
    
    /**
     * Determine Admission Status Stats
     */
    public function getStats() {
        $defaults = [
            'total' => 0,
            'pending' => 0,
            'interviews' => 0,
            'accepted' => 0
        ];

        $result = $this->selectOne("SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN status = 'interview_scheduled' THEN 1 ELSE 0 END) as interviews,
            SUM(CASE WHEN status = 'accepted' THEN 1 ELSE 0 END) as accepted
            FROM admissions");
            
        return $result ? array_merge($defaults, $result) : $defaults;
    }
    
    /**
     * Generate unique application number
     */
    public function generateApplicationNumber() {
        $prefix = 'APP' . date('Y');
        $last = $this->selectOne("SELECT application_number FROM admissions WHERE application_number LIKE ? ORDER BY id DESC LIMIT 1", ["$prefix%"]);
        
        if ($last) {
            $num = (int)str_replace($prefix, '', $last['application_number']);
            return $prefix . str_pad($num + 1, 4, '0', STR_PAD_LEFT);
        }
        
        return $prefix . '0001';
    }
    
    /**
     * Schedule an interview
     */
    public function scheduleInterview($data) {
        return $this->insert('admission_interviews', $data);
    }
    
    /**
     * Get interviews for an application
     */
    public function getInterviews($admissionId) {
        $sql = "SELECT i.*, u.full_name as interviewer_name 
                FROM admission_interviews i 
                LEFT JOIN users u ON i.interviewer_id = u.id 
                WHERE i.admission_id = ? 
                ORDER BY i.interview_date DESC";
        return $this->select($sql, [$admissionId]);
    }
    
    /**
     * Get waitlisted students sorted by rank
     */
    public function getWaitlist() {
        $sql = "SELECT a.*, c.name as class_name 
                FROM admissions a 
                LEFT JOIN classes c ON a.class_id = c.id 
                WHERE a.status = 'waitlisted' 
                ORDER BY a.waiting_list_rank ASC, a.created_at ASC";
        return $this->select($sql);
    }
    
    /**
     * Update waitlist rank
     */
    public function updateWaitlistRank($id, $rank) {
        return $this->update('admissions', ['waiting_list_rank' => $rank], 'id = :id', [':id' => $id]);
    }
}
