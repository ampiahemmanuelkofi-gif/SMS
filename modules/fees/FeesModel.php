<?php
/**
 * Fee Model
 */

class FeesModel extends Model {
    
    public function getFeeCategories() {
        return $this->select("SELECT * FROM fee_categories ORDER BY name");
    }
    
    public function getCurrencies() {
        return $this->select("SELECT * FROM currencies ORDER BY is_base DESC, code");
    }
    
    /**
     * Generate Invoices for all active students in a term
     */
    public function generateInvoices($termId) {
        $db = getDbConnection();
        $students = $db->query("SELECT s.id, s.section_id, sec.class_id FROM students s JOIN sections sec ON s.section_id = sec.id WHERE s.status = 'active'")->fetchAll();
        
        $count = 0;
        foreach ($students as $student) {
            // Check if invoice already exists
            $check = $db->query("SELECT id FROM fee_invoices WHERE student_id = {$student['id']} AND term_id = $termId")->fetch();
            if ($check) continue;
            
            // Get applicable fees for this class and term
            $fees = $db->query("SELECT id, amount, category_id, description FROM fee_structure WHERE class_id = {$student['class_id']} AND term_id = $termId")->fetchAll();
            if (empty($fees)) continue;
            
            $totalAmount = array_sum(array_column($fees, 'amount'));
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . str_pad($student['id'], 4, '0', STR_PAD_LEFT);
            
            $stmt = $db->prepare("INSERT INTO fee_invoices (invoice_number, student_id, term_id, total_amount, status, due_date) VALUES (?, ?, ?, ?, 'unpaid', ?)");
            $stmt->execute([$invoiceNumber, $student['id'], $termId, $totalAmount, date('Y-m-t')]); // Due end of month
            $invoiceId = $db->lastInsertId();
            
            // Add items
            $itemStmt = $db->prepare("INSERT INTO invoice_items (invoice_id, category_id, amount, description) VALUES (?, ?, ?, ?)");
            foreach ($fees as $fee) {
                $itemStmt->execute([$invoiceId, $fee['category_id'], $fee['amount'], $fee['description']]);
            }
            $count++;
        }
        return $count;
    }
    
    public function getStudentInvoices($studentId) {
        return $this->select("
            SELECT fi.*, t.name as term_name, ay.name as year_name
            FROM fee_invoices fi
            JOIN terms t ON fi.term_id = t.id
            JOIN academic_years ay ON t.academic_year_id = ay.id
            WHERE fi.student_id = $studentId
            ORDER BY fi.created_at DESC
        ");
    }
    
    public function getDefaulters($termId) {
        return $this->select("
            SELECT s.first_name, s.last_name, s.student_id, fi.total_amount, fi.paid_amount, (fi.total_amount - fi.paid_amount) as balance
            FROM fee_invoices fi
            JOIN students s ON fi.student_id = s.id
            WHERE fi.term_id = $termId AND fi.status != 'paid'
            ORDER BY balance DESC
        ");
    }

    /**
     * Get dashboard statistics
     */
    public function getDashboardStats() {
        $db = getDbConnection();
        
        // Total collected (all time)
        $totalCollected = $db->query("SELECT COALESCE(SUM(amount), 0) FROM fee_payments")->fetchColumn();
        
        // Total outstanding
        $totalOutstanding = $db->query("SELECT COALESCE(SUM(total_amount - paid_amount), 0) FROM fee_invoices WHERE status != 'paid'")->fetchColumn();
        
        // Today's collections
        $todayCollected = $db->query("SELECT COALESCE(SUM(amount), 0) FROM fee_payments WHERE DATE(payment_date) = CURDATE()")->fetchColumn();
        
        // This week's collections
        $weekCollected = $db->query("SELECT COALESCE(SUM(amount), 0) FROM fee_payments WHERE YEARWEEK(payment_date) = YEARWEEK(CURDATE())")->fetchColumn();
        
        // This month's collections
        $monthCollected = $db->query("SELECT COALESCE(SUM(amount), 0) FROM fee_payments WHERE MONTH(payment_date) = MONTH(CURDATE()) AND YEAR(payment_date) = YEAR(CURDATE())")->fetchColumn();
        
        // Total invoices
        $totalInvoices = $db->query("SELECT COUNT(*) FROM fee_invoices")->fetchColumn();
        
        // Paid invoices
        $paidInvoices = $db->query("SELECT COUNT(*) FROM fee_invoices WHERE status = 'paid'")->fetchColumn();
        
        // Collection rate
        $totalBilled = $db->query("SELECT COALESCE(SUM(total_amount), 0) FROM fee_invoices")->fetchColumn();
        $collectionRate = $totalBilled > 0 ? round(($totalCollected / $totalBilled) * 100, 1) : 0;
        
        // Defaulters count
        $defaultersCount = $db->query("SELECT COUNT(DISTINCT student_id) FROM fee_invoices WHERE status != 'paid' AND due_date < CURDATE()")->fetchColumn();
        
        return [
            'total_collected' => $totalCollected,
            'total_outstanding' => $totalOutstanding,
            'today_collected' => $todayCollected,
            'week_collected' => $weekCollected,
            'month_collected' => $monthCollected,
            'total_invoices' => $totalInvoices,
            'paid_invoices' => $paidInvoices,
            'collection_rate' => $collectionRate,
            'defaulters_count' => $defaultersCount
        ];
    }

    /**
     * Get recent payments
     */
    public function getRecentPayments($limit = 10) {
        return $this->select("
            SELECT fp.*, s.first_name, s.last_name, s.student_id as student_code,
                   c.name as class_name, u.full_name as received_by_name
            FROM fee_payments fp
            JOIN students s ON fp.student_id = s.id
            JOIN sections sec ON s.section_id = sec.id
            JOIN classes c ON sec.class_id = c.id
            JOIN users u ON fp.received_by = u.id
            ORDER BY fp.created_at DESC
            LIMIT $limit
        ");
    }

    /**
     * Get collection by category
     */
    public function getCollectionByCategory() {
        return $this->select("
            SELECT fc.name as category, COALESCE(SUM(ii.amount), 0) as total
            FROM fee_categories fc
            LEFT JOIN invoice_items ii ON fc.id = ii.category_id
            LEFT JOIN fee_invoices fi ON ii.invoice_id = fi.id AND fi.status = 'paid'
            GROUP BY fc.id, fc.name
            ORDER BY total DESC
        ");
    }

    /**
     * Get monthly collection trend (last 6 months)
     */
    public function getMonthlyTrend() {
        return $this->select("
            SELECT DATE_FORMAT(payment_date, '%Y-%m') as month,
                   DATE_FORMAT(payment_date, '%b %Y') as month_label,
                   SUM(amount) as total
            FROM fee_payments
            WHERE payment_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
            GROUP BY DATE_FORMAT(payment_date, '%Y-%m'), DATE_FORMAT(payment_date, '%b %Y')
            ORDER BY month ASC
        ");
    }

    /**
     * Add fee structure
     */
    public function addStructure($data) {
        return $this->insert('fee_structure', $data);
    }

    /**
     * Get student balance
     */
    public function getStudentBalance($studentId) {
        $result = $this->selectOne("
            SELECT COALESCE(SUM(total_amount - paid_amount), 0) as balance
            FROM fee_invoices
            WHERE student_id = :id AND status != 'paid'
        ", [':id' => $studentId]);
        return $result ? $result['balance'] : 0;
    }
}
