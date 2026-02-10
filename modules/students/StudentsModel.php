<?php
/**
 * Student Model
 */

class StudentsModel extends Model {
    
    /**
     * Get all students with optional filters
     */
    public function getAllStudents($classId = null, $sectionId = null, $search = null) {
        $sql = "SELECT s.*, sec.name as section_name, c.name as class_name 
                FROM students s
                LEFT JOIN sections sec ON s.section_id = sec.id
                LEFT JOIN classes c ON sec.class_id = c.id
                WHERE 1=1";
        
        $params = [];
        
        if ($classId) {
            $sql .= " AND c.id = :class_id";
            $params[':class_id'] = $classId;
        }
        
        if ($sectionId) {
            $sql .= " AND sec.id = :section_id";
            $params[':section_id'] = $sectionId;
        }
        
        if ($search) {
            $sql .= " AND (s.first_name LIKE :search OR s.last_name LIKE :search OR s.student_id LIKE :search)";
            $params[':search'] = "%$search%";
        }
        
        $sql .= " ORDER BY s.created_at DESC";
        
        return $this->select($sql, $params);
    }

    /**
     * Get students by section ID
     */
    public function getStudentsBySection($sectionId) {
        return $this->getAllStudents(null, $sectionId);
    }
    
    /**
     * Get student by ID with category and medical info
     */
    public function getStudentById($id) {
        $sql = "SELECT s.*, sec.name as section_name, c.name as class_name, c.id as class_id, c.level, cat.name as category_name
                FROM students s
                LEFT JOIN sections sec ON s.section_id = sec.id
                LEFT JOIN classes c ON sec.class_id = c.id
                LEFT JOIN student_categories cat ON s.category_id = cat.id
                WHERE s.id = :id";
        
        return $this->selectOne($sql, [':id' => $id]);
    }
    
    /**
     * Get student categories
     */
    public function getCategories() {
        return $this->select("SELECT * FROM student_categories ORDER BY name");
    }
    
    /**
     * Get medical info for a student
     */
    public function getMedicalInfo($studentId) {
        return $this->selectOne("SELECT * FROM student_medical WHERE student_id = :id", [':id' => $studentId]);
    }
    
    /**
     * Save/Update medical info
     */
    public function saveMedicalInfo($data) {
        $exists = $this->getMedicalInfo($data['student_id']);
        if ($exists) {
            $id = $exists['id'];
            unset($data['student_id']);
            return $this->update('student_medical', $data, 'id = :id', [':id' => $id]);
        }
        return $this->insert('student_medical', $data);
    }
    
    /**
     * Get disciplinary records for a student
     */
    public function getDisciplinaryRecords($studentId) {
        return $this->select("
            SELECT d.*, u.full_name as recorded_by_name 
            FROM student_disciplinary d
            JOIN users u ON d.recorded_by = u.id
            WHERE d.student_id = :id 
            ORDER BY d.incident_date DESC", [':id' => $studentId]);
    }
    
    /**
     * Add disciplinary record
     */
    public function addDisciplinaryRecord($data) {
        return $this->insert('student_disciplinary', $data);
    }
    
    /**
     * Get siblings for a student
     */
    public function getSiblings($studentId) {
        return $this->select("
            SELECT s.*, stud.first_name, stud.last_name, stud.student_id as sibling_code, c.name as class_name
            FROM student_siblings s
            JOIN students stud ON s.sibling_student_id = stud.id
            JOIN sections sec ON stud.section_id = sec.id
            JOIN classes c ON sec.class_id = c.id
            WHERE s.student_id = :id", [':id' => $studentId]);
    }
    
    /**
     * Add sibling link
     */
    public function addSibling($studentId, $siblingId, $relationship = 'Sibling') {
        // Add both ways
        $this->insert('student_siblings', [
            'student_id' => $studentId,
            'sibling_student_id' => $siblingId,
            'relationship_type' => $relationship
        ]);
        return $this->insert('student_siblings', [
            'student_id' => $siblingId,
            'sibling_student_id' => $studentId,
            'relationship_type' => $relationship
        ]);
    }
    
    /**
     * Get documents for a student
     */
    public function getDocuments($studentId) {
        return $this->select("SELECT * FROM student_documents WHERE student_id = :id ORDER BY uploaded_at DESC", [':id' => $studentId]);
    }
    
    /**
     * Add student document
     */
    public function addDocument($data) {
        return $this->insert('student_documents', $data);
    }
    
    /**
     * Get custom fields and values for a student
     */
    public function getCustomFieldsWithValues($studentId) {
        return $this->select("
            SELECT f.*, v.field_value 
            FROM student_custom_fields f
            LEFT JOIN student_custom_values v ON f.id = v.field_id AND v.student_id = :id
            ORDER BY f.field_name", [':id' => $studentId]);
    }
    
    /**
     * Save custom field value
     */
    public function saveCustomFieldValue($studentId, $fieldId, $value) {
        $sql = "INSERT INTO student_custom_values (student_id, field_id, field_value) 
                VALUES (:sid, :fid, :val) 
                ON DUPLICATE KEY UPDATE field_value = :val";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':sid' => $studentId, ':fid' => $fieldId, ':val' => $value]);
    }
    
    /**
     * Add new student
     */
    public function addStudent($data) {
        return $this->insert('students', $data);
    }
    
    /**
     * Update student
     */
    public function updateStudent($id, $data) {
        return $this->update('students', $data, 'id = :id', [':id' => $id]);
    }
    
    /**
     * Delete student (soft delete ideally, but hard delete for now if needed)
     */
    public function deleteStudent($id) {
        return $this->delete('students', 'id = :id', [':id' => $id]);
    }
    
    /**
     * Get student count by status
     */
    public function getStudentCount($status = 'active') {
        return $this->count('students', 'status = :status', [':status' => $status]);
    }
    
    /**
     * Search students for AJAX
     */
    public function searchStudents($query) {
        $sql = "SELECT s.id, s.first_name, s.last_name, s.student_id, c.name as class_name 
                FROM students s
                JOIN sections sec ON s.section_id = sec.id
                JOIN classes c ON sec.class_id = c.id
                WHERE s.first_name LIKE :query 
                   OR s.last_name LIKE :query 
                   OR s.student_id LIKE :query
                LIMIT 10";
        return $this->select($sql, [':query' => "%$query%"]);
    }
}
