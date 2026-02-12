<?php
/**
 * System Administration Controller
 */

class AdminController extends Controller {
    
    protected $adminModel;

    public function __construct() {
        $this->requireRole('super_admin');
        $this->adminModel = $this->model('admin');
    }
    
    /**
     * Admin Dashboard
     */
    public function index() {
        $data = [
            'pageTitle' => 'System Administration',
            'stats' => $this->adminModel->getSystemStats(),
            'recentLogs' => $this->adminModel->getAuditLogs(10)
        ];
        $this->view('admin/dashboard', $data);
    }
    
    /**
     * User Management
     */
    public function users() {
        if ($this->isPost() && $this->post('action') === 'update_status') {
            $this->requireNotDemo();
            $this->adminModel->updateUser($this->post('user_id'), ['status' => $this->post('status')]);
            $this->setFlash('success', 'User status updated.');
            $this->redirect('admin/users');
        }

        $data = [
            'pageTitle' => 'User Management',
            'users' => $this->adminModel->getAllUsers()
        ];
        $this->view('admin/users', $data);
    }
    
    /**
     * Add new user
     */
    public function addUser() {
        if ($this->isPost()) {
            $this->requireNotDemo();
            $data = $this->sanitizedPost();
            
            $validator = new Validator();
            $validator->required('full_name', $data['full_name'], 'Full Name')
                     ->required('username', $data['username'], 'Username')
                     ->required('password', $data['password'], 'Password')
                     ->required('role', $data['role'], 'Role');
            
            if ($validator->isValid()) {
                if ($this->adminModel->usernameExists($data['username'])) {
                    $this->setFlash('error', 'Username already exists.');
                } else {
                    $userData = [
                        'full_name' => $data['full_name'],
                        'username' => $data['username'],
                        'password' => Auth::hashPassword($data['password']),
                        'email' => $data['email'] ?? null,
                        'phone' => $data['phone'] ?? null,
                        'role' => $data['role'],
                        'is_active' => 1
                    ];
                    
                    if ($this->adminModel->insertUser($userData)) {
                        $this->setFlash('success', 'User created successfully.');
                        $this->redirect('admin/users');
                    } else {
                        $this->setFlash('error', 'Failed to create user.');
                    }
                }
            } else {
                $this->setFlash('error', $validator->getFirstError());
            }
        }
        
        $data = [
            'pageTitle' => 'New User',
            'action' => 'Add',
            'user' => null
        ];
        $this->view('admin/user_form', $data);
    }

    /**
     * Edit existing user
     */
    public function editUser($id) {
        $user = $this->adminModel->getUserById($id);
        if (!$user) {
            $this->setFlash('error', 'User not found.');
            $this->redirect('admin/users');
        }

        if ($this->isPost()) {
            $this->requireNotDemo();
            $data = $this->sanitizedPost();
            
            $validator = new Validator();
            $validator->required('full_name', $data['full_name'], 'Full Name')
                     ->required('role', $data['role'], 'Role');
            
            if ($validator->isValid()) {
                if ($this->adminModel->usernameExists($data['username'], $id)) {
                    $this->setFlash('error', 'Username already exists.');
                } else {
                    $userData = [
                        'full_name' => $data['full_name'],
                        'email' => $data['email'] ?? null,
                        'phone' => $data['phone'] ?? null,
                        'role' => $data['role']
                    ];
                    
                    if (!empty($data['password'])) {
                        $userData['password'] = Auth::hashPassword($data['password']);
                    }
                    
                    if ($this->adminModel->updateUser($id, $userData)) {
                        // Update individual user permissions
                        $userPerms = $this->post('user_permissions', []);
                        $modules = $this->getModuleList();
                        
                        // Clear existing overrides if requested
                        if ($this->post('reset_permissions') === '1') {
                            $this->adminModel->clearUserPermissions($id);
                        } else {
                            // If we have any permissions data, save it
                            foreach ($modules as $key => $name) {
                                $canAccess = isset($userPerms[$key]) ? 1 : 0;
                                // We only save if there's an explicit override or if we want to lock the current state
                                // Actually, it's safer to save all submitted states
                                $this->adminModel->updateUserPermission($id, $key, $canAccess);
                            }
                        }

                        $this->setFlash('success', 'User updated successfully.');
                        $this->redirect('admin/users');
                    } else {
                        $this->setFlash('error', 'Failed to update user.');
                    }
                }
            } else {
                $this->setFlash('error', $validator->getFirstError());
            }
        }
        
        $data = [
            'pageTitle' => 'Edit User',
            'action' => 'Edit',
            'user' => $user,
            'modules' => $this->getModuleList(),
            'userPermissions' => $this->adminModel->getUserPermissions($id)
        ];
        $this->view('admin/user_form', $data);
    }
    
