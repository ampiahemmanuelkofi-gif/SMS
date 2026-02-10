<?php
/**
 * Portal Controller
 * specialized Dashboards for Students, Parents, and Staff
 */

class PortalController extends Controller {
    
    public function student() {
        $this->requireRole('student');
        
        // Mock data aggregation for portal
        $data = [
            'pageTitle' => 'Student Dashboard',
            'attendance_rate' => '95%',
            'recent_grades' => [
                ['subject' => 'Mathematics', 'grade' => 'A', 'date' => '2026-02-01'],
                ['subject' => 'Science', 'grade' => 'B+', 'date' => '2026-01-28']
            ],
            'library_books' => [
                ['title' => 'Biology Basics', 'due_date' => '2026-02-15']
            ],
            'announcements' => $this->model('communication')->getAnnouncements('students')
        ];
        
        $this->view('portals/student_dashboard', $data);
    }

    public function parent() {
        $this->requireRole('parent');
        
        // Mock data aggregation for parent portal
        $data = [
            'pageTitle' => 'Parent Dashboard',
            'children' => [
                ['name' => 'John Doe', 'class' => 'Grade 10', 'attendance' => '98%'],
                ['name' => 'Jane Doe', 'class' => 'Grade 8', 'attendance' => '92%']
            ],
            'fee_balance' => 'GHS 450.00',
            'recent_payments' => [
                ['amount' => '500.00', 'date' => '2026-01-15', 'status' => 'confirmed']
            ],
            'messages' => $this->model('communication')->getInbox($_SESSION['user_id'])
        ];
        
        $this->view('portals/parent_dashboard', $data);
    }

    public function staff() {
        $this->requireRole(['teacher', 'staff']);
        
        // Mock data aggregation for staff portal
        $data = [
            'pageTitle' => 'Staff Dashboard',
            'classes' => ['Grade 10A', 'Grade 9B'],
            'recent_payroll' => [
                ['month' => 'January 2026', 'net' => '2,450.00', 'status' => 'Paid']
            ],
            'announcements' => $this->model('communication')->getAnnouncements('staff')
        ];
        
        $this->view('portals/staff_dashboard', $data);
    }
}
