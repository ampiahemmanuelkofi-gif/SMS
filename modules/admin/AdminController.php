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
