<?php
/**
 * Reports Controller
 * Centralized reporting for academics and finances
 */

class ReportsController extends Controller {
    
    /**
     * Default index
     */
    public function index() {
        $this->requireRole(['super_admin', 'admin']);
        
        $data = [
            'pageTitle' => 'System Reports'
        ];
        
        $this->view('reports/index', $data);
    }

    /**
     * Show reports dashboard
     */
    public function dashboard() {
        $this->requireRole(['super_admin', 'admin']);
        
        $data = [
            'pageTitle' => 'System Reports'
        ];
        
        $this->view('reports/index', $data);
    }
    
    /**
     * Financial Collection Report
     */
    public function finance() {
        $this->requireRole(['super_admin', 'admin', 'accountant']);
        
        $db = getDbConnection();
        $startDate = $this->get('start_date', date('Y-m-01'));
        $endDate = $this->get('end_date', date('Y-m-d'));
        
        $payments = $db->query("
            SELECT fp.*, s.first_name, s.last_name, c.name as class_name, u.full_name as received_by_name
            FROM fee_payments fp
            JOIN students s ON fp.student_id = s.id
            JOIN sections sec ON s.section_id = sec.id
            JOIN classes c ON sec.class_id = c.id
            JOIN users u ON fp.received_by = u.id
            WHERE fp.payment_date BETWEEN '$startDate' AND '$endDate'
            ORDER BY fp.payment_date DESC
        ")->fetchAll();
        
        $summary = $db->query("
            SELECT payment_method, SUM(amount) as total
            FROM fee_payments
            WHERE payment_date BETWEEN '$startDate' AND '$endDate'
            GROUP BY payment_method
        ")->fetchAll();
        
        $data = [
            'pageTitle' => 'Financial Reports',
            'payments' => $payments,
            'summary' => $summary,
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate
            ]
        ];
        
        $this->view('reports/finance', $data);
    }
    
    /**
     * Student Statistics Report
     */
    public function students() {
        $this->requireRole(['super_admin', 'admin']);
        
        $db = getDbConnection();
        $stats = [
            'total' => $db->query("SELECT COUNT(*) FROM students")->fetchColumn(),
            'by_gender' => $db->query("SELECT gender, COUNT(*) as count FROM students GROUP BY gender")->fetchAll(),
            'by_class' => $db->query("SELECT c.name, COUNT(s.id) as count FROM classes c LEFT JOIN sections sec ON c.id = sec.class_id LEFT JOIN students s ON sec.id = s.section_id GROUP BY c.id")->fetchAll(),
            'status' => $db->query("SELECT status, COUNT(*) as count FROM students GROUP BY status")->fetchAll()
        ];
        
        $data = [
            'pageTitle' => 'Student Statistics',
            'stats' => $stats
        ];
        
        $this->view('reports/students', $data);
    }

    /**
     * Profit & Loss Report
     */
    public function pnl() {
        $this->requireRole(['super_admin', 'admin', 'accountant']);
        $startDate = $this->get('start_date', date('Y-01-01'));
        $endDate = $this->get('end_date', date('Y-12-31'));
        
        $model = $this->model('accounting');
        $pnl = $model->getPnL($startDate, $endDate);
        
        $data = [
            'pageTitle' => 'Profit & Loss Statement',
            'pnl' => $pnl,
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate
            ]
        ];
        
        $this->view('reports/pnl', $data);
    }
}
