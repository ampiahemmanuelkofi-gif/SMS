<?php
/**
 * Assessments Model
 */

class AssessmentsModel extends Model {
    
    /**
     * Get students for assessment entry
     */
    public function getStudentsForAssessment($sectionId) {
        return $this->select("SELECT id, first_name, last_name, student_id FROM students WHERE section_id = ? AND status = 'active' ORDER BY last_name, first_name", [$sectionId]);
    }
    
    /**
     * Get existing marks for a class/subject/term/type
     */
    public function getExistingMarks($sectionId, $subjectId, $termId, $type) {
        $sql = "SELECT student_id, marks, remarks FROM assessments WHERE section_id = ? AND subject_id = ? AND term_id = ? AND assessment_type = ?";
        return $this->select($sql, [$sectionId, $subjectId, $termId, $type]);
    }
    
    /**
     * Grade calculation based on Ghana BECE (1-9 scale)
     */
    public function calculateGrade($marks) {
        if ($marks >= 80) return 1;
        if ($marks >= 70) return 2;
        if ($marks >= 60) return 3;
        if ($marks >= 55) return 4;
        if ($marks >= 50) return 5;
        if ($marks >= 45) return 6;
        if ($marks >= 40) return 7;
        if ($marks >= 35) return 8;
        return 9;
    }
    
    /**
     * Get remark based on grade
     */
    public function getGradeRemark($grade) {
        $remarks = [
            1 => 'Highest',
            2 => 'Higher',
            3 => 'High',
            4 => 'High Average',
            5 => 'Average',
            6 => 'Low Average',
            7 => 'Low',
            8 => 'Lower',
            9 => 'Lowest'
        ];
        return $remarks[$grade] ?? 'N/A';
    }
    
    /**
     * Save/Update assessment
     */
    public function saveAssessment($data) {
        $check = $this->select("SELECT id FROM assessments WHERE student_id = ? AND section_id = ? AND subject_id = ? AND term_id = ? AND assessment_type = ?", 
            [$data['student_id'], $data['section_id'], $data['subject_id'], $data['term_id'], $data['assessment_type']]);
        
        if ($check) {
            return $this->update('assessments', $data, "id = :id", [':id' => $check[0]['id']]);
        } else {
            return $this->insert('assessments', $data);
        }
    }
}
