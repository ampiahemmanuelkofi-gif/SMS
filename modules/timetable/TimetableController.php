<?php
/**
 * Timetable Controller
 */
class TimetableController extends Controller {
    
    /**
     * View Class Timetable
     */
    public function index() {
        $this->requireRole(['super_admin', 'admin', 'teacher', 'student', 'parent']);
        
        $academicsModel = $this->model('academics');
        $classes = $academicsModel->getClasses();
        
        $model = $this->model('timetable');
        
        $selectedClassId = isset($_GET['class_id']) ? $_GET['class_id'] : (isset($classes[0]) ? $classes[0]['id'] : null);
        $timetable = [];
        
        if ($selectedClassId) {
            $timetable = $model->getClassTimetable($selectedClassId);
        }
        
        $data = [
            'pageTitle' => 'Class Timetable',
            'classes' => $classes,
            'selectedClassId' => $selectedClassId,
            'timetable' => $timetable
        ];
        
        $this->view('timetable/index', $data);
    }
    
    /**
     * Manage Timetable (Admin/Teacher)
     */
    public function manage() {
        $this->requireRole(['super_admin', 'admin']);
        
        $academicsModel = $this->model('academics');
        $classes = $academicsModel->getClasses();
        $rooms = $this->model('resources')->getRooms();
        $subjects = $academicsModel->getAllSubjects(); // Needs implementation or specific fetch
        
        // For simple management, we might pick a class first
        $selectedClassId = isset($_GET['class_id']) ? $_GET['class_id'] : null;
        
        $data = [
            'pageTitle' => 'Manage Timetable',
            'classes' => $classes,
            'rooms' => $rooms,
            'subjects' => $subjects,
            'selectedClassId' => $selectedClassId
        ];
        
        $this->view('timetable/manage', $data);
    }
    
    /**
     * Add Timetable Entry
     */
    public function add_entry() {
        $this->requireRole(['super_admin', 'admin']);
        
        if ($this->isPost()) {
            $data = Security::cleanArray($_POST);
            $model = $this->model('timetable');
            
            // Validate conflicts
            if ($model->checkConflict($data['teacher_id'], $data['day_of_week'], $data['start_time'], $data['end_time'])) {
                $this->setFlash('error', 'Conflict detected! Teacher is already booked at this time.');
            } elseif ($model->checkRoomConflict($data['room_id'], $data['day_of_week'], $data['start_time'], $data['end_time'])) {
                $this->setFlash('error', 'Conflict detected! Room is already booked at this time.');
            } else {
                if ($model->addEntry($data)) {
                    $this->setFlash('success', 'Timetable entry added.');
                } else {
                    $this->setFlash('error', 'Failed to add entry.');
                }
            }
            
            $this->redirect('timetable/manage?class_id=' . $data['class_id']);
        }
    }
}
