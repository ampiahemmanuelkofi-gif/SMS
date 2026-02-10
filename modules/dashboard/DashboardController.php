<?php
/**
 * Dashboard Controller
 */

class DashboardController extends Controller {
    
    /**
     * Main dashboard (role-based)
     */
    public function index() {
        $role = Auth::getRole();
        
        // Route to role-specific dashboard
        switch ($role) {
            case 'super_admin':
            case 'admin':
                $this->adminDashboard();
                break;
            case 'teacher':
                $this->teacherDashboard();
                break;
            case 'accountant':
                $this->accountantDashboard();
                break;
            case 'parent':
                $this->parentDashboard();
                break;
            default:
                Auth::logout();
                $this->redirect('auth/login');
        }
    }
    
    /**
     * Admin/Super Admin Dashboard
     */
    private function adminDashboard() {
        $db = getDbConnection();
        
        // Get statistics
        $stats = [
            'total_students' => $this->getCount("SELECT COUNT(*) as count FROM students WHERE status = 'active'"),
            'total_teachers' => $this->getCount("SELECT COUNT(*) as count FROM users WHERE role = 'teacher' AND is_active = 1"),
            'total_classes' => $this->getCount("SELECT COUNT(*) as count FROM classes"),
            'total_sections' => $this->getCount("SELECT COUNT(*) as count FROM sections"),
        ];
        
        // Get current term info
        $currentTerm = $this->getCurrentTerm();
        
        // Get recent students
        $recentStudents = $this->select(
            "SELECT s.*, sec.name as section_name, c.name as class_name 
             FROM students s 
             LEFT JOIN sections sec ON s.section_id = sec.id 
             LEFT JOIN classes c ON sec.class_id = c.id 
             WHERE s.status = 'active'
             ORDER BY s.created_at DESC LIMIT 5"
        );
        
        // Get today's attendance summary
        $attendanceToday = $this->select(
            "SELECT status, COUNT(*) as count 
             FROM attendance 
             WHERE date = CURDATE() 
             GROUP BY status"
        );
        
        // Get recent notices
        $notices = $this->select(
            "SELECT n.*, u.full_name as posted_by_name 
             FROM notices n 
             LEFT JOIN users u ON n.posted_by = u.id 
             WHERE (n.expires_at IS NULL OR n.expires_at >= CURDATE())
             AND n.target_audience IN ('all', 'teachers')
             ORDER BY n.created_at DESC LIMIT 5"
        );
        
        $data = [
            'stats' => $stats,
            'currentTerm' => $currentTerm,
            'recentStudents' => $recentStudents,
            'attendanceToday' => $attendanceToday,
            'notices' => $notices
        ];
        
        $this->view('dashboard/admin', $data);
    }
    
    /**
     * Teacher Dashboard
     */
    private function teacherDashboard() {
        $teacherId = Auth::getUserId();
        
        // Get assigned classes
        $assignedClasses = $this->select(
            "SELECT DISTINCT sec.id, sec.name as section_name, c.name as class_name, 
                    c.level, COUNT(DISTINCT s.id) as student_count
             FROM teacher_assignments ta
             JOIN sections sec ON ta.section_id = sec.id
             JOIN classes c ON sec.class_id = c.id
             LEFT JOIN students s ON s.section_id = sec.id AND s.status = 'active'
             WHERE ta.teacher_id = :teacher_id
             GROUP BY sec.id, sec.name, c.name, c.level",
            [':teacher_id' => $teacherId]
        );
        
        // Get pending marks entry
        $pendingMarks = $this->getCount(
            "SELECT COUNT(DISTINCT s.id) as count
             FROM students s
             JOIN sections sec ON s.section_id = sec.id
             JOIN teacher_assignments ta ON ta.section_id = sec.id
             WHERE ta.teacher_id = :teacher_id
             AND s.status = 'active'
             AND s.id NOT IN (
                 SELECT student_id FROM assessments 
                 WHERE term_id = (SELECT id FROM terms WHERE is_current = 1 LIMIT 1)
             )",
            [':teacher_id' => $teacherId]
        );
        
        // Get recent homework
        $homework = $this->select(
            "SELECT h.*, sub.name as subject_name, sec.name as section_name, c.name as class_name
             FROM homework h
             JOIN subjects sub ON h.subject_id = sub.id
             JOIN sections sec ON h.section_id = sec.id
             JOIN classes c ON sec.class_id = c.id
             WHERE h.created_by = :teacher_id
             ORDER BY h.created_at DESC LIMIT 5",
            [':teacher_id' => $teacherId]
        );
        
        $data = [
            'assignedClasses' => $assignedClasses,
            'pendingMarks' => $pendingMarks,
            'homework' => $homework
        ];
        
        $this->view('dashboard/teacher', $data);
    }
    
