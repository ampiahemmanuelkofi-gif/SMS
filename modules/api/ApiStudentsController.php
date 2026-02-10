<?php
/**
 * Student API Controller
 * Provides RESTful access to student data
 */

require_once CORE_PATH . '/ApiController.php';

class ApiStudentsController extends ApiController {
    
    /**
     * GET /api/students
     */
    public function index() {
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
     * GET /api/students/profile/ID
     */
    public function profile($id) {
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
}
