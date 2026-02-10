<?php
/**
 * Attendance Service
 */
class AttendanceService {
    
    protected $db;
    
    public function __construct() {
        $this->db = getDbConnection();
    }
    
    /**
     * Get Attendance Mode (Daily or Subject-wise)
     */
    public function getAttendanceMode() {
        $stmt = $this->db->query("SELECT name FROM attendance_types WHERE is_active = 1 LIMIT 1");
        $type = $stmt->fetchColumn();
        return $type ?: 'daily';
    }
    
    /**
     * Toggle Attendance Mode
     */
    public function setAttendanceMode($mode) {
        $this->db->query("UPDATE attendance_types SET is_active = 0");
        $stmt = $this->db->prepare("UPDATE attendance_types SET is_active = 1 WHERE name = ?");
        $stmt->execute([$mode]);
    }
    
    /**
     * Process Biometric Log
     * Converts raw text/JSON log into attendance record
     */
    public function processBiometricLog($deviceId, $userId, $timestamp, $type) {
        // Logic to map user_id to student/staff and insert into attendance
        // This is a placeholder for actual hardware integration
        $stmt = $this->db->prepare("
            INSERT INTO biometric_logs (device_id, user_id, timestamp, event_type) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$deviceId, $userId, $timestamp, $type]);
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Get Pending Leaves
     */
    public function getPendingLeaves() {
        return $this->db->query("
            SELECT sl.*, s.first_name, s.last_name, c.name as class_name 
            FROM student_leaves sl
            JOIN students s ON sl.student_id = s.id
            JOIN sections sec ON s.section_id = sec.id
            JOIN classes c ON sec.class_id = c.id
            WHERE sl.status = 'pending'
            ORDER BY sl.created_at DESC
        ")->fetchAll();
    }
    
    /**
     * Create Leave Request
     */
    public function requestLeave($studentId, $startDate, $endDate, $reason) {
        $stmt = $this->db->prepare("
            INSERT INTO student_leaves (student_id, start_date, end_date, reason) 
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([$studentId, $startDate, $endDate, $reason]);
    }
    
    /**
     * Approve/Reject Leave
     */
    public function updateLeaveStatus($leaveId, $status, $approverId) {
        $stmt = $this->db->prepare("
            UPDATE student_leaves 
            SET status = ?, approved_by = ? 
            WHERE id = ?
        ");
        $stmt->execute([$status, $approverId, $leaveId]);
        
        // If approved, verify if we should auto-mark attendance as 'excused'
        if ($status === 'approved') {
            $this->markExcusedAttendance($leaveId);
        }
    }
    
    private function markExcusedAttendance($leaveId) {
        $leave = $this->db->query("SELECT * FROM student_leaves WHERE id = $leaveId")->fetch();
        // Loop through dates and mark attendance... (Simplified implementation)
    }
    
    /**
     * Get Attendance Statistics for Dashboard
     */
    public function getStats() {
        $today = date('Y-m-d');
        return [
            'present' => $this->db->query("SELECT COUNT(*) FROM attendance WHERE date = '$today' AND status = 'present'")->fetchColumn(),
            'absent' => $this->db->query("SELECT COUNT(*) FROM attendance WHERE date = '$today' AND status = 'absent'")->fetchColumn(),
            'late' => $this->db->query("SELECT COUNT(*) FROM attendance WHERE date = '$today' AND status = 'late'")->fetchColumn(),
            'leaves' => $this->db->query("SELECT COUNT(*) FROM student_leaves WHERE status = 'approved' AND '$today' BETWEEN start_date AND end_date")->fetchColumn()
        ];
    }
}
