<?php
/**
 * iStudent Mobile App Controller
 */

class iStudentController extends Controller {
    
    public function __construct() {
        $this->requireRole(['student', 'super_admin', 'admin']);
    }
    
    /**
     * Student Dashboard
     */
    public function index() {
        $studentsModel = $this->model('students');
        // Mock: use a specific student ID for the student view (usually from session)
        $studentId = 1; 
        $student = $studentsModel->getStudentById($studentId);
        
        $data = [
            'pageTitle' => 'iStudent Hub',
            'student' => $student,
            'layout' => 'mobile',
            'app_mode' => 'student'
        ];
        
        $this->view('istudent/dashboard', $data);
    }
    
    /**
     * Personal Timetable
     */
    public function timetable() {
        $data = [
            'pageTitle' => 'My Timetable',
            'layout' => 'mobile',
            'app_mode' => 'student'
        ];
        $this->view('istudent/timetable', $data);
    }
    
    /**
     * Homework & Assignments
     */
    public function assignments() {
        $data = [
            'pageTitle' => 'Homework',
            'layout' => 'mobile',
            'app_mode' => 'student'
        ];
        $this->view('istudent/assignments', $data);
    }
}
