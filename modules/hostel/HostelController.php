<?php
/**
 * Hostel Controller
 * Manages school boarding and hostel logistics
 */

class HostelController extends Controller {
    
    public function index() {
        $this->requirePermission('hostel');
        $model = $this->model('hostel');
        
        $data = [
            'pageTitle' => 'Hostel Dashboard',
            'hostels' => $model->getHostels(),
            'allocations' => $model->getAllocations('active'),
            'pending_leave' => $model->getLeaveRequests('pending')
        ];
        
        $this->view('hostel/dashboard', $data);
    }

    public function inventory() {
        $this->requirePermission('hostel');
        $model = $this->model('hostel');
        
        if ($this->isPost()) {
            $action = $_POST['action'];
            if ($action == 'add_hostel') {
                $model->addHostel([
                    'hostel_name' => $_POST['hostel_name'],
                    'hostel_type' => $_POST['hostel_type'],
                    'capacity' => $_POST['capacity'],
                    'description' => $_POST['description']
                ]);
                $this->setFlash('success', "Hostel added successfully.");
            } elseif ($action == 'add_room') {
                $model->addRoom([
                    'hostel_id' => $_POST['hostel_id'],
                    'room_number' => $_POST['room_number'],
                    'floor' => $_POST['floor'],
                    'capacity' => $_POST['capacity']
                ]);
                $this->setFlash('success', "Room added successfully.");
            }
            $this->redirect('hostel/inventory');
        }

        $data = [
            'pageTitle' => 'Room & Bed Inventory',
            'hostels' => $model->getHostels(),
            'rooms' => $model->getRooms()
        ];
        
        $this->view('hostel/room_inventory', $data);
    }

    public function allocations() {
        $this->requirePermission('hostel');
        $model = $this->model('hostel');
        $usersModel = $this->model('users');
        
        if ($this->isPost()) {
            $action = $_POST['action'];
            if ($action == 'allocate') {
                $model->allocateBed([
                    'student_id' => $_POST['student_id'],
                    'bed_id' => $_POST['bed_id'],
                    'allotted_on' => $_POST['allotted_on']
                ]);
                $this->setFlash('success', "Bed allocated to student.");
            } elseif ($action == 'vacate') {
                $model->vacateBed($_POST['allocation_id'], $_POST['bed_id']);
                $this->setFlash('success', "Bed vacated successfully.");
            }
            $this->redirect('hostel/allocations');
        }

        $data = [
            'pageTitle' => 'Bed Allocations',
            'allocations' => $model->getAllocations('active'),
            'students' => $usersModel->getUsersByRole('student'),
            'available_beds' => $model->getBeds() // Filter for available in view if needed
        ];
        
        $this->view('hostel/allocations', $data);
    }

    public function leave() {
        $this->requirePermission('hostel');
        $model = $this->model('hostel');
        
        if ($this->isPost()) {
            $action = $_POST['action'];
            if ($action == 'status') {
                $model->updateLeaveStatus($_POST['id'], $_POST['status'], $_SESSION['user_id']);
                $this->setFlash('success', "Leave request updated.");
            }
            $this->redirect('hostel/leave');
        }

        $data = [
            'pageTitle' => 'Leave Management',
            'requests' => $model->getLeaveRequests()
        ];
        
        $this->view('hostel/leave_management', $data);
    }

    public function incidents() {
        $this->requirePermission('hostel');
        $model = $this->model('hostel');
        $usersModel = $this->model('users');
        
        if ($this->isPost()) {
            $model->addIncident([
                'student_id' => $_POST['student_id'],
                'incident_date' => $_POST['incident_date'],
                'category' => $_POST['category'],
                'description' => $_POST['description'],
                'action_taken' => $_POST['action_taken'],
                'reported_by' => $_SESSION['user_id']
            ]);
            $this->setFlash('success', "Incident logged.");
            $this->redirect('hostel/incidents');
        }

        $data = [
            'pageTitle' => 'Incident Tracking',
            'incidents' => $model->getIncidents(),
            'students' => $usersModel->getUsersByRole('student')
        ];
        
        $this->view('hostel/incidents', $data);
    }
}
