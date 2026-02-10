<?php
/**
 * Resources Controller (Rooms, Lessons, Syllabus)
 */
class ResourcesController extends Controller {

    /**
     * Manage Rooms
     */
    public function rooms() {
        $this->requireRole(['super_admin', 'admin']);
        
        $model = $this->model('resources');
        
        if ($this->isPost()) {
            $data = Security::cleanArray($_POST);
            if ($model->addRoom($data)) {
                 $this->setFlash('success', 'Room added successfully.');
            } else {
                 $this->setFlash('error', 'Failed to add room.');
            }
            $this->redirect('resources/rooms');
        }
        
        $rooms = $model->getRooms();
        $data = ['pageTitle' => 'School Rooms', 'rooms' => $rooms];
        $this->view('resources/rooms', $data);
    }
    
    /**
     * Lesson Plans
     */
    public function lesson_plans() {
        $this->requireRole(['super_admin', 'admin', 'teacher']);
        
        $model = $this->model('resources');
        
        // If teacher, only see own. If admin, see all.
        $teacherId = (Auth::getRole() === 'teacher') ? Auth::getUserId() : null;
        
        $plans = $model->getLessonPlans($teacherId);
        
        $data = ['pageTitle' => 'Lesson Plans', 'plans' => $plans];
        $this->view('resources/lesson_plans', $data);
    }
}
