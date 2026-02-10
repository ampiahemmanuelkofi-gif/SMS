<?php
/**
 * Communication Controller
 * Manages school announcements and internal messaging
 */

class CommunicationController extends Controller {
    
    public function index() {
        $this->requireRole(['super_admin', 'admin', 'teacher', 'parent', 'student']);
        $model = $this->model('communication');
        
        $audience = $_SESSION['role'];
        if (in_array($audience, ['super_admin', 'admin'])) {
            $audience = 'all';
        }
        
        $data = [
            'pageTitle' => 'Communication Hub',
            'announcements' => $model->getAnnouncements($audience)
        ];
        
        $this->view('communication/announcements', $data);
    }

    public function inbox() {
        $this->requireRole(['super_admin', 'admin', 'teacher', 'parent', 'student']);
        $model = $this->model('communication');
        
        $data = [
            'pageTitle' => 'My Inbox',
            'messages' => $model->getInbox($_SESSION['user_id'])
        ];
        
        $this->view('communication/messages', $data);
    }

    public function send() {
        $this->requireRole(['super_admin', 'admin', 'teacher']);
        $model = $this->model('communication');
        $usersModel = $this->model('users');
        
        if ($this->isPost()) {
            $model->sendMessage([
                'sender_id' => $_SESSION['user_id'],
                'receiver_id' => $_POST['receiver_id'],
                'subject' => $_POST['subject'],
                'message' => $_POST['message']
            ]);
            $this->setFlash('success', "Message sent successfully.");
            $this->redirect('communication/inbox');
        }

        $data = [
            'pageTitle' => 'New Message',
            'users' => $usersModel->getUsersByRole('parent') // For demonstration
        ];
        
        $this->view('communication/send_message', $data);
    }

    public function announcements() {
        $this->requireRole(['super_admin', 'admin']);
        $model = $this->model('communication');
        
        if ($this->isPost()) {
            $model->addAnnouncement([
                'title' => $_POST['title'],
                'content' => $_POST['content'],
                'target_audience' => $_POST['target_audience'],
                'posted_by' => $_SESSION['user_id']
            ]);
            $this->setFlash('success', "Announcement published.");
            $this->redirect('communication');
        }

        $data = [
            'pageTitle' => 'Manage Announcements',
            'announcements' => $model->getAnnouncements('all')
        ];
        
        $this->view('communication/manage_announcements', $data);
    }

    public function bulk() {
        $this->requireRole(['super_admin', 'admin']);
        $model = $this->model('communication');
        
        if ($this->isPost()) {
            // Logic for bulk sending (Simulated)
            $model->logCommunication([
                'type' => $_POST['type'],
                'recipient' => $_POST['target_audience'],
                'subject' => $_POST['subject'],
                'content' => $_POST['content'],
                'status' => 'sent',
                'sent_at' => date('Y-m-d H:i:s')
            ]);
            $this->setFlash('success', "Bulk notification sent to queue.");
            $this->redirect('communication/bulk');
        }

        $data = [
            'pageTitle' => 'Bulk Notifications',
            'templates' => $model->getTemplates(),
            'logs' => $model->getCommLogs()
        ];
        
        $this->view('communication/bulk_sms', $data);
    }
}
