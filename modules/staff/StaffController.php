<?php
/**
 * Staff Controller
 */

class StaffController extends Controller {
    
    /**
     * List all staff
     */
    public function index() {
        $this->requireRole(['super_admin', 'admin']);
        
        $model = $this->model('staff');
        $staff = $model->getAllStaff();
        
        $data = [
            'pageTitle' => 'Staff Management',
            'staff' => $staff
        ];
        
        $this->view('staff/list', $data);
    }
    
    /**
     * Add new staff member
     */
    public function add() {
        $this->requireRole('super_admin');
        
        if ($this->isPost()) {
            $data = Security::cleanArray($_POST);
            
            $validator = new Validator();
            $validator->required('full_name', $data['full_name'], 'Full Name')
                     ->required('username', $data['username'], 'Username')
                     ->required('password', $data['password'], 'Password')
                     ->required('role', $data['role'], 'Role');
            
            if ($validator->isValid()) {
                $model = $this->model('staff');
                // Check if username exists
                if ($model->usernameExists($data['username'])) {
                    $this->setFlash('error', 'Username already exists.');
                } else {
                    $userData = [
                        'username' => $data['username'],
                        'password' => password_hash($data['password'], PASSWORD_BCRYPT),
                        'full_name' => $data['full_name'],
                        'role' => $data['role'],
                        'email' => $data['email'],
                        'phone' => $data['phone'],
                        'is_active' => 1
                    ];
                    
                    if ($model->addStaff($userData)) {
                        $this->setFlash('success', 'Staff member added successfully.');
                        $this->redirect('staff');
                    } else {
                        $this->setFlash('error', 'Failed to add staff member.');
                    }
                }
            } else {
                $this->setFlash('error', $validator->getFirstError());
            }
        }
        
        $data = [
            'pageTitle' => 'Add Staff'
        ];
        
        $this->view('staff/add', $data);
    }
    
    /**
     * Edit staff member
     */
    public function edit($id) {
        $this->requireRole('super_admin');
        
        $model = $this->model('staff');
        $user = $model->getStaffById($id);
        if (!$user) {
            $this->setFlash('error', 'Staff member not found.');
            $this->redirect('staff');
        }
        
        if ($this->isPost()) {
            $data = Security::cleanArray($_POST);
            
            $validator = new Validator();
            $validator->required('full_name', $data['full_name'], 'Full Name')
                     ->required('role', $data['role'], 'Role');
            
            if ($validator->isValid()) {
                $updateData = [
                    'full_name' => $data['full_name'],
                    'role' => $data['role'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'is_active' => isset($data['is_active']) ? 1 : 0
                ];
                
                // Update password if provided
                if (!empty($data['password'])) {
                    $updateData['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
                }
                
                if ($model->updateStaff($id, $updateData)) {
                    $this->setFlash('success', 'Staff member updated successfully.');
                    $this->redirect('staff');
                } else {
                    $this->setFlash('error', 'Failed to update staff member.');
                }
            }
        }
        
        $data = [
            'pageTitle' => 'Edit Staff',
            'user' => $user
        ];
        
        $this->view('staff/edit', $data);
    }
}
