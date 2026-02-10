<?php
/**
 * API Controller
 * Provides RESTful access to system data
 */

require_once CORE_PATH . '/ApiController.php';

class ApiController extends ApiControllerBase {
    
    /**
     * GET /api/index
     */
    public function index() {
        $this->jsonResponse([
            'status' => 'success',
            'message' => 'Welcome to the School Management System API',
            'version' => '1.0.0'
        ]);
    }

    /**
     * GET /api/students
     */
    public function students() {
        $usersModel = $this->model('users');
        $students = $usersModel->getUsersByRole('student');
        
        $formatted = [];
        foreach ($students as $s) {
            $formatted[] = [
                'id' => $s['id'],
                'username' => $s['username'],
                'full_name' => $s['full_name'],
                'email' => $s['email']
            ];
        }
        
        $this->jsonResponse([
            'status' => 'success',
            'count' => count($formatted),
            'data' => $formatted
        ]);
    }

    /**
     * GET /api/student_profile/ID
     */
    public function student_profile($id) {
        $usersModel = $this->model('users');
        $student = $usersModel->getUserById($id);
        
        if (!$student || $student['role'] !== 'student') {
            $this->jsonResponse(['error' => 'Student not found'], 404);
        }
        
        $this->jsonResponse([
            'status' => 'success',
            'data' => [
                'id' => $student['id'],
                'username' => $student['username'],
                'full_name' => $student['full_name'],
                'email' => $student['email'],
                'phone' => $student['phone']
            ]
        ]);
    }

    /**
     * GET /api/staff
     */
    public function staff() {
        $usersModel = $this->model('users');
        $staff = $usersModel->getUsersByRole('staff');
        
        $formatted = [];
        foreach ($staff as $s) {
            $formatted[] = [
                'id' => $s['id'],
                'username' => $s['username'],
                'full_name' => $s['full_name'],
                'email' => $s['email']
            ];
        }
        
        $this->jsonResponse([
            'status' => 'success',
            'count' => count($formatted),
            'data' => $formatted
        ]);
    }

    /**
     * GET /api/exams
     */
    public function exams() {
        $examModel = $this->model('exam');
        $exams = $examModel->getExams();
        
        $this->jsonResponse([
            'status' => 'success',
            'count' => count($exams),
            'data' => $exams
        ]);
    }
}
