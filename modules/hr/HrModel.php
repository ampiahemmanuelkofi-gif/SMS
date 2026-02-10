<?php
/**
 * HR Model
 */

class HrModel extends Model {
    
    // Departments
    public function getDepartments() {
        return $this->select("SELECT * FROM departments ORDER BY name");
    }

    // Employees
    public function getEmployees($status = 'active') {
        return $this->select("
            SELECT e.*, u.full_name, u.email as user_email, d.name as department_name 
            FROM employees e
            JOIN users u ON e.user_id = u.id
            LEFT JOIN departments d ON e.department_id = d.id
            WHERE e.status = ?
            ORDER BY u.full_name
        ", [$status]);
    }

    public function getEmployeeById($id) {
        return $this->selectOne("
            SELECT e.*, u.full_name, u.username, u.email as user_email, u.phone as user_phone, d.name as department_name 
            FROM employees e
            JOIN users u ON e.user_id = u.id
            LEFT JOIN departments d ON e.department_id = d.id
            WHERE e.id = ?
        ", [$id]);
    }

    public function getEmployeeByUserId($userId) {
        return $this->selectOne("SELECT * FROM employees WHERE user_id = ?", [$userId]);
    }

    public function addEmployee($data) {
        return $this->insert('employees', $data);
    }

    public function updateEmployee($id, $data) {
        return $this->update('employees', $data, "id = :id", [':id' => $id]);
    }

    // Recruitment
    public function getApplicants($status = null) {
        $sql = "SELECT * FROM applicants";
        $params = [];
        if ($status) {
            $sql .= " WHERE status = ?";
            $params[] = $status;
        }
        $sql .= " ORDER BY applied_at DESC";
        return $this->select($sql, $params);
    }

    public function addApplicant($data) {
        return $this->insert('applicants', $data);
    }

    public function updateApplicantStatus($id, $status) {
        return $this->update('applicants', ['status' => $status], "id = :id", [':id' => $id]);
    }

    // Leave Management
    public function getLeaveTypes() {
        return $this->select("SELECT * FROM leave_types");
    }

    public function getLeaveRequests($status = 'pending') {
        return $this->select("
            SELECT lr.*, u.full_name, lt.name as leave_type_name
            FROM leave_requests lr
            JOIN employees e ON lr.employee_id = e.id
            JOIN users u ON e.user_id = u.id
            JOIN leave_types lt ON lr.leave_type_id = lt.id
            WHERE lr.status = ?
            ORDER BY lr.created_at DESC
        ", [$status]);
    }

    public function getStaffLeaveRequests($employeeId) {
        return $this->select("
            SELECT lr.*, lt.name as leave_type_name
            FROM leave_requests lr
            JOIN leave_types lt ON lr.leave_type_id = lt.id
            WHERE lr.employee_id = ?
            ORDER BY lr.created_at DESC
        ", [$employeeId]);
    }

    public function createLeaveRequest($data) {
        return $this->insert('leave_requests', $data);
    }

    public function updateLeaveStatus($id, $status, $adminId) {
        return $this->update('leave_requests', [
            'status' => $status,
            'approved_by' => $adminId
        ], "id = :id", [':id' => $id]);
    }
}
