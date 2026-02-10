<?php
/**
 * Health & Medical Model
 * Handles patient records, clinic logs, and medical alerts
 */

class HealthModel extends Model {
    
    // --- Medical Records (Folders) ---
    public function getMedicalRecord($userId) {
        return $this->selectOne("
            SELECT m.*, u.full_name, u.role
            FROM medical_records m
            JOIN users u ON m.user_id = u.id
            WHERE m.user_id = ?
        ", [$userId]);
    }

    public function createOrUpdateRecord($data) {
        $exists = $this->selectOne("SELECT id FROM medical_records WHERE user_id = ?", [$data['user_id']]);
        if ($exists) {
            $userId = $data['user_id'];
            unset($data['user_id']);
            return $this->update('medical_records', $data, "user_id = :uid", [':uid' => $userId]);
        } else {
            return $this->insert('medical_records', $data);
        }
    }

    // --- Clinic Visits ---
    public function getClinicVisits($userId = null, $status = null) {
        $sql = "SELECT v.*, u.full_name as patient_name, a.full_name as staff_name 
                FROM medical_clinic_visits v
                JOIN users u ON v.user_id = u.id
                JOIN users a ON v.attended_by = a.id
                WHERE 1=1";
        $params = [];
        if ($userId) {
            $sql .= " AND v.user_id = ?";
            $params[] = $userId;
        }
        if ($status) {
            $sql .= " AND v.status = ?";
            $params[] = $status;
        }
        $sql .= " ORDER BY v.visit_date DESC";
        return $this->select($sql, $params);
    }

    public function addClinicVisit($data) {
        return $this->insert('medical_clinic_visits', $data);
    }

    public function getVisitById($id) {
        return $this->selectOne("SELECT * FROM medical_clinic_visits WHERE id = ?", [$id]);
    }

    // --- Medications ---
    public function getMedications($visitId) {
        return $this->select("SELECT * FROM medical_medications WHERE visit_id = ?", [$visitId]);
    }

    public function addMedication($data) {
        return $this->insert('medical_medications', $data);
    }

    // --- Screenings ---
    public function getScreenings($userId = null) {
        $sql = "SELECT s.*, u.full_name 
                FROM medical_screenings s
                JOIN users u ON s.user_id = u.id
                WHERE 1=1";
        $params = [];
        if ($userId) {
            $sql .= " AND s.user_id = ?";
            $params[] = $userId;
        }
        $sql .= " ORDER BY s.screening_date DESC";
        return $this->select($sql, $params);
    }

    public function addScreening($data) {
        return $this->insert('medical_screenings', $data);
    }

    // --- Dashboard & Alerts ---
    public function getCriticalAlerts() {
        return $this->select("
            SELECT user_id, full_name, allergies, chronic_conditions
            FROM medical_records m
            JOIN users u ON m.user_id = u.id
            WHERE allergies != '' OR chronic_conditions != ''
        ");
    }
}
