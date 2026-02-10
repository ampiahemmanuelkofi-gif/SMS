<?php
/**
 * Fee Controller
 */

class FeesController extends Controller {
    
    /**
     * Finance Dashboard
     */
    public function index() {
        $this->requireRole(['super_admin', 'admin', 'accountant']);
        
        $model = $this->model('fees');
        $stats = $model->getDashboardStats();
        $recentPayments = $model->getRecentPayments(8);
        $categoryData = $model->getCollectionByCategory();
        $monthlyTrend = $model->getMonthlyTrend();
        
        $data = [
            'pageTitle' => 'Finance Dashboard',
            'stats' => $stats,
            'recentPayments' => $recentPayments,
            'categoryData' => $categoryData,
            'monthlyTrend' => $monthlyTrend
        ];
        
        $this->view('fees/dashboard', $data);
    }

    /**
     * Fee structure list
     */
    public function structure() {
        $this->requireRole(['super_admin', 'admin', 'accountant']);
        
        $model = $this->model('fees');
        $structures = $this->selectFeeStructure();
        $categories = $model->getFeeCategories();
        
        $db = getDbConnection();
        $academicsModel = $this->model('academics');
        $classes = $academicsModel->getClasses();
        $terms = $db->query("SELECT t.*, ay.name as year_name FROM terms t JOIN academic_years ay ON t.academic_year_id = ay.id WHERE t.is_current = 1 OR ay.is_current = 1")->fetchAll();
        
        $data = [
            'pageTitle' => 'Fee Structure',
            'structures' => $structures,
            'categories' => $categories,
            'classes' => $classes,
            'terms' => $terms
        ];
        
        $this->view('fees/structure', $data);
    }

