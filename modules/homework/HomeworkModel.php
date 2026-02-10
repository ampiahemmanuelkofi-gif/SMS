<?php
/**
 * Homework Model
 */

class HomeworkModel extends Model {
    
    public function getHomeworkBySection($sectionId) {
        $sql = "SELECT h.*, sub.name as subject_name, u.full_name as teacher_name 
                FROM homework h
                JOIN subjects sub ON h.subject_id = sub.id
                JOIN users u ON h.created_by = u.id
                WHERE h.section_id = ? 
                ORDER BY h.deadline ASC";
        return $this->select($sql, [$sectionId]);
    }
    
    public function getHomeworkByTeacher($teacherId) {
        $sql = "SELECT h.*, s.name as section_name, c.name as class_name, sub.name as subject_name
                FROM homework h
                JOIN sections s ON h.section_id = s.id
                JOIN classes c ON s.class_id = c.id
                JOIN subjects sub ON h.subject_id = sub.id
                WHERE h.created_by = ?
                ORDER BY h.created_at DESC";
        return $this->select($sql, [$teacherId]);
    }
}
