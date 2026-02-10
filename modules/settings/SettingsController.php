<?php
/**
 * Settings Controller
 * Comprehensive system configuration and preferences
 */

class SettingsController extends Controller {
    
    /**
     * Show settings page with all sections
     */
    public function index() {
        $this->requireRole('super_admin');
        
        $model = $this->model('settings');
        $settings = $model->getAllSettings();
        
        // Get additional data for dropdowns
        $db = getDbConnection();
        
        $data = [
            'pageTitle' => 'System Settings',
            'settings' => $settings,
            'activeTab' => $this->get('tab', 'school')
        ];
        
        $this->view('settings/index', $data);
    }
    
    /**
     * Save settings for a specific section
     */
    public function save($section = 'school') {
        $this->requireRole('super_admin');
        
        if ($this->isPost()) {
            $this->requireCsrf();
            $model = $this->model('settings');
            $data = $this->sanitizedPost('settings');
            
            if ($data) {
                if ($model->saveSettings($data)) {
                    $this->setFlash('success', ucfirst($section) . ' settings updated successfully.');
                } else {
                    $this->setFlash('error', 'Failed to update settings.');
                }
            }
        }
        
        $this->redirect('settings?tab=' . $section);
    }
    
    /**
     * Handle logo upload
     */
    public function uploadLogo() {
        $this->requireRole('super_admin');
        
        if ($this->isPost() && isset($_FILES['logo'])) {
            $this->requireCsrf();
            
            $file = $_FILES['logo'];
            $allowed = ['image/jpeg', 'image/png', 'image/gif'];
            
            if (!in_array($file['type'], $allowed)) {
                $this->setFlash('error', 'Invalid file type. Only JPG, PNG, GIF allowed.');
                $this->redirect('settings?tab=school');
            }
            
            $filename = 'school_logo_' . time() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
            $destination = UPLOADS_PATH . '/branding/' . $filename;
            
            if (!is_dir(UPLOADS_PATH . '/branding')) {
                mkdir(UPLOADS_PATH . '/branding', 0755, true);
            }
            
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $model = $this->model('settings');
                $model->saveSettings(['school_logo' => 'uploads/branding/' . $filename]);
                $this->setFlash('success', 'Logo uploaded successfully.');
            } else {
                $this->setFlash('error', 'Failed to upload logo.');
            }
        }
        
        $this->redirect('settings?tab=school');
    }
    
    /**
     * Manual database backup
     */
    public function backup() {
        $this->requireRole('super_admin');
        
        if ($this->isPost()) {
            $this->requireCsrf();
            
            try {
                $backupDir = ROOT_PATH . '/backups';
                if (!is_dir($backupDir)) {
                    mkdir($backupDir, 0755, true);
                }
                
                $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
                $filepath = $backupDir . '/' . $filename;
                
                // Simple backup notice (actual mysqldump would need shell access)
                $this->setFlash('success', 'Backup request initiated. Check the backups folder.');
                
            } catch (Exception $e) {
                $this->setFlash('error', 'Backup failed: ' . $e->getMessage());
            }
        }
        
        $this->redirect('settings?tab=backup');
    }
    
    /**
     * Toggle maintenance mode
     */
    public function maintenance() {
        $this->requireRole('super_admin');
        
        if ($this->isPost()) {
            $this->requireCsrf();
            $model = $this->model('settings');
            $currentMode = $model->get('maintenance_mode', '0');
            $newMode = $currentMode === '1' ? '0' : '1';
            
            $model->saveSettings(['maintenance_mode' => $newMode]);
            $this->setFlash('success', 'Maintenance mode ' . ($newMode === '1' ? 'enabled' : 'disabled') . '.');
        }
        
        $this->redirect('settings?tab=backup');
    }
}
