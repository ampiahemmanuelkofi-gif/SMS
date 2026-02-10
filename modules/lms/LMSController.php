<?php
/**
 * LMS Controller
 */

class LMSController extends Controller {
    
    private $lmsModel;
    
    public function __construct() {
        $this->lmsModel = $this->model('lms');
    }
    
    /**
     * LMS Index - Overview Dashboard
     */
    public function index() {
        $this->requireRole(['super_admin', 'admin', 'teacher', 'student', 'parent']);
        
        $data = [
            'pageTitle' => 'Learning Management System',
            'platforms' => $this->lmsModel->getActivePlatforms(),
            'recentContent' => $this->lmsModel->getContentItems(null, 1),
            'quizzes' => $this->lmsModel->getQuizzes()
        ];
        
        $this->view('lms/index', $data);
    }
    
    /**
     * Platform Management (Admin ONLY)
     */
    public function settings() {
        $this->requireRole(['super_admin', 'admin']);
        
        $db = getDbConnection();
        $platforms = $db->query("SELECT * FROM lms_platforms")->fetchAll();
        
        $data = [
            'pageTitle' => 'LMS Integration Settings',
            'platforms' => $platforms
        ];
        
        $this->view('lms/settings', $data);
    }
    
    /**
     * Update platform configuration
     */
    public function update_settings() {
        $this->requireRole(['super_admin', 'admin']);
        
        if ($this->isPost()) {
            $id = $this->post('id');
            $updateData = [
                'client_id' => $this->post('client_id'),
                'client_secret' => $this->post('client_secret'),
                'is_active' => $this->post('is_active') ? 1 : 0,
                'redirect_uri' => $this->post('redirect_uri')
            ];
            
            if ($this->lmsModel->updatePlatform($id, $updateData)) {
                $this->setFlash('success', 'Platform settings updated successfully.');
            } else {
                $this->setFlash('error', 'Failed to update platform settings.');
            }
        }
        
        $this->redirect('lms/settings');
    }
    
    /**
     * Content Repository
     */
    public function repository() {
        $this->requireRole(['super_admin', 'admin', 'teacher', 'student']);
        
        $academicsModel = $this->model('academics');
        $subjects = $academicsModel->getSubjects();
        
        $subjectId = $this->get('subject_id');
        $content = $this->lmsModel->getContentItems($subjectId);
        
        $data = [
            'pageTitle' => 'Learning Content Repository',
            'subjects' => $subjects,
            'content' => $content,
            'filters' => ['subject_id' => $subjectId]
        ];
        
        $this->view('lms/repository', $data);
    }
    
    /**
     * Add new content item
     */
    public function add_content() {
        $this->requireRole(['super_admin', 'admin', 'teacher']);
        
        if ($this->isPost()) {
            $contentData = [
                'subject_id' => $this->post('subject_id'),
                'title' => $this->post('title'),
                'content_type' => $this->post('content_type'),
                'content_value' => $this->post('content_value'),
                'is_public' => $this->post('is_public') ? 1 : 0,
                'created_by' => $_SESSION['user_id']
            ];
            
            if ($this->lmsModel->addContentItem($contentData)) {
                $this->setFlash('success', 'Content added to repository.');
            } else {
                $this->setFlash('error', 'Failed to add content.');
            }
        }
        
        $this->redirect('lms/repository');
    }
    
    /**
     * Quiz Builder
     */
    public function quizzes() {
        $this->requireRole(['super_admin', 'admin', 'teacher']);
        
        $data = [
            'pageTitle' => 'Online Quizzes & Assessments',
            'quizzes' => $this->lmsModel->getQuizzes()
        ];
        
        $this->view('lms/quizzes', $data);
    }
    
    /**
     * Grade Passback (Mockup for now)
     */
    public function sync_grades($platformKey) {
        $this->requireRole(['super_admin', 'admin', 'teacher']);
        
        // Mocking a sync process
        sleep(1);
        
        $this->setFlash('success', "Successfully synced grades from " . ucfirst($platformKey));
        $this->redirect('lms');
    }

    /**
     * Assignment Sync
     */
    public function sync_assignments($platformKey) {
        $this->requireRole(['super_admin', 'admin', 'teacher']);
        
        // Mocking assignment synchronization
        $this->setFlash('info', "Assignment sync initiated for " . ucfirst($platformKey) . "...");
        $this->redirect('lms');
    }
}
