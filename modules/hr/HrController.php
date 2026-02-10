<?php
/**
 * HR Controller
 */

class HrController extends Controller {
    
    public function index() {
        $this->requireRole(['super_admin', 'admin']);
        $model = $this->model('hr');
        
        $data = [
            'pageTitle' => 'HR Dashboard',
            'stats' => [
                'employees' => count($model->getEmployees('active')),
                'applicants' => count($model->getApplicants('new')),
                'pending_leaves' => count($model->getLeaveRequests('pending'))
            ]
        ];
        
        $this->view('hr/index', $data);
    }

    /**
     * Employee Directory
     */
    public function directory() {
        $this->requireRole(['super_admin', 'admin']);
        $model = $this->model('hr');
        
        $status = $this->get('status', 'active');
        $employees = $model->getEmployees($status);
        
        $data = [
            'pageTitle' => 'Employee Directory',
            'employees' => $employees,
            'status' => $status
        ];
        
        $this->view('hr/directory', $data);
    }

    /**
     * Add new employee
     */
    public function add() {
        $this->requireRole('super_admin');
        $model = $this->model('hr');
        
        if ($this->isPost()) {
            $data = Security::cleanArray($_POST);
            
            // 1. Create User first
            $staffModel = $this->model('staff');
            $userData = [
                'username' => $data['username'],
                'password' => password_hash($data['password'], PASSWORD_BCRYPT),
                'full_name' => $data['full_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'role' => $data['role'],
                'is_active' => 1
            ];
            
            $userId = $staffModel->addStaff($userData);
            
            if ($userId) {
                // 2. Create Employee Profile
                $empData = [
                    'user_id' => $userId,
                    'employee_id' => $data['employee_id'],
                    'department_id' => $data['department_id'],
                    'designation' => $data['designation'],
                    'joining_date' => $data['joining_date'],
                    'base_salary' => $data['base_salary'],
                    'ssnit_number' => $data['ssnit_number'],
                    'bank_name' => $data['bank_name'],
                    'account_number' => $data['account_number']
                ];
                
                if ($model->addEmployee($empData)) {
                    $this->setFlash('success', 'Employee profile created successfully.');
                    $this->redirect('hr/directory');
                }
            }
            $this->setFlash('error', 'Failed to create employee profile.');
        }
        
        $data = [
            'pageTitle' => 'Add Employee',
            'departments' => $model->getDepartments()
        ];
        
        $this->view('hr/add_employee', $data);
    }

    /**
     * Leave Management
     */
    public function leave() {
        $this->requireRole(['super_admin', 'admin', 'teacher', 'accountant']);
        $model = $this->model('hr');
        $role = Auth::getRole();
        
        if ($this->isPost() && in_array($role, ['super_admin', 'admin'])) {
            $this->model('hr')->updateLeaveStatus($_POST['id'], $_POST['status'], $_SESSION['user_id']);
            $this->setFlash('success', 'Leave request updated.');
            $this->redirect('hr/leave');
        }

        $data = [
            'pageTitle' => 'Leave Management',
            'leaveTypes' => $model->getLeaveTypes()
        ];

        if (in_array($role, ['super_admin', 'admin'])) {
            $data['pendingRequests'] = $model->getLeaveRequests('pending');
            $data['allRequests'] = $model->getLeaveRequests('approved');
        } else {
            $emp = $model->getEmployeeByUserId($_SESSION['user_id']);
            $data['myRequests'] = $model->getStaffLeaveRequests($emp['id']);
        }
        
        $this->view('hr/leave', $data);
    }

    /**
     * Payroll Processing
     */
    public function payroll() {
        $this->requireRole(['super_admin', 'accountant']);
        $model = $this->model('hr');
        $payrollModel = $this->model('payroll');
        
        $month = $this->get('month', date('m'));
        $year = $this->get('year', date('Y'));
        
        if ($this->isPost() && $_POST['action'] == 'generate') {
            $employees = $model->getEmployees('active');
            $count = 0;
            foreach ($employees as $emp) {
                $calc = $payrollModel->calculatePayroll($emp['id'], $month, $year);
                if ($calc) {
                    $payrollModel->savePayroll($calc);
                    $count++;
                }
            }
            $this->setFlash('success', "Payroll generated for $count employees.");
            $this->redirect("hr/payroll?month=$month&year=$year");
        }

        $data = [
            'pageTitle' => 'Payroll Processing',
            'payrolls' => $payrollModel->getPayrolls($month, $year),
            'month' => $month,
            'year' => $year
        ];
        $this->view('hr/payroll', $data);
    }

    /**
     * Recruitment / Applicant Tracking
     */
    public function recruitment() {
        $this->requireRole(['super_admin', 'admin']);
        $model = $this->model('hr');
        
        if ($this->isPost()) {
            $model->updateApplicantStatus($_POST['id'], $_POST['status']);
            $this->setFlash('success', 'Applicant status updated.');
            $this->redirect('hr/recruitment');
        }

        $data = [
            'pageTitle' => 'Recruitment & Applicants',
            'applicants' => $model->getApplicants()
        ];
        
        $this->view('hr/recruitment', $data);
    }

    /**
     * Individual Payslip View
     */
    public function payslip($id) {
        $this->requireRole(['super_admin', 'admin', 'accountant', 'teacher']);
        
        $db = getDbConnection();
        $payroll = $db->query("
            SELECT p.*, u.full_name, e.employee_id as emp_code, d.name as department_name, e.designation, e.bank_name, e.account_number
            FROM payrolls p
            JOIN employees e ON p.employee_id = e.id
            JOIN users u ON e.user_id = u.id
            LEFT JOIN departments d ON e.department_id = d.id
            WHERE p.id = $id
        ")->fetch();

        if (!$payroll) {
            $this->setFlash('error', 'Payslip not found.');
            $this->redirect('hr/payroll');
        }

        // Restrict teachers to only see their own payslips
        if (Auth::getRole() === 'teacher' && $payroll['user_id'] != $_SESSION['user_id']) {
            $this->requireRole('admin');
        }

        $data = [
            'pageTitle' => 'Staff Payslip',
            'p' => $payroll
        ];
        
        $this->view('hr/payslip', $data);
    }
}