    /**
     * Helper to get list of modules
     */
    private function getModuleList() {
        return [
            'students' => 'Student Registry',
            'admissions' => 'Admissions',
            'hr' => 'HR Management',
            'academics' => 'Knowledge Hub',
            'assessments' => 'Assessments',
            'attendance' => 'Attendance',
            'finance' => 'Finance & Fees',
            'inventory' => 'Inventory & Assets',
            'library' => 'Library Hub',
            'transport' => 'Transport Fleet',
            'hostel' => 'Boarding/Hostel',
            'health' => 'Health & Medical',
            'cafeteria' => 'Cafeteria Hub',
            'support' => 'Support Hub',
            'alumni' => 'Alumni Portal',
            'safeguarding' => 'Safeguarding'
        ];
    }
    
    /**
     * System Logs & Security
     */
    public function logs() {
        $data = [
            'pageTitle' => 'Audit Trails',
            'logs' => $this->adminModel->getAuditLogs(100)
        ];
        $this->view('admin/logs', $data);
    }

    /**
     * Role Permissions Management
     */
    public function permissions() {
        if ($this->isPost()) {
            $this->requireNotDemo();
            $permissions = $this->post('permissions', []);
            
            // Loop through all roles and modules to sync state
            $allRoles = array_keys(ROLES);
            $modules = [
                'students', 'admissions', 'hr', 'academics', 'assessments', 'attendance', 
                'finance', 'inventory', 'library', 'transport', 'hostel', 'health', 
                'cafeteria', 'support', 'alumni', 'safeguarding'
            ];

            foreach ($allRoles as $role) {
                if ($role === 'super_admin') continue; // Always has access
                
                foreach ($modules as $module) {
                    $canAccess = isset($permissions[$role][$module]) ? 1 : 0;
                    $this->adminModel->updateRolePermission($role, $module, $canAccess);
                }
            }
            
            $this->setFlash('success', 'Role permissions updated successfully.');
            $this->redirect('admin/permissions');
        }

        $allPermissions = $this->adminModel->getRolePermissions();
        $matrix = [];
        foreach ($allPermissions as $p) {
            $matrix[$p['role']][$p['module_key']] = $p['can_access'];
        }

        $data = [
            'pageTitle' => 'Role Permission Matrix',
            'roles' => ROLES,
            'modules' => [
                'students' => 'Student Registry',
                'admissions' => 'Admissions',
                'hr' => 'HR Management',
                'academics' => 'Knowledge Hub',
                'assessments' => 'Assessments',
                'attendance' => 'Attendance',
                'finance' => 'Finance & Fees',
                'inventory' => 'Inventory & Assets',
                'library' => 'Library Hub',
                'transport' => 'Transport Fleet',
                'hostel' => 'Boarding/Hostel',
                'health' => 'Health & Medical',
                'cafeteria' => 'Cafeteria Hub',
                'support' => 'Support Hub',
                'alumni' => 'Alumni Portal',
                'safeguarding' => 'Safeguarding'
            ],
            'matrix' => $matrix
        ];
        
        $this->view('admin/permissions', $data);
    }
    
    /**
     * Global Settings
     */
    public function settings() {
        $this->redirect('settings');
    }

    /**
     * School Type Profiler (Module Matrix)
     */
    public function profiles() {
        if ($this->isPost()) {
            $this->requireNotDemo();
            $type = $this->post('school_profile');
            if ($this->adminModel->applyProfile($type)) {
                $this->setFlash('success', 'School profile applied. Modules have been updated.');
            } else {
                $this->setFlash('error', 'Failed to apply profile.');
            }
            $this->redirect('admin/profiles');
        }

        $data = [
            'pageTitle' => 'School Type Profiler',
            'currentProfile' => $this->adminModel->getSettings()['school_profile'] ?? 'none'
        ];
        $this->view('admin/profiles', $data);
    }

    /**
     * Maintenance & Backups
     */
    public function maintenance() {
        if ($this->isPost() && $this->post('action') === 'backup') {
            $this->requireNotDemo();
            $filename = $this->adminModel->generateBackup();
            $this->setFlash('success', 'Database backup generated: ' . $filename);
            $this->redirect('admin/maintenance');
        }

        $data = [
            'pageTitle' => 'System Maintenance'
        ];
        $this->view('admin/maintenance', $data);
    }
}
