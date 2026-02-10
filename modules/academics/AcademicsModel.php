<?php
/**
 * Academic Model
 */

class AcademicsModel extends Model {
    
    /**
     * Get all classes
     */
    public function getClasses() {
        return $this->select("SELECT * FROM classes ORDER BY level, name");
    }
    
    /**
     * Get all academic programs
     */
    public function getPrograms() {
        return $this->select("SELECT * FROM academic_programs ORDER BY name ASC");
    }
    
    /**
     * Get sections for a specific class
     */
    public function getSectionsByClass($classId) {
        return $this->select("SELECT * FROM sections WHERE class_id = :class_id ORDER BY name", [':class_id' => $classId]);
    }
    
    /**
     * Get all subjects
     */

    public function getSubjects($level = null) {
        $sql = "SELECT * FROM subjects";
        $params = [];
        
        if ($level) {
            $sql .= " WHERE level = :level OR level = 'all'";
            $params[':level'] = $level;
        }
        
        return $this->select($sql, $params);
    }

    /**
     * Alias for getSubjects to support new controllers
     */
    public function getAllSubjects() {
        return $this->getSubjects();
    }
    
    /**
     * Get current academic year
     */
    public function getCurrentAcademicYear() {
        return $this->selectOne("SELECT * FROM academic_years WHERE is_current = 1 LIMIT 1");
    }
    
    /**
     * Get all academic years
     */
    public function getAcademicYears() {
        return $this->select("SELECT * FROM academic_years ORDER BY start_date DESC");
    }
    
    /**
     * Get all terms with year info
     */
    public function getTerms() {
        return $this->select("SELECT t.*, ay.name as year_name 
                            FROM terms t 
                            JOIN academic_years ay ON t.academic_year_id = ay.id 
                            ORDER BY ay.start_date DESC, t.start_date DESC");
    }
    
    /**
     * Add new class
     */
    public function addClass($data) {
        return $this->insert('classes', $data);
    }
    
    /**
     * Update class
     */
    public function updateClass($id, $data) {
        return $this->update('classes', $data, 'id = :id', [':id' => $id]);
    }
    
    /**
     * Add new section
     */
    public function addSection($data) {
        return $this->insert('sections', $data);
    }
    
    /**
     * Update section
     */
    public function updateSection($id, $data) {
        return $this->update('sections', $data, 'id = :id', [':id' => $id]);
    }
    
    /**
     * Add new subject
     */
    public function addSubject($data) {
        return $this->insert('subjects', $data);
    }
    
    /**
     * Update subject
     */
    public function updateSubject($id, $data) {
        return $this->update('subjects', $data, 'id = :id', [':id' => $id]);
    }
    
    /**
     * Assign teacher
     */
    public function assignTeacher($data) {
        return $this->insert('teacher_assignments', $data);
    }
    
    /**
     * Get teacher assignments
     */
    public function getTeacherAssignments() {
        return $this->select("
            SELECT ta.*, u.full_name as teacher_name, c.name as class_name, sec.name as section_name, sub.name as subject_name
            FROM teacher_assignments ta
            JOIN users u ON ta.teacher_id = u.id
            JOIN sections sec ON ta.section_id = sec.id
            JOIN classes c ON sec.class_id = c.id
            JOIN subjects sub ON ta.subject_id = sub.id
            ORDER BY c.name, sec.name, sub.name
        ");
    }

    /**
     * Get sections assigned to a specific teacher
     */
    public function getTeacherSections($teacherId) {
        return $this->select("
            SELECT DISTINCT sec.*, c.name as class_name
            FROM teacher_assignments ta
            JOIN sections sec ON ta.section_id = sec.id
            JOIN classes c ON sec.class_id = c.id
            WHERE ta.teacher_id = ?
        ", [$teacherId]);
    }

    /**
     * Get schedule for a specific teacher
     */
    public function getTeacherSchedule($teacherId) {
        // This is a mockup of a schedule query, assuming a timetable table exists
        // Given we don't have a timetable table yet in the schema, we return teacher assignments
        // with mock times for the mobile view
        return $this->select("
            SELECT ta.*, sec.name as section_name, c.name as class_name, sub.name as subject_name
            FROM teacher_assignments ta
            JOIN sections sec ON ta.section_id = sec.id
            JOIN classes c ON sec.class_id = c.id
            JOIN subjects sub ON ta.subject_id = sub.id
            WHERE ta.teacher_id = ?
        ", [$teacherId]);
    }
}
