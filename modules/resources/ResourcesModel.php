<?php
/**
 * Resources Model
 */
class ResourcesModel extends Model {
    
    // ROOMS
    public function getRooms() {
        return $this->select("SELECT * FROM school_rooms ORDER BY name ASC");
    }
    
    public function addRoom($data) {
        return $this->insert('school_rooms', $data);
    }
    
    // LESSON PLANS
    public function getLessonPlans($teacherId = null) {
        $sql = "SELECT lp.*, u.full_name as teacher_name, s.name as subject_name, c.name as class_name 
                FROM lesson_plans lp 
                JOIN users u ON lp.teacher_id = u.id 
                JOIN subjects s ON lp.subject_id = s.id 
                JOIN classes c ON lp.class_id = c.id";
        
        $params = [];
        if ($teacherId) {
            $sql .= " WHERE lp.teacher_id = ?";
            $params[] = $teacherId;
        }
        
        $sql .= " ORDER BY lp.created_at DESC";
        return $this->select($sql, $params);
    }
}
