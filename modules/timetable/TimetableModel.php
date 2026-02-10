<?php
/**
 * Timetable Model
 */
class TimetableModel extends Model {
    
    /**
     * Get timetable for a class
     */
    public function getClassTimetable($classId) {
        $sql = "SELECT t.*, s.name as subject_name, u.full_name as teacher_name, r.name as room_name 
                FROM class_timetable t 
                JOIN subjects s ON t.subject_id = s.id 
                LEFT JOIN users u ON t.teacher_id = u.id 
                LEFT JOIN school_rooms r ON t.room_id = r.id 
                WHERE t.class_id = ? 
                ORDER BY FIELD(t.day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), t.start_time";
        return $this->select($sql, [$classId]);
    }
    
    /**
     * Add timetable entry
     */
    public function addEntry($data) {
        return $this->insert('class_timetable', $data);
    }
    
    /**
     * Check for teacher conflict
     */
    public function checkConflict($teacherId, $day, $startTime, $endTime) {
        if (!$teacherId) return false;
        
        $sql = "SELECT id FROM class_timetable 
                WHERE teacher_id = ? 
                AND day_of_week = ? 
                AND (
                    (start_time <= ? AND end_time > ?) OR 
                    (start_time < ? AND end_time >= ?) OR
                    (start_time >= ? AND end_time <= ?)
                )";
        // Time overlap logic
        return $this->selectOne($sql, [$teacherId, $day, $startTime, $startTime, $endTime, $endTime, $startTime, $endTime]);
    }

    /**
     * Check for room conflict
     */
    public function checkRoomConflict($roomId, $day, $startTime, $endTime) {
        if (!$roomId) return false;
        
        $sql = "SELECT id FROM class_timetable 
                WHERE room_id = ? 
                AND day_of_week = ? 
                AND (
                    (start_time <= ? AND end_time > ?) OR 
                    (start_time < ? AND end_time >= ?) OR
                    (start_time >= ? AND end_time <= ?)
                )";
        return $this->selectOne($sql, [$roomId, $day, $startTime, $startTime, $endTime, $endTime, $startTime, $endTime]);
    }
}
