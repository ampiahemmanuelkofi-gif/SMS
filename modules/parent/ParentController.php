<?php
/**
 * Parent Controller
 * Handles views for parents to see their children's data
 */

class ParentController extends Controller {
    
    /**
     * Show list of children linked to this parent
     */
    public function children() {
        $this->requireRole('parent');
        
        $db = getDbConnection();
        $parentUserId = $_SESSION['user_id'];
        
        // Get children linked by guardian email (simple matching for MVP)
        $parentEmail = $db->query("SELECT email FROM users WHERE id = $parentUserId")->fetchColumn();
        
        $children = $db->query("
            SELECT s.*, c.name as class_name, sec.name as section_name
            FROM students s
            JOIN sections sec ON s.section_id = sec.id
            JOIN classes c ON sec.class_id = c.id
            WHERE s.guardian_email = '$parentEmail'
        ")->fetchAll();
        
        $data = [
            'pageTitle' => 'My Children',
            'children' => $children
        ];
        
        $this->view('parent/children', $data);
    }
    
    /**
     * View child performance/details
     */
    public function childDetails($studentId) {
        $this->requireRole('parent');
        
        // Verify ownership
        if (!$this->isParentOf($studentId)) {
            $this->setFlash('error', 'Unauthorized access.');
            $this->redirect('parent/children');
        }
        
        $db = getDbConnection();
        $student = $db->query("SELECT s.*, c.name as class_name, sec.name as section_name FROM students s JOIN sections sec ON s.section_id = sec.id JOIN classes c ON sec.class_id = c.id WHERE s.id = $studentId")->fetch();
        
        // Get attendance summary
        $attendance = $db->query("
            SELECT status, COUNT(*) as count 
            FROM attendance 
            WHERE student_id = $studentId 
            GROUP BY status
        ")->fetchAll();
        
        // Get recent assessments
        $assessments = $db->query("
            SELECT a.*, sub.name as subject_name
            FROM assessments a
            JOIN subjects sub ON a.subject_id = sub.id
            WHERE a.student_id = $studentId
            ORDER BY a.created_at DESC
            LIMIT 10
        ")->fetchAll();
        
        $feesModel = $this->model('fees');
        $balance = $feesModel->getStudentBalance($studentId);
        
        $data = [
            'pageTitle' => $student['first_name'] . "'s Profile",
            'student' => $student,
            'attendance' => $attendance,
            'assessments' => $assessments,
            'balance' => $balance
        ];
        
        $this->view('parent/child_view', $data);
    }
    
    /**
     * View payments for children
     */
    public function payments() {
        $this->requireRole('parent');
        
        $db = getDbConnection();
        $parentUserId = $_SESSION['user_id'];
        $parentEmail = $db->query("SELECT email FROM users WHERE id = $parentUserId")->fetchColumn();
        
        $payments = $db->query("
            SELECT fp.*, s.first_name, s.last_name, t.name as term_name
            FROM fee_payments fp
            JOIN students s ON fp.student_id = s.id
            JOIN terms t ON fp.term_id = t.id
            WHERE s.guardian_email = '$parentEmail'
            ORDER BY fp.payment_date DESC
        ")->fetchAll();
        
        $data = [
            'pageTitle' => 'Payment History',
            'payments' => $payments
        ];
        
        $this->view('parent/payments', $data);
    }
    
    /**
     * Utility to check if parent owns a student record
     */
    private function isParentOf($studentId) {
        $db = getDbConnection();
        $parentEmail = $db->query("SELECT email FROM users WHERE id = " . $_SESSION['user_id'])->fetchColumn();
        $studentGuardian = $db->query("SELECT guardian_email FROM students WHERE id = $studentId")->fetchColumn();
        return $parentEmail === $studentGuardian;
    }
}
