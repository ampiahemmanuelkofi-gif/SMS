<?php
/**
 * Hostel Model
 * Handles hostel inventory, allocations, and boarder logistics
 */

class HostelModel extends Model {
    
    // --- Hostel Management ---
    public function getHostels() {
        return $this->select("SELECT * FROM hostel_hostels ORDER BY hostel_name");
    }

    public function getHostelById($id) {
        return $this->selectOne("SELECT * FROM hostel_hostels WHERE id = ?", [$id]);
    }

    public function addHostel($data) {
        return $this->insert('hostel_hostels', $data);
    }

    // --- Room Management ---
    public function getRooms($hostelId = null) {
        $sql = "SELECT r.*, h.hostel_name 
                FROM hostel_rooms r
                JOIN hostel_hostels h ON r.hostel_id = h.id
                WHERE 1=1";
        $params = [];
        if ($hostelId) {
            $sql .= " AND r.hostel_id = ?";
            $params[] = $hostelId;
        }
        $sql .= " ORDER BY h.hostel_name, r.room_number";
        return $this->select($sql, $params);
    }

    public function addRoom($data) {
        return $this->insert('hostel_rooms', $data);
    }

    // --- Bed Management ---
    public function getBeds($roomId = null) {
        $sql = "SELECT b.*, r.room_number, h.hostel_name 
                FROM hostel_beds b
                JOIN hostel_rooms r ON b.room_id = r.id
                JOIN hostel_hostels h ON r.hostel_id = h.id
                WHERE 1=1";
        $params = [];
        if ($roomId) {
            $sql .= " AND b.room_id = ?";
            $params[] = $roomId;
        }
        $sql .= " ORDER BY h.hostel_name, r.room_number, b.bed_number";
        return $this->select($sql, $params);
    }

    public function addBed($data) {
        return $this->insert('hostel_beds', $data);
    }

    public function updateBedStatus($bedId, $status) {
        return $this->update('hostel_beds', ['status' => $status], "id = :id", [':id' => $bedId]);
    }

    // --- Allocations ---
    public function getAllocations($status = 'active') {
        return $this->select("
            SELECT a.*, u.full_name as student_name, b.bed_number, r.room_number, h.hostel_name
            FROM hostel_allocations a
            JOIN users u ON a.student_id = u.id
            JOIN hostel_beds b ON a.bed_id = b.id
            JOIN hostel_rooms r ON b.room_id = r.id
            JOIN hostel_hostels h ON r.hostel_id = h.id
            WHERE a.status = ?
            ORDER BY a.allotted_on DESC
        ", [$status]);
    }

    public function allocateBed($data) {
        $this->db->beginTransaction();
        try {
            $this->insert('hostel_allocations', $data);
            $this->updateBedStatus($data['bed_id'], 'occupied');
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function vacateBed($allocationId, $bedId) {
        $this->db->beginTransaction();
        try {
            $this->update('hostel_allocations', [
                'status' => 'vacated',
                'vacated_on' => date('Y-m-d')
            ], "id = :id", [':id' => $allocationId]);
            $this->updateBedStatus($bedId, 'available');
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    // --- Leave Management ---
    public function getLeaveRequests($status = null) {
        $sql = "SELECT l.*, u.full_name as student_name 
                FROM hostel_leave l
                JOIN users u ON l.student_id = u.id
                WHERE 1=1";
        $params = [];
        if ($status) {
            $sql .= " AND l.status = ?";
            $params[] = $status;
        }
        $sql .= " ORDER BY l.out_date DESC";
        return $this->select($sql, $params);
    }

    public function addLeaveRequest($data) {
        return $this->insert('hostel_leave', $data);
    }

    public function updateLeaveStatus($id, $status, $approvedBy = null) {
        $data = ['status' => $status];
        if ($approvedBy) $data['approved_by'] = $approvedBy;
        return $this->update('hostel_leave', $data, "id = :id", [':id' => $id]);
    }

    // --- Incidents ---
    public function getIncidents() {
        return $this->select("
            SELECT i.*, u.full_name as student_name, r.full_name as reporter_name
            FROM hostel_incidents i
            JOIN users u ON i.student_id = u.id
            JOIN users r ON i.reported_by = r.id
            ORDER BY i.incident_date DESC
        ");
    }

    public function addIncident($data) {
        return $this->insert('hostel_incidents', $data);
    }
}