    private function selectFeeStructure() {
        $db = getDbConnection();
        return $db->query("
            SELECT fs.*, c.name as class_name, t.name as term_name, ay.name as year_name, fc.name as category_name
            FROM fee_structure fs
            JOIN classes c ON fs.class_id = c.id
            JOIN terms t ON fs.term_id = t.id
            JOIN academic_years ay ON t.academic_year_id = ay.id
            LEFT JOIN fee_categories fc ON fs.category_id = fc.id
            ORDER BY ay.start_date DESC, t.name, c.name
        ")->fetchAll();
    }
    
    /**
     * Categories Management
     */
    public function categories() {
        $this->requireRole(['super_admin', 'admin', 'accountant']);
        $model = $this->model('fees');
        $categories = $model->getFeeCategories();
        $this->view('fees/categories', ['categories' => $categories, 'pageTitle' => 'Fee Categories']);
    }

    public function saveCategory() {
        $this->requireRole(['super_admin', 'admin', 'accountant']);
        if ($this->isPost()) {
            $db = getDbConnection();
            $stmt = $db->prepare("INSERT INTO fee_categories (name, description) VALUES (?, ?)");
            $stmt->execute([$_POST['name'], $_POST['description']]);
            $this->setFlash('success', 'Category added.');
        }
        $this->redirect('fees/categories');
    }

    /**
     * Invoices Management
     */
    public function invoices() {
        $this->requireRole(['super_admin', 'admin', 'accountant']);
        $db = getDbConnection();
        $terms = $db->query("SELECT t.*, ay.name as year_name FROM terms t JOIN academic_years ay ON t.academic_year_id = ay.id WHERE t.is_current = 1 OR ay.is_current = 1")->fetchAll();
        
        $termId = $this->get('term_id') ?? ($terms[0]['id'] ?? 0);
        $invoices = [];
        if ($termId) {
            $invoices = $db->query("SELECT fi.*, s.first_name, s.last_name, s.student_id FROM fee_invoices fi JOIN students s ON fi.student_id = s.id WHERE fi.term_id = $termId ORDER BY fi.created_at DESC")->fetchAll();
        }

        $this->view('fees/invoices', [
            'pageTitle' => 'Fee Invoices',
            'invoices' => $invoices,
            'terms' => $terms,
            'termId' => $termId
        ]);
    }

    public function generateInvoices() {
        $this->requireRole(['super_admin', 'admin', 'accountant']);
        if ($this->isPost()) {
            $termId = $_POST['term_id'];
            $model = $this->model('fees');
            $count = $model->generateInvoices($termId);
            
            // Record to ledger: Debit A/R (2), Credit Fee Income (3)
            // Note: In a real system we'd calculate the exact total. 
            // For now, we'll record a summary entry or do it per invoice in a real loop.
            // Simplified: Record summary entry for the batch
            $db = getDbConnection();
            $total = $db->query("SELECT SUM(total_amount) FROM fee_invoices WHERE term_id = $termId")->fetchColumn();
            
            $accounting = $this->model('accounting');
            $accounting->recordTransaction([
                ['account_id' => 2, 'debit' => $total, 'description' => "Batch Invoice Generation - Term $termId"],
                ['account_id' => 3, 'credit' => $total, 'description' => "Batch Invoice Generation - Term $termId"]
            ]);

            $this->setFlash('success', "$count invoices generated and ledger updated.");
        }
        $this->redirect('fees/invoices');
    }

    /**
     * Defaulters List
     */
    public function defaulters() {
        $this->requireRole(['super_admin', 'admin', 'accountant']);
        $db = getDbConnection();
        $terms = $db->query("SELECT t.*, ay.name as year_name FROM terms t JOIN academic_years ay ON t.academic_year_id = ay.id WHERE t.is_current = 1 OR ay.is_current = 1")->fetchAll();
        $termId = $this->get('term_id') ?? ($terms[0]['id'] ?? 0);
        
        $model = $this->model('fees');
        $defaulters = $model->getDefaulters($termId);

        $this->view('fees/defaulters', [
            'pageTitle' => 'Fee Defaulters',
            'defaulters' => $defaulters,
            'terms' => $terms,
            'termId' => $termId
        ]);
    }
    
    /**
     * Add fee structure
     */
    public function addStructure() {
        $this->requireRole(['super_admin', 'admin', 'accountant']);
        
        if ($this->isPost()) {
            $data = Security::cleanArray($_POST);
            $model = $this->model('fees');
            
            $saveData = [
                'class_id' => $data['class_id'],
                'term_id' => $data['term_id'],
                'category_id' => $data['category_id'],
                'amount' => $data['amount'],
                'description' => $data['description']
            ];
            
            if ($model->addStructure($saveData)) {
                $this->setFlash('success', 'Fee structure added successfully.');
            } else {
                $this->setFlash('error', 'Failed to add fee structure.');
            }
        }
        
        $this->redirect('fees');
    }
    
    /**
     * Record payment
     */
    public function collect($studentId = null) {
        $this->requireRole(['super_admin', 'admin', 'accountant']);
        
        $student = null;
        $balance = null;
        if ($studentId) {
            $studentModel = $this->model('students');
            $student = $studentModel->getStudentById($studentId);
            
            $feesModel = $this->model('fees');
            $balance = $feesModel->getStudentBalance($studentId);
        }
        
        $data = [
            'pageTitle' => 'Collect Fees',
            'student' => $student,
            'balance' => $balance
        ];
        
        $this->view('fees/collect', $data);
    }
    
    /**
     * Handle payment submission
     */
    public function savePayment() {
        $this->requireRole(['super_admin', 'admin', 'accountant']);
        
        if ($this->isPost()) {
            $db = getDbConnection();
            $data = Security::cleanArray($_POST);
            
            $stmt = $db->prepare("INSERT INTO fee_payments (student_id, amount, payment_date, payment_method, reference_number, received_by, term_id, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['student_id'],
                $data['amount'],
                $data['payment_date'],
                $data['payment_method'],
                $data['reference_number'],
                $_SESSION['user_id'],
                $data['term_id'],
                $data['notes']
            ]);
            
            $paymentId = $db->lastInsertId();

            // Ledger Entry: Debit Cash (1), Credit A/R (2)
            $accounting = $this->model('accounting');
            $accounting->recordTransaction([
                ['account_id' => 1, 'debit' => $data['amount'], 'description' => "Fee Payment Rec: $paymentId - Student: " . $data['student_id']],
                ['account_id' => 2, 'credit' => $data['amount'], 'description' => "Fee Payment Rec: $paymentId - Student: " . $data['student_id']]
            ]);

            // Update Invoice Paid Amount (Simplified: Apply to oldest unpaid invoice)
            $db->exec("UPDATE fee_invoices SET paid_amount = paid_amount + {$data['amount']}, status = IF(paid_amount >= total_amount, 'paid', 'partially_paid') WHERE student_id = {$data['student_id']} AND status != 'paid' ORDER BY created_at LIMIT 1");

            $this->setFlash('success', 'Payment recorded and ledger updated.');
            $this->redirect('fees/receipt/' . $paymentId);
        }
    }
    
    /**
     * View/Print Receipt
     */
    public function receipt($id) {
        $this->requireRole(['super_admin', 'admin', 'accountant', 'parent']);
        
        $db = getDbConnection();
        $payment = $db->query("
            SELECT fp.*, s.first_name, s.last_name as student_name, s.student_id, 
                   c.name as class_name, t.name as term_name, ay.name as year_name,
                   u.full_name as received_by_name
            FROM fee_payments fp
            JOIN students s ON fp.student_id = s.id
            JOIN sections sec ON s.section_id = sec.id
            JOIN classes c ON sec.class_id = c.id
            JOIN terms t ON fp.term_id = t.id
            JOIN academic_years ay ON t.academic_year_id = ay.id
            JOIN users u ON fp.received_by = u.id
            WHERE fp.id = $id
        ")->fetch();
        
        if (!$payment) {
            $this->setFlash('error', 'Receipt not found.');
            $this->redirect('fees');
        }
        
        $this->view('fees/receipt', ['payment' => $payment]);
    }
}
