<?php
/**
 * iParent Mobile App Controller
 */

class iParentController extends Controller {
    
    public function __construct() {
        $this->requirePermission('support');
    }
    
    /**
     * Parent Dashboard
     */
    public function index() {
        $studentsModel = $this->model('students');
        
        // In a real system, we'd fetch children linked to the parent user
        // Mock: fetch first 2 students as "children"
        $children = $studentsModel->getAllStudents(null, null, null);
        $children = array_slice($children, 0, 2);
        
        // Handle child switching
        if (!isset($_SESSION['selected_child_id']) && !empty($children)) {
            $_SESSION['selected_child_id'] = $children[0]['id'];
        }
        
        if ($this->get('switch_child')) {
            $_SESSION['selected_child_id'] = $this->get('switch_child');
            $this->redirect('iparent');
        }
        
        $selectedChild = null;
        if (isset($_SESSION['selected_child_id'])) {
            $selectedChild = $studentsModel->getStudentById($_SESSION['selected_child_id']);
        }
        
        $data = [
            'pageTitle' => 'iParent Hub',
            'children' => $children,
            'selectedChild' => $selectedChild,
            'layout' => 'mobile',
            'app_mode' => 'parent'
        ];
        
        $this->view('iparent/dashboard', $data);
    }
    
    /**
     * Finances / Fees View
     */
    public function finances() {
        $data = [
            'pageTitle' => 'Fees & Payments',
            'layout' => 'mobile',
            'app_mode' => 'parent'
        ];
        $this->view('iparent/finances', $data);
    }
    
    /**
     * Academic Performance / Grades
     */
    public function grades() {
        $studentsModel = $this->model('students');
        $selectedChild = $studentsModel->getStudentById($_SESSION['selected_child_id']);
        
        $data = [
            'pageTitle' => 'Academic Performance',
            'selectedChild' => $selectedChild,
            'layout' => 'mobile',
            'app_mode' => 'parent'
        ];
        $this->view('iparent/grades', $data);
    }
}
