<?php
/**
 * Alumni Model
 */

class AlumniModel extends Model {
    
    /**
     * Get all alumni (graduated students)
     */
    public function getAlumni($search = null) {
        $sql = "SELECT s.*, sec.name as section_name, c.name as class_name 
                FROM students s
                LEFT JOIN sections sec ON s.section_id = sec.id
                LEFT JOIN classes c ON sec.class_id = c.id
                WHERE s.status = 'graduated'";
        
        $params = [];
        
        if ($search) {
            $sql .= " AND (s.first_name LIKE :search OR s.last_name LIKE :search OR s.student_id LIKE :search)";
            $params[':search'] = "%$search%";
        }
        
        $sql .= " ORDER BY s.last_name ASC, s.first_name ASC";
        
        return $this->select($sql, $params);
    }

    /**
     * Get alumni events
     */
    public function getEvents($limit = 10) {
        $sql = "SELECT e.*, u.full_name as created_by_name 
                FROM alumni_events e
                LEFT JOIN users u ON e.created_by = u.id
                ORDER BY e.event_date DESC LIMIT $limit";
        return $this->select($sql);
    }

    /**
     * Add new alumni event
     */
    public function addEvent($data) {
        return $this->insert('alumni_events', $data);
    }

    /**
     * Get alumni donations
     */
    public function getDonations($limit = 10) {
        $sql = "SELECT d.*, s.first_name, s.last_name, s.student_id, u.full_name as received_by_name
                FROM alumni_donations d
                JOIN students s ON d.student_id = s.id
                LEFT JOIN users u ON d.received_by = u.id
                ORDER BY d.donation_date DESC LIMIT $limit";
        return $this->select($sql);
    }

    /**
     * Add alumni donation
     */
    public function addDonation($data) {
        return $this->insert('alumni_donations', $data);
    }
}
