<?php
/**
 * Notices Controller
 */

class NoticesController extends Controller {
    
    public function index() {
        $this->requireRole(['super_admin', 'admin', 'teacher', 'accountant', 'parent']);
        
        $model = $this->model('notices');
        $role = Auth::getRole();
        
        // Map roles to audience
        $audienceMap = [
            'super_admin' => 'all',
            'admin' => 'all',
            'teacher' => 'teachers',
            'parent' => 'parents',
            'accountant' => 'all'
        ];
        $audience = $audienceMap[$role] ?? 'all';
        
        $notices = $model->getActiveNotices($audience);
        
        $data = [
            'pageTitle' => 'Notice Board',
            'notices' => $notices
        ];
        
        $this->view('notices/board', $data);
    }
    
    public function add() {
        $this->requireRole(['super_admin', 'admin']);
        
        if ($this->isPost()) {
            $this->requireCsrf();
            $data = $this->sanitizedPost();
            $model = $this->model('notices');
            
            $saveData = [
                'title' => $data['title'],
                'content' => $data['content'],
                'target_audience' => $data['audience'],
                'posted_by' => Auth::getUserId(),
                'expires_at' => !empty($data['expires_at']) ? $data['expires_at'] : null
            ];
            
            if ($model->addNotice($saveData)) {
                $this->setFlash('success', 'Notice published successfully.');
                $this->redirect('notices');
            } else {
                $this->setFlash('error', 'Failed to publish notice.');
            }
        }
        
        // Show the add form
        $data = [
            'pageTitle' => 'Post New Notice'
        ];
        
        $this->view('notices/add', $data);
    }
}
