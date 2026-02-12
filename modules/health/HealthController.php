<?php
/**
 * Health & Medical Controller
 * Manages clinic visits, medical histories, and health alerts
 */

class HealthController extends Controller {
    protected $healthModel;

    public function __construct() {
        $this->requirePermission('health');
        $this->healthModel = $this->model('health');
    }

    public function index() {
        $model = $this->healthModel;
        
        $data = [
            'pageTitle' => 'Medical Dashboard',
            'recent_visits' => $model->getClinicVisits(null, null),
            'critical_alerts' => $model->getCriticalAlerts(),
            'total_visits_today' => count($model->getClinicVisits(null, 'completed')) // Simple mock count
        ];
        
        $this->view('health/dashboard', $data);
    }

    public function visits() {
        $model = $this->healthModel;
        $usersModel = $this->model('users');
        
        if ($this->isPost()) {
            $visitData = [
                'user_id' => $_POST['user_id'],
                'visit_date' => date('Y-m-d H:i:s'),
                'symptoms' => $_POST['symptoms'],
                'diagnosis' => $_POST['diagnosis'],
                'treatment' => $_POST['treatment'],
                'temperature' => $_POST['temperature'],
                'weight' => $_POST['weight'],
                'blood_pressure' => $_POST['blood_pressure'],
                'attended_by' => $_SESSION['user_id'],
                'status' => 'completed'
            ];
            
            $visitId = $model->addClinicVisit($visitData);
            
            if (!empty($_POST['medication'])) {
                $model->addMedication([
                    'visit_id' => $visitId,
                    'medication_name' => $_POST['medication'],
                    'dosage' => $_POST['dosage'],
                    'instructions' => $_POST['instructions']
                ]);
            }
            
            $this->setFlash('success', "Clinic visit logged successfully.");
            $this->redirect('health/visits');
        }

        $data = [
            'pageTitle' => 'Daily Clinic Log',
            'visits' => $model->getClinicVisits(),
            'users' => $usersModel->getUsersByRole('student') // Focus on students for now
        ];
        
        $this->view('health/clinic_log', $data);
    }

    
    public function records() {
        $model = $this->healthModel;
        $usersModel = $this->model('users');
        
        $userId = $_GET['user_id'] ?? null;
        
        if ($this->isPost()) {
            $model->createOrUpdateRecord([
                'user_id' => $_POST['user_id'],
                'blood_group' => $_POST['blood_group'],
                'allergies' => $_POST['allergies'],
                'chronic_conditions' => $_POST['chronic_conditions'],
                'emergency_contact_name' => $_POST['emergency_name'],
                'emergency_contact_phone' => $_POST['emergency_phone']
            ]);
            $this->setFlash('success', "Medical record updated.");
            $this->redirect('health/records?user_id=' . $_POST['user_id']);
        }

        $data = [
            'pageTitle' => 'Medical Folders',
            'users' => $usersModel->getUsersByRole('student'),
            'current_record' => $userId ? $model->getMedicalRecord($userId) : null
        ];
        
        $this->view('health/medical_records', $data);
    }

    public function screenings() {
        $model = $this->healthModel;
        
        if ($this->isPost()) {
            $model->addScreening([
                'user_id' => $_POST['user_id'],
                'screening_type' => $_POST['screening_type'],
                'screening_date' => $_POST['screening_date'],
                'results' => $_POST['results'],
                'recommendations' => $_POST['recommendations'],
                'conducted_by' => $_POST['conducted_by']
            ]);
            $this->setFlash('success', "Health screening recorded.");
            $this->redirect('health/screenings');
        }

        $data = [
            'pageTitle' => 'Health Screenings',
            'screenings' => $model->getScreenings()
        ];
        
        $this->view('health/screenings', $data);
    }
}
