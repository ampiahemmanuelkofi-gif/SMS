<?php
/**
 * Profile Controller
 * Handles user profile viewing and password changes
 */

class ProfileController extends Controller {
    
    /**
     * Show profile page
     */
    public function index() {
        $this->requireRole(['super_admin', 'admin', 'teacher', 'accountant', 'parent']);
        
        $db = getDbConnection();
        $user = $db->query("SELECT * FROM users WHERE id = " . Auth::getUserId())->fetch();
        
        $data = [
            'pageTitle' => 'My Profile',
            'user' => $user
        ];
        
        $this->view('profile/index', $data);
    }
    
    /**
     * Update password
     */
    public function updatePassword() {
        $this->requireRole(['super_admin', 'admin', 'teacher', 'accountant', 'parent']);
        
        if ($this->isPost()) {
            $data = $_POST;
            $db = getDbConnection();
            
            // Validate CSRF
            if (!Auth::validateCsrfToken($data['csrf_token'])) {
                $this->setFlash('error', 'Invalid security token.');
                $this->redirect('profile');
            }
            
            $user = $db->query("SELECT password FROM users WHERE id = " . Auth::getUserId())->fetch();
            
            // Verify current password
            if (!password_verify($data['current_password'], $user['password'])) {
                $this->setFlash('error', 'Current password is incorrect.');
                $this->redirect('profile');
            }
            
            // Validate new password
            if (strlen($data['new_password']) < 6) {
                $this->setFlash('error', 'New password must be at least 6 characters.');
                $this->redirect('profile');
            }
            
            if ($data['new_password'] !== $data['confirm_password']) {
                $this->setFlash('error', 'Passwords do not match.');
                $this->redirect('profile');
            }
            
            $newHash = password_hash($data['new_password'], PASSWORD_BCRYPT);
            $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$newHash, Auth::getUserId()]);
            
            $this->setFlash('success', 'Password updated successfully.');
            $this->redirect('profile');
        }
    }
}
