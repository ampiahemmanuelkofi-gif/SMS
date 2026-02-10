<?php
/**
 * Admissions Controller
 */
class AdmissionsController extends Controller {
    
    /**
     * Admin Dashboard for Admissions
     */
    public function index() {
        $this->requireRole(['super_admin', 'admin', 'principal']);
        
        $model = $this->model('admissions');
        
        // Handle Filters
        $status = isset($_GET['status']) ? $_GET['status'] : null;
        $applications = $model->getApplications($status);
        $stats = $model->getStats();
        
        $data = [
            'pageTitle' => 'Admissions Management',
            'applications' => $applications,
            'stats' => $stats,
            'filter_status' => $status
        ];
        
        $this->view('admissions/dashboard', $data);
    }
    
    /**
     * Public Application Form
     */
    public function apply() {
        // Public access - no role requirement
        
        $academicsModel = $this->model('academics');
        $classes = $academicsModel->getClasses();
        
        if ($this->isPost()) {
            $data = Security::cleanArray($_POST);
            $model = $this->model('admissions');
            
            // Basic validation
            if (empty($data['first_name']) || empty($data['last_name']) || empty($data['class_id'])) {
                $this->setFlash('error', 'Please fill in all required fields.');
            } else {
                $appData = [
                    'application_number' => $model->generateApplicationNumber(),
                    'academic_year_id' => 1, // Defaulting to 1 for now, ideally fetch current
                    'class_id' => $data['class_id'],
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'date_of_birth' => $data['date_of_birth'],
                    'gender' => $data['gender'],
                    'guardian_name' => $data['guardian_name'],
                    'guardian_phone' => $data['guardian_phone'],
                    'guardian_email' => $data['guardian_email'],
                    'address' => $data['address'],
                    'status' => 'pending'
                ];
                
                if ($model->createApplication($appData)) {
                    // Handle file uploads if any (implementation needed)
                    $this->setFlash('success', 'Application submitted successfully! Your Ref: ' . $appData['application_number']);
                    $this->redirect('admissions/success'); // Create a success page or redirect to home with flash
                    return;
                } else {
                    $this->setFlash('error', 'Failed to submit application. Please try again.');
                }
            }
        }
        
        $data = [
            'pageTitle' => 'Apply for Admission',
            'classes' => $classes,
            'layout' => 'public' // Separate layout for public pages if exists, or handle in view
        ];
        
        // For now using standard view, assuming header handles public/private state
        $this->view('admissions/apply', $data);
    }
    
    /**
     * View Application Details
     */
    public function view_application($id) {
        $this->requireRole(['super_admin', 'admin', 'principal']);
        
        $model = $this->model('admissions');
        $application = $model->getApplication($id);
        
        if (!$application) {
            $this->setFlash('error', 'Application not found.');
            $this->redirect('admissions');
        }
        
        $documents = $model->getDocuments($id);
        
        $data = [
            'pageTitle' => 'Application Details',
            'application' => $application,
            'documents' => $documents
        ];
        
        $this->view('admissions/view', $data);
    }
    
    /**
     * Update Status
     */
    public function update_status() {
        $this->requireRole(['super_admin', 'admin']);
        
        if ($this->isPost()) {
            $id = $_POST['application_id'];
            $status = $_POST['status'];
            
            $model = $this->model('admissions');
            if ($model->updateStatus($id, $status)) {
                $this->setFlash('success', 'Application status updated.');
            } else {
                $this->setFlash('error', 'Failed to update status.');
            }
            
            $this->redirect('admissions/view_application/' . $id);
        }
    }
    
    /**
     * Schedule Interview
     */
    public function schedule_interview() {
        $this->requireRole(['super_admin', 'admin', 'principal']);
        
        if ($this->isPost()) {
            $data = Security::cleanArray($_POST);
            $admissionId = $data['admission_id'];
            
            $interviewData = [
                'admission_id' => $admissionId,
                'interview_date' => $data['interview_date'], // datetime-local format usually works or needs format
                'interviewer_id' => Auth::getUserId(), // Default to current user or select from list
                'status' => 'scheduled'
            ];
            
            $model = $this->model('admissions');
            if ($model->scheduleInterview($interviewData)) {
                $model->updateStatus($admissionId, 'interview_scheduled');
                $this->setFlash('success', 'Interview scheduled successfully.');
            } else {
                $this->setFlash('error', 'Failed to schedule interview.');
            }
            
            $this->redirect('admissions/view_application/' . $admissionId);
        }
    }
    
    /**
     * Generate Offer Letter
     */
    public function generate_offer($id) {
        $this->requireRole(['super_admin', 'admin', 'principal']);
        
        $model = $this->model('admissions');
        $application = $model->getApplication($id);
        
        if (!$application) {
            $this->setFlash('error', 'Application not found.');
            $this->redirect('admissions');
        }
        
        // Update status if needed
        if ($application['status'] !== 'offered') {
            $model->updateStatus($id, 'offered');
            $application['status'] = 'offered';
        }
        
        // Load simple view for printing
        $data = [
            'pageTitle' => 'Offer Letter',
            'application' => $application
        ];
        
        // Use a layout-less view or specific print layout
        $this->view('admissions/offer_letter', $data); // We might need to handle layout in view() or manually include header/footer specific for print
    }
    
    /**
     * Waitlist Management
     */
    public function waitlist() {
        $this->requireRole(['super_admin', 'admin', 'principal']);
        
        $model = $this->model('admissions');
        
        if ($this->isPost() && isset($_POST['ranks'])) {
            // Update ranks
            foreach ($_POST['ranks'] as $id => $rank) {
                $model->updateWaitlistRank($id, (int)$rank);
            }
            $this->setFlash('success', 'Waitlist rankings updated.');
        }
        
        $students = $model->getWaitlist();
        
        $data = [
            'pageTitle' => 'Waitlist Management',
            'students' => $students
        ];
        
        $this->view('admissions/waitlist', $data);
    }
}
