<?php
/**
 * Grading Service
 * Handles all grade calculations and scale lookups
 */
class GradingService {
    
    protected $db;
    
    public function __construct() {
        $this->db = getDbConnection();
    }
    
    /**
     * Get Grade and Remark based on percentage score and system
     */
    public function getGrade($percentage, $gradingSystemId) {
        $stmt = $this->db->prepare("
            SELECT * FROM grading_scales 
            WHERE system_id = ? 
            AND ? >= min_score 
            AND ? <= max_score 
            LIMIT 1
        ");
        $stmt->execute([$gradingSystemId, $percentage, $percentage]);
        return $stmt->fetch();
    }
    
    /**
     * Calculate Weighted Score
     */
    public function calculateWeightedScore($marks, $totalWeight) {
        if ($totalWeight <= 0) return 0;
        return ($marks / 100) * $totalWeight;
    }
    
    /**
     * Get configured weights for a subject in a class
     */
    public function getSubjectWeights($classId, $subjectId, $termId) {
        $stmt = $this->db->prepare("
            SELECT sw.*, ac.name as category_name 
            FROM subject_weights sw 
            JOIN assessment_categories ac ON sw.category_id = ac.id 
            WHERE sw.class_id = ? AND sw.subject_id = ? AND sw.term_id = ?
        ");
        $stmt->execute([$classId, $subjectId, $termId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Get all Grading Systems
     */
    public function getGradingSystems() {
        return $this->db->query("SELECT * FROM grading_systems")->fetchAll();
    }
    
    /**
     * Get Scales for a System
     */
    public function getScales($systemId) {
        $stmt = $this->db->prepare("SELECT * FROM grading_scales WHERE system_id = ? ORDER BY max_score DESC");
        $stmt->execute([$systemId]);
        return $stmt->fetchAll();
    }

    /**
     * Create default scales for a new system
     */
    public function createDefaultScales($systemId, $type = 'waec') {
        if ($type === 'waec') {
            $scales = [
                ['A1', 80, 100, 4.0, 'Excellent'],
                ['B2', 70, 79.99, 3.5, 'Very Good'],
                ['B3', 65, 69.99, 3.0, 'Good'],
                ['C4', 60, 64.99, 2.5, 'Credit'],
                ['C5', 55, 59.99, 2.0, 'Credit'],
                ['C6', 50, 54.99, 1.5, 'Credit'],
                ['D7', 45, 49.99, 1.0, 'Pass'],
                ['E8', 40, 44.99, 0.5, 'Pass'],
                ['F9', 0, 39.99, 0.0, 'Fail']
            ];
            
            $stmt = $this->db->prepare("INSERT INTO grading_scales (system_id, grade, min_score, max_score, gpa_point, remark) VALUES (?, ?, ?, ?, ?, ?)");
            foreach ($scales as $scale) {
                $stmt->execute([$systemId, $scale[0], $scale[1], $scale[2], $scale[3], $scale[4]]);
            }
        }
    }
}
