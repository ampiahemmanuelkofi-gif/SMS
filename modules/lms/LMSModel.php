<?php
/**
 * LMS Model
 */

class LMSModel extends Model {
    
    /**
     * Get all active LMS platforms
     */
    public function getActivePlatforms() {
        return $this->select("SELECT * FROM lms_platforms WHERE is_active = 1");
    }
    
    /**
     * Get platform by key
     */
    public function getPlatform($key) {
        return $this->selectOne("SELECT * FROM lms_platforms WHERE platform_key = ?", [$key]);
    }
    
    /**
     * Update platform settings
     */
    public function updatePlatform($id, $data) {
        return $this->update('lms_platforms', $data, 'id = :id', [':id' => $id]);
    }
    
    /**
     * Create or update course mapping
     */
    public function saveCourseMapping($data) {
        $sql = "INSERT INTO lms_course_mapping (section_id, subject_id, platform_id, external_course_id, external_course_name) 
                VALUES (:section_id, :subject_id, :platform_id, :external_course_id, :external_course_name)
                ON DUPLICATE KEY UPDATE 
                external_course_id = VALUES(external_course_id),
                external_course_name = VALUES(external_course_name)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
    
    /**
     * Get course mapping for a section/subject
     */
    public function getCourseMapping($sectionId, $subjectId) {
        return $this->selectOne("
            SELECT m.*, p.name as platform_name, p.platform_key
            FROM lms_course_mapping m
            JOIN lms_platforms p ON m.platform_id = p.id
            WHERE m.section_id = ? AND m.subject_id = ?
        ", [$sectionId, $subjectId]);
    }
    
    /**
     * Sync assignment from LMS
     */
    public function syncAssignment($mappingId, $externalId, $data) {
        $exists = $this->selectOne("SELECT id FROM lms_assignments WHERE course_mapping_id = ? AND external_assignment_id = ?", [$mappingId, $externalId]);
        
        if ($exists) {
            return $this->update('lms_assignments', $data, 'id = :id', [':id' => $exists['id']]);
        }
        
        $data['course_mapping_id'] = $mappingId;
        $data['external_assignment_id'] = $externalId;
        return $this->insert('lms_assignments', $data);
    }
    
    /**
     * Get content repository items
     */
    public function getContentItems($subjectId = null, $isPublic = null) {
        $sql = "SELECT c.*, s.name as subject_name, u.full_name as creator_name 
                FROM lms_content c
                LEFT JOIN subjects s ON c.subject_id = s.id
                LEFT JOIN users u ON c.created_by = u.id
                WHERE 1=1";
        $params = [];
        
        if ($subjectId) {
            $sql .= " AND c.subject_id = ?";
            $params[] = $subjectId;
        }
        
        if ($isPublic !== null) {
            $sql .= " AND c.is_public = ?";
            $params[] = $isPublic ? 1 : 0;
        }
        
        $sql .= " ORDER BY c.created_at DESC";
        return $this->select($sql, $params);
    }
    
    /**
     * Add content item
     */
    public function addContentItem($data) {
        return $this->insert('lms_content', $data);
    }
    
    /**
     * Get online quizzes
     */
    public function getQuizzes($subjectId = null) {
        $sql = "SELECT q.*, s.name as subject_name FROM lms_quizzes q JOIN subjects s ON q.subject_id = s.id";
        $params = [];
        if ($subjectId) {
            $sql .= " WHERE q.subject_id = ?";
            $params[] = $subjectId;
        }
        return $this->select($sql, $params);
    }
}