    /**
     * Accountant Dashboard
     */
    private function accountantDashboard() {
        // Get fee collection stats
        $currentTerm = $this->getCurrentTerm();
        
        $stats = [
            'total_collected' => $this->getSum(
                "SELECT SUM(amount) as total FROM fee_payments WHERE term_id = :term_id",
                [':term_id' => $currentTerm['id']]
            ),
            'total_expected' => $this->getSum(
                "SELECT SUM(fs.amount) as total 
                 FROM fee_structure fs
                 JOIN classes c ON fs.class_id = c.id
                 JOIN sections sec ON sec.class_id = c.id
                 JOIN students s ON s.section_id = sec.id
                 WHERE fs.term_id = :term_id AND s.status = 'active'",
                [':term_id' => $currentTerm['id']]
            ),
            'payments_today' => $this->getCount(
                "SELECT COUNT(*) as count FROM fee_payments WHERE payment_date = CURDATE()"
            )
        ];
        
        // Calculate pending
        $stats['total_pending'] = $stats['total_expected'] - $stats['total_collected'];
        
        // Get recent payments
        $recentPayments = $this->select(
            "SELECT fp.*, s.first_name, s.last_name, s.student_id, u.full_name as received_by_name
             FROM fee_payments fp
             JOIN students s ON fp.student_id = s.id
             JOIN users u ON fp.received_by = u.id
             ORDER BY fp.created_at DESC LIMIT 10"
        );
        
        $data = [
            'stats' => $stats,
            'currentTerm' => $currentTerm,
            'recentPayments' => $recentPayments
        ];
        
        $this->view('dashboard/accountant', $data);
    }
    
    /**
     * Parent Dashboard
     */
    private function parentDashboard() {
        $parentId = Auth::getUserId();
        
        // Get parent's email
        $parentEmail = $this->selectOne("SELECT email FROM users WHERE id = :parent_id", [':parent_id' => $parentId])['email'];
        
        // Get parent's children via email link
        $children = $this->select(
            "SELECT s.*, sec.name as section_name, c.name as class_name
             FROM students s
             LEFT JOIN sections sec ON s.section_id = sec.id
             LEFT JOIN classes c ON sec.class_id = c.id
             WHERE s.guardian_email = :parent_email AND s.status = 'active'",
            [':parent_email' => $parentEmail]
        );
        
        // For each child, get attendance and fee balance
        foreach ($children as &$child) {
            // Attendance percentage
            $attendance = $this->selectOne(
                "SELECT 
                    COUNT(*) as total_days,
                    SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present_days
                 FROM attendance 
                 WHERE student_id = :student_id 
                 AND term_id = (SELECT id FROM terms WHERE is_current = 1 LIMIT 1)",
                [':student_id' => $child['id']]
            );
            
            $child['attendance_percentage'] = $attendance['total_days'] > 0 
                ? round(($attendance['present_days'] / $attendance['total_days']) * 100, 1) 
                : 0;
            
            // Fee balance
            $feeExpected = $this->getSum(
                "SELECT SUM(amount) as total FROM fee_structure fs
                 JOIN sections sec ON sec.class_id = fs.class_id
                 WHERE sec.id = :section_id 
                 AND fs.term_id = (SELECT id FROM terms WHERE is_current = 1 LIMIT 1)",
                [':section_id' => $child['section_id']]
            );
            
            $feePaid = $this->getSum(
                "SELECT SUM(amount) as total FROM fee_payments 
                 WHERE student_id = :student_id 
                 AND term_id = (SELECT id FROM terms WHERE is_current = 1 LIMIT 1)",
                [':student_id' => $child['id']]
            );
            
            $child['fee_balance'] = $feeExpected - $feePaid;
        }
        
        // Get notices for parents
        $notices = $this->select(
            "SELECT * FROM notices 
             WHERE target_audience IN ('all', 'parents')
             AND (expires_at IS NULL OR expires_at >= CURDATE())
             ORDER BY created_at DESC LIMIT 5"
        );
        
        $data = [
            'children' => $children,
            'notices' => $notices
        ];
        
        $this->view('dashboard/parent', $data);
    }
    
    /**
     * Helper: Get count from query
     */
    private function getCount($sql, $params = []) {
        $db = getDbConnection();
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result ? (int)$result['count'] : 0;
    }
    
    /**
     * Helper: Get sum from query
     */
    private function getSum($sql, $params = []) {
        $db = getDbConnection();
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result && $result['total'] ? (float)$result['total'] : 0;
    }
    
    /**
     * Helper: Select records
     */
    private function select($sql, $params = []) {
        $db = getDbConnection();
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    /**
     * Helper: Select one record
     */
    private function selectOne($sql, $params = []) {
        $db = getDbConnection();
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }
    
    /**
     * Helper: Get current term
     */
    private function getCurrentTerm() {
        return $this->selectOne("SELECT * FROM terms WHERE is_current = 1 LIMIT 1");
    }
}
