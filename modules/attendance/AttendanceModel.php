<?php
/**
 * Attendance Model
 */

class AttendanceModel extends Model {
    
    /**
     * Get existing attendance records for a section and date
     */
    public function getExistingRecords($sectionId, $date, $subjectId = null) {
        $sql = "SELECT * FROM attendance WHERE section_id = ? AND date = ?";
        $params = [$sectionId, $date];
        
        if ($subjectId) {
            $sql .= " AND subject_id = ?";
            $params[] = $subjectId;
        } else {
            $sql .= " AND subject_id IS NULL";
        }
        
        $records = $this->select($sql, $params);
        $result = [];
        foreach ($records as $row) {
            $result[$row['student_id']] = $row;
        }
        return $result;
    }

    /**
     * Save/Update attendance record
     */
    public function saveAttendance($data) {
        $sql = "SELECT id FROM attendance WHERE student_id = ? AND date = ? AND section_id = ?";
        $params = [$data['student_id'], $data['date'], $data['section_id']];
        
        if ($data['subject_id']) {
            $sql .= " AND subject_id = ?";
            $params[] = $data['subject_id'];
        } else {
            $sql .= " AND subject_id IS NULL";
        }
        
        $existing = $this->select($sql, $params);
        
        if ($existing) {
            return $this->update('attendance', $data, "id = :id", [':id' => $existing[0]['id']]);
        } else {
            return $this->insert('attendance', $data);
        }
    }

    /**
     * Get attendance report for a section and month
     */
    public function getMonthlyReport($sectionId, $month) {
        $sql = "SELECT s.first_name, s.last_name as name, s.id as student_id,
                       SUM(CASE WHEN t.status = 'present' THEN 1 ELSE 0 END) as present,
                       SUM(CASE WHEN t.status = 'absent' THEN 1 ELSE 0 END) as absent,
                       SUM(CASE WHEN t.status = 'late' THEN 1 ELSE 0 END) as late,
                       SUM(CASE WHEN t.status = 'excused' THEN 1 ELSE 0 END) as excused
                FROM students s
                LEFT JOIN attendance t ON s.id = t.student_id AND DATE_FORMAT(t.date, '%Y-%m') = ?
                WHERE s.section_id = ? AND s.status = 'active'
                GROUP BY s.id
                ORDER BY s.last_name, s.first_name";
        return $this->select($sql, [$month, $sectionId]);
    }
}
