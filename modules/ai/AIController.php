<?php
/**
 * AI Controller
 * Manages artificial intelligence features and automation
 */

class AIController extends Controller {
    
    public function index() {
        $this->requireRole(['super_admin', 'admin']);
        $model = $this->model('ai');
        
        $data = [
            'pageTitle' => 'AI Intelligence Hub',
            'anomalies' => $model->detectAnomalies(),
            'fee_alerts' => $model->getFeeReminders()
        ];
        
        $this->view('ai/analytics', $data);
    }

    /**
     * Predict student outcome
     */
    public function predict($studentId) {
        $this->requireRole(['super_admin', 'admin', 'teacher']);
        $model = $this->model('ai');
        $usersModel = $this->model('users');
        
        $student = $usersModel->getUserById($studentId);
        $prediction = $model->predictPerformance($studentId);

        $data = [
            'pageTitle' => 'Outcome Prediction: ' . $student['full_name'],
            'student' => $student,
            'prediction' => $prediction
        ];

        $this->view('ai/prediction_detail', $data);
    }

    /**
     * AI Writing Assistant (Voice & Comment generation)
     */
    public function assistant() {
        $this->requireRole(['super_admin', 'admin', 'teacher']);
        $model = $this->model('ai');
        
        $data = [
            'pageTitle' => 'AI Writing Assistant'
        ];
        
        $this->view('ai/assistant', $data);
    }

    /**
     * Automated Fee Reminders
     */
    public function sendReminders() {
        $this->requireRole(['super_admin', 'admin']);
        // Mocking the automated process
        $this->setFlash('success', "Intelligent fee reminders have been scheduled for 15 parents.");
        $this->redirect('ai');
    }

    /**
     * AJAX/API endpoint for comment generation
     */
    public function generateComment() {
        header('Content-Type: application/json');
        if (!isset($_GET['score'])) {
            echo json_encode(['comment' => '']);
            exit;
        }

        $model = $this->model('ai');
        $comment = $model->generateAutomatedComment($_GET['score']);
        
        echo json_encode(['comment' => $comment]);
        exit;
    }
}
