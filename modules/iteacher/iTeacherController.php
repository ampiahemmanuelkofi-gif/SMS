<?php
/**
 * iTeacher Mobile App Controller
 */

class iTeacherController extends Controller {
    
    public function __construct() {
        $this->requireRole(['teacher', 'super_admin', 'admin']);
    }
    
    /**
     * Mobile Home Dashboard
     */
    public function index() {
        $data = [
            'pageTitle' => 'iTeacher App',
            'layout' => 'mobile' // Signal to use mobile layout
        ];
        
        $this->view('iteacher/dashboard', $data);
    }
    
    /**
     * Mobile Attendance Interface
     */
    public function attendance() {
        $academicsModel = $this->model('academics');
        $studentsModel = $this->model('students');
        
        $sections = $academicsModel->getTeacherSections($_SESSION['user_id']);
        
        $sectionId = $this->get('section_id');
        $students = $sectionId ? $studentsModel->getStudentsBySection($sectionId) : [];
        
        $data = [
            'pageTitle' => 'Mark Attendance',
            'sections' => $sections,
            'students' => $students,
            'sectionId' => $sectionId,
            'layout' => 'mobile'
        ];
        
        $this->view('iteacher/attendance', $data);
    }
    
    /**
     * Mobile Timetable View
     */
    public function timetable() {
        $academicsModel = $this->model('academics');
        
        $data = [
            'pageTitle' => 'My Schedule',
            'schedule' => $academicsModel->getTeacherSchedule($_SESSION['user_id']),
            'layout' => 'mobile'
        ];
        
        $this->view('iteacher/timetable', $data);
    }
    
    /**
     * Mobile Behavior Reporting
     */
    public function behavior() {
        $studentsModel = $this->model('students');
        
        $data = [
            'pageTitle' => 'Report Incident',
            'students' => $studentsModel->getAllStudents(),
            'layout' => 'mobile'
        ];
        
        $this->view('iteacher/behavior', $data);
    }

    /**
     * Submit behavior incident (Mock logic)
     */
    public function submit_incident() {
        if ($this->isPost()) {
            $this->setFlash('success', 'Incident reported successfully.');
        }
        $this->redirect('iteacher/behavior');
    }
}
