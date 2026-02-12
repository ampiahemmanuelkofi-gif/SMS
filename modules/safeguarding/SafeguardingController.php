<?php
/**
 * Safeguarding Controller
 */

class SafeguardingController extends Controller {
    
    private $safeguardingModel;
    
    public function __construct() {
        $this->requirePermission('safeguarding');
        $this->safeguardingModel = $this->model('safeguarding');
        $db = getDbConnection();
        $stmt = $db->prepare("SELECT is_safeguarding_lead FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        
        if (!$user || !$user['is_safeguarding_lead']) {
            $this->setFlash('error', 'Strictly Restricted: You do not have Safeguarding Lead authorization.');
            $this->redirect('dashboard');
        }
    }
    
    /**
     * Safeguarding Dashboard
     */
    public function index() {
        $filters = [
            'status' => $this->get('status'),
            'severity' => $this->get('severity')
        ];
        
        $data = [
            'pageTitle' => 'Safeguarding Hub',
            'concerns' => $this->safeguardingModel->getConcerns($filters),
            'filters' => $filters
        ];
        
        $this->safeguardingModel->logAudit(['user_id' => $_SESSION['user_id'], 'action' => 'view', 'detail' => 'Accessed Safeguarding Hub']);
        
        $this->view('safeguarding/dashboard', $data);
    }
    
    /**
     * Log new concern
     */
    public function add() {
        $studentsModel = $this->model('students');
        
        if ($this->isPost()) {
            $concernData = [
                'student_id' => $this->post('student_id'),
                'recorded_by' => $_SESSION['user_id'],
                'severity' => $this->post('severity'),
                'category' => $this->post('category'),
                'title' => $this->post('title'),
                'description' => $this->post('description'),
                'incident_date' => $this->post('incident_date'),
                'status' => 'open'
            ];
            
            $id = $this->safeguardingModel->addConcern($concernData);
            if ($id) {
                $this->safeguardingModel->logAudit([
                    'user_id' => $_SESSION['user_id'], 
                    'concern_id' => $id, 
                    'student_id' => $concernData['student_id'],
                    'action' => 'create', 
                    'detail' => 'Logged new concern: ' . $concernData['title']
                ]);
                $this->setFlash('success', 'Concern logged successfully.');
                $this->redirect('safeguarding/case_details/' . $id);
            }
        }
        
        $data = [
            'pageTitle' => 'Log Safeguarding Concern',
            'students' => $studentsModel->getAllStudents()
        ];
        $this->view('safeguarding/concern_form', $data);
    }
    
    /**
     * View concern detail & chronology
     */
    public function case_details($id) {
        $concern = $this->safeguardingModel->getConcernDetail($id);
        if (!$concern) {
            $this->setFlash('error', 'Concern not found.');
            $this->redirect('safeguarding');
        }
        
        $data = [
            'pageTitle' => 'Concern Case: ' . $concern['student_code'],
            'concern' => $concern,
            'chronology' => $this->safeguardingModel->getStudentChronology($concern['student_id'])
        ];
        
        $this->safeguardingModel->logAudit([
            'user_id' => $_SESSION['user_id'], 
            'concern_id' => $id, 
            'student_id' => $concern['student_id'],
            'action' => 'view', 
            'detail' => 'Viewed concern case details'
        ]);
        
        $this->view('safeguarding/view', $data);
    }
    
    /**
     * Add action/update to chronology
     */
    public function add_action() {
        if ($this->isPost()) {
            $actionData = [
                'concern_id' => $this->post('concern_id'),
                'action_by' => $_SESSION['user_id'],
                'action_type' => $this->post('action_type'),
                'description' => $this->post('description')
            ];
            
            if ($this->safeguardingModel->addAction($actionData)) {
                $this->setFlash('success', 'Action added to chronology.');
            }
        }
        $this->redirect('safeguarding/case_details/' . $this->post('concern_id'));
    }
    
    /**
     * GDPR Tools
     */
    public function gdpr() {
        $this->requireRole('super_admin');
        
        $data = [
            'pageTitle' => 'Data Protection & GDPR Tools'
        ];
        
        $this->view('safeguarding/gdpr_tools', $data);
    }
}
