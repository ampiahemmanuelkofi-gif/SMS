<?php
/**
 * Promotions Model
 */

class PromotionsModel extends Model {
    
    /**
     * Get promotion history for a specific student
     * 
     * @param int $studentId
     * @return array
     */
    public function getStudentPromotionHistory($studentId) {
        return $this->select("
            SELECT p.*, ay.name as academic_year, 
                   fs.name as from_section, ts.name as to_section,
                   fc.name as from_class, tc.name as to_class
            FROM promotions p
            JOIN academic_years ay ON p.academic_year_id = ay.id
            JOIN sections fs ON p.from_section_id = fs.id
            JOIN sections ts ON p.to_section_id = ts.id
            JOIN classes fc ON fs.class_id = fc.id
            JOIN classes tc ON ts.class_id = tc.id
            WHERE p.student_id = ?
            ORDER BY p.promotion_date DESC
        ", [$studentId]);
    }

    /**
     * Log a student promotion
     */
    public function logPromotion($data) {
        return $this->insert('promotions', $data);
    }

    /**
     * Get all promotions in an academic year
     */
    public function getPromotionsByYear($yearId) {
        return $this->select("
            SELECT p.*, s.first_name, s.last_name, s.student_id as student_code
            FROM promotions p
            JOIN students s ON p.student_id = s.id
            WHERE p.academic_year_id = ?
        ", [$yearId]);
    }
}
