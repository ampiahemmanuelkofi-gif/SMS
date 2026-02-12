<?php
/**
 * Cafeteria Controller
 * Manages school food services and dietary requirements
 */

class CafeteriaController extends Controller {
    
    public function index() {
        $this->requirePermission('cafeteria');
        $model = $this->model('cafeteria');
        
        $data = [
            'pageTitle' => 'Cafeteria Dashboard',
            'today_menu' => $model->getTodayMenu(),
            'meal_types' => $model->getMealTypes()
        ];
        
        $this->view('cafeteria/dashboard', $data);
    }

    public function menus() {
        $this->requirePermission('cafeteria');
        $model = $this->model('cafeteria');
        
        if ($this->isPost()) {
            $model->addMenu([
                'meal_date' => $_POST['meal_date'],
                'meal_type_id' => $_POST['meal_type_id'],
                'menu_item' => $_POST['menu_item'],
                'description' => $_POST['description'],
                'is_published' => 1
            ]);
            $this->setFlash('success', "Menu item added successfully.");
            $this->redirect('cafeteria/menus');
        }

        $data = [
            'pageTitle' => 'Weekly Menu Planning',
            'menus' => $model->getMenus(date('Y-m-d', strtotime('-1 day')), date('Y-m-d', strtotime('+7 days'))),
            'meal_types' => $model->getMealTypes()
        ];
        
        $this->view('cafeteria/menu_planning', $data);
    }

    public function attendance() {
        $this->requirePermission('cafeteria');
        $model = $this->model('cafeteria');
        $usersModel = $this->model('users');
        $healthModel = $this->model('health'); // To check medical allergies
        
        if ($this->isPost()) {
            $success = $model->logMeal([
                'user_id' => $_POST['user_id'],
                'menu_id' => $_POST['menu_id'],
                'marked_by' => $_SESSION['user_id']
            ]);
            if ($success) {
                $this->setFlash('success', "Meal marked for student.");
            } else {
                $this->setFlash('warning', "Student already marked for this meal.");
            }
            $this->redirect('cafeteria/attendance?menu_id=' . $_POST['menu_id']);
        }

        $menuId = $_GET['menu_id'] ?? null;
        $currentMenu = null;
        $dietaryAlerts = [];
        
        if ($menuId) {
            $today = $model->getTodayMenu();
            foreach ($today as $m) {
                if ($m['id'] == $menuId) {
                    $currentMenu = $m;
                    break;
                }
            }
        }

        // Get dietary alerts for all students to show warning icons in selection
        $restrictions = $model->getDietaryRestrictions();
        foreach ($restrictions as $r) {
            $dietaryAlerts[$r['user_id']][] = $r['details'];
        }

        $data = [
            'pageTitle' => 'Meal Attendance (Check-off)',
            'today_menu' => $model->getTodayMenu(),
            'current_menu' => $currentMenu,
            'students' => $usersModel->getUsersByRole('student'),
            'dietary_alerts' => $dietaryAlerts,
            'logs' => $menuId ? $model->getMealLogs($menuId) : []
        ];
        
        $this->view('cafeteria/attendance', $data);
    }

    public function restrictions() {
        $this->requireRole(['super_admin', 'admin', 'canteen_staff', 'nurse']);
        $model = $this->model('cafeteria');
        $usersModel = $this->model('users');
        
        if ($this->isPost()) {
            $model->addRestriction([
                'user_id' => $_POST['user_id'],
                'restriction_type' => $_POST['restriction_type'],
                'details' => $_POST['details']
            ]);
            $this->setFlash('success', "Dietary restriction added.");
            $this->redirect('cafeteria/restrictions');
        }

        $data = [
            'pageTitle' => 'Dietary Restrictions',
            'restrictions' => $model->getDietaryRestrictions(),
            'students' => $usersModel->getUsersByRole('student')
        ];
        
        $this->view('cafeteria/dietary_registry', $data);
    }
}
