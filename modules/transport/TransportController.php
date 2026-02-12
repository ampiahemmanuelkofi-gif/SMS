<?php
/**
 * Transport Controller
 * Manages school transport logistics
 */

class TransportController extends Controller {
    
    public function index() {
        $this->requirePermission('transport');
        $model = $this->model('transport');
        
        $data = [
            'pageTitle' => 'Transport Dashboard',
            'vehicles' => $model->getVehicles(),
            'routes' => $model->getRoutes()
        ];
        
        $this->view('transport/dashboard', $data);
    }

    public function fleet() {
        $this->requirePermission('transport');
        $model = $this->model('transport');
        
        if ($this->isPost()) {
            $action = $_POST['action'];
            if ($action == 'add') {
                $model->addVehicle([
                    'plate_number' => $_POST['plate_number'],
                    'vehicle_model' => $_POST['vehicle_model'],
                    'capacity' => $_POST['capacity'],
                    'driver_name' => $_POST['driver_name'],
                    'driver_phone' => $_POST['driver_phone']
                ]);
                $this->setFlash('success', "Vehicle added successfully.");
            }
            $this->redirect('transport/fleet');
        }

        $data = [
            'pageTitle' => 'Fleet Management',
            'vehicles' => $model->getVehicles()
        ];
        
        $this->view('transport/fleet', $data);
    }

    public function routes() {
        $this->requirePermission('transport');
        $model = $this->model('transport');
        
        if ($this->isPost()) {
            $model->addRoute([
                'route_name' => $_POST['route_name'],
                'start_point' => $_POST['start_point'],
                'end_point' => $_POST['end_point'],
                'base_fare' => $_POST['base_fare'],
                'vehicle_id' => $_POST['vehicle_id']
            ]);
            $this->setFlash('success', "Route created successfully.");
            $this->redirect('transport/routes');
        }

        $data = [
            'pageTitle' => 'Route Management',
            'routes' => $model->getRoutes(),
            'vehicles' => $model->getVehicles()
        ];
        
        $this->view('transport/routes', $data);
    }

    public function assignments() {
        $this->requirePermission('transport');
        $model = $this->model('transport');
        $usersModel = $this->model('users');
        
        if ($this->isPost()) {
            $model->assignTransport([
                'user_id' => $_POST['user_id'],
                'route_id' => $_POST['route_id'],
                'start_date' => $_POST['start_date']
            ]);
            $this->setFlash('success', "Student assigned to route.");
            $this->redirect('transport/assignments');
        }

        $data = [
            'pageTitle' => 'Transport Assignments',
            'assignments' => $model->getAssignments(),
            'students' => $usersModel->getUsersByRole('student'),
            'routes' => $model->getRoutes()
        ];
        
        $this->view('transport/assignments', $data);
    }

    public function maintenance() {
        $this->requirePermission('transport');
        $model = $this->model('transport');
        
        if ($this->isPost()) {
            $model->addMaintenanceEntry([
                'vehicle_id' => $_POST['vehicle_id'],
                'service_date' => $_POST['service_date'],
                'service_type' => $_POST['service_type'],
                'cost' => $_POST['cost'],
                'notes' => $_POST['notes']
            ]);
            $this->setFlash('success', "Maintenance log added.");
            $this->redirect('transport/maintenance');
        }

        $data = [
            'pageTitle' => 'Vehicle Maintenance',
            'logs' => $model->getMaintenanceLogs(),
            'vehicles' => $model->getVehicles()
        ];
        
        $this->view('transport/maintenance', $data);
    }
}
