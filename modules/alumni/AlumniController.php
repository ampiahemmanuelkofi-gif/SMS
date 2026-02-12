<?php
/**
 * Alumni Controller
 */

class AlumniController extends Controller {
    
    protected $alumniModel;

    public function __construct() {
        // Require login for all alumni actions
        $this->requireLogin();
        $this->alumniModel = $this->model('alumni');
    }
    
    /**
     * Main index (listing for admins, dashboard for alumni)
     */
    public function index() {
        $role = Auth::getRole();
        
        if ($role === 'alumni') {
            $this->portal();
            return;
        }

        // For other roles (admin, etc.), show the alumni registry
        $this->registry();
    }

    /**
     * Alumni Registry (Admin/Staff View)
     */
    public function registry() {
        $search = $_GET['search'] ?? null;
        $alumni = $this->alumniModel->getAlumni($search);

        $data = [
            'pageTitle' => 'Alumni Registry',
            'alumni' => $alumni,
            'search' => $search
        ];
        
        $this->view('alumni/registry', $data);
    }

    /**
     * Alumni Portal (Alumni View)
     */
    public function portal() {
        $events = $this->alumniModel->getEvents(5);
        
        $data = [
            'pageTitle' => 'Alumni Portal',
            'events' => $events
        ];
        
        $this->view('dashboard/alumni', $data);
    }

    /**
     * Alumni Events
     */
    public function events() {
        $events = $this->alumniModel->getEvents(10);
        
        $data = [
            'pageTitle' => 'Alumni Events',
            'events' => $events
        ];
        
        $this->view('alumni/events', $data);
    }

    /**
     * Add Alumni Event (Admin only)
     */
    public function addEvent() {
        $this->requireRole(['super_admin', 'admin']);

        if ($this->isPost()) {
            $this->requireNotDemo();
            $data = $this->sanitizedPost();
            
            $validator = new Validator();
            $validator->required('title', $data['title'], 'Event Title')
                     ->required('event_date', $data['event_date'], 'Event Date');
            
            if ($validator->isValid()) {
                $eventData = [
                    'title' => $data['title'],
                    'description' => $data['description'] ?? null,
                    'event_date' => $data['event_date'],
                    'event_time' => $data['event_time'] ?? null,
                    'location' => $data['location'] ?? null,
                    'created_by' => Auth::getUserId()
                ];
                
                if ($this->alumniModel->addEvent($eventData)) {
                    $this->setFlash('success', 'Alumni event added successfully.');
                    $this->redirect('alumni/events');
                } else {
                    $this->setFlash('error', 'Failed to add event.');
                }
            } else {
                $this->setFlash('error', $validator->getFirstError());
            }
        }
        
        $data = [
            'pageTitle' => 'Add Alumni Event'
        ];
        $this->view('alumni/event_form', $data);
    }

    /**
     * Alumni Donations
     */
    public function donations() {
        $this->requireRole(['super_admin', 'admin', 'accountant']);
        
        $donations = $this->alumniModel->getDonations(20);
        
        $data = [
            'pageTitle' => 'Alumni Donations',
            'donations' => $donations
        ];
        
        $this->view('alumni/donations', $data);
    }
}
