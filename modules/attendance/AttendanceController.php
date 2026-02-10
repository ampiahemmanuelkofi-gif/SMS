<?php
/**
 * Attendance Controller
 */

class AttendanceController extends Controller {
    
    protected $service;

    public function __construct() {
        require_once 'AttendanceService.php';
        $this->service = new AttendanceService();
    }

    /**
     * Default index (maps to mark)
     */
    public function index() {
        $this->mark();
    }

    /**
     * Mark attendance - select class/section/date
     */
    public function mark() {
        $this->requireRole(['super_admin', 'admin', 'teacher']);
        
        $academicsModel = $this->model('academics');
        $classes = $academicsModel->getClasses();
        $subjects = $academicsModel->getSubjects(); // Fetch subjects for subject-wise
        
        $mode = $this->service->getAttendanceMode();
        
        $classId = $this->get('class_id');
        $sectionId = $this->get('section_id');
        $subjectId = $this->get('subject_id');
        $date = $this->get('date', date('Y-m-d'));
        
        $students = [];
        $existingRecords = [];
        
        if ($classId && $sectionId) {
            // If subject-wise, require subject_id
            if ($mode === 'daily' || ($mode === 'subject_wise' && $subjectId)) {
                $studentModel = $this->model('students');
                $attendanceModel = $this->model('attendance');
                
                $students = $studentModel->getAllStudents($classId, $sectionId);
                $existingRecords = $attendanceModel->getExistingRecords($sectionId, $date, $subjectId);
            }
        }
        
        $data = [
            'pageTitle' => 'Mark Attendance (' . ucfirst(str_replace('_', '-', $mode)) . ')',
            'classes' => $classes,
            'subjects' => $subjects,
            'students' => $students,
            'existingRecords' => $existingRecords,
            'mode' => $mode,
            'filters' => [
                'class_id' => $classId,
                'section_id' => $sectionId,
                'subject_id' => $subjectId,
                'date' => $date
            ]
        ];
        
        $this->view('attendance/mark', $data);
    }
    
    /**
     * Handle attendance submission
     */
    public function save() {
        $this->requireRole(['super_admin', 'admin', 'teacher']);
        
        if ($this->isPost()) {
            $sectionId = $_POST['section_id'];
            $subjectId = $_POST['subject_id'] ?? null;
            $date = $_POST['date'];
            $attendance = $_POST['attendance']; // Array of student_id => status
            $times = $_POST['times'] ?? []; // Array of student_id => ['in' => '...', 'out' => '...']
            $remarks = $_POST['remarks'] ?? [];
            
            $model = $this->model('attendance');
            
            try {
                foreach ($attendance as $studentId => $status) {
                    $model->saveAttendance([
                        'student_id' => $studentId,
                        'section_id' => $sectionId,
                        'subject_id' => $subjectId,
                        'date' => $date,
                        'status' => $status,
                        'remarks' => $remarks[$studentId] ?? '',
                        'time_in' => $times[$studentId]['in'] ?? null,
                        'time_out' => $times[$studentId]['out'] ?? null,
                        'source' => 'manual'
                    ]);
                }
                
                $this->setFlash('success', 'Attendance recorded successfully for ' . $date);
            } catch (Exception $e) {
                $this->setFlash('error', 'Failed to save attendance: ' . $e->getMessage());
            }
        }
        
        if ($this->post('source') === 'mobile') {
            $this->redirect('iteacher/attendance?section_id=' . $sectionId);
        }

        $this->redirect('attendance/mark?' . http_build_query([
            'section_id' => $sectionId,
            'date' => $date,
            'class_id' => $_POST['class_id'],
            'subject_id' => $_POST['subject_id'] ?? ''
        ]));
    }
    
    /**
     * Leave Management
     */
    public function leaves() {
        $this->requireRole(['super_admin', 'admin', 'teacher', 'parent']);
        
        $pendingLeaves = $this->service->getPendingLeaves();
        
        // If parent/student, get their leaves
        // For admin/teacher, get pending leaves
        
        $data = [
            'pageTitle' => 'Leave Management',
            'pendingLeaves' => $pendingLeaves
        ];
        
        $this->view('attendance/leaves', $data);
    }
    
    /**
     * Request/Approve Leave
     */
    public function saveLeave() {
        if ($this->isPost()) {
            if (isset($_POST['approve_leave'])) {
                $this->requireRole(['super_admin', 'admin', 'teacher']);
                $this->service->updateLeaveStatus($_POST['leave_id'], 'approved', Auth::getUserId());
                $this->setFlash('success', 'Leave approved.');
            } elseif (isset($_POST['reject_leave'])) {
                $this->requireRole(['super_admin', 'admin', 'teacher']);
                $this->service->updateLeaveStatus($_POST['leave_id'], 'rejected', Auth::getUserId());
                $this->setFlash('success', 'Leave rejected.');
            } else {
                // New request
                $this->requireRole(['super_admin', 'admin', 'parent']);
                // Mock student ID for now or from session
                $studentId = $_POST['student_id']; 
                $this->service->requestLeave($studentId, $_POST['start_date'], $_POST['end_date'], $_POST['reason']);
                $this->setFlash('success', 'Leave request submitted.');
            }
        }
        $this->redirect('attendance/leaves');
    }

    /**
     * Configuration (Mode Toggle)
     */
    public function config() {
        $this->requireRole(['super_admin']);
        
        if ($this->isPost()) {
            $this->service->setAttendanceMode($_POST['mode']);
            $this->setFlash('success', 'Attendance mode updated.');
            $this->redirect('attendance/mark');
        }
    }

    /**
     * Attendance Reports
     */
    public function reports() {
        $this->requireRole(['super_admin', 'admin', 'teacher']);
        
        $academicsModel = $this->model('academics');
        $classes = $academicsModel->getClasses();
        
        $classId = $this->get('class_id');
        $sectionId = $this->get('section_id');
        $month = $this->get('month', date('Y-m'));
        
        $reportData = [];
        
        if ($sectionId) {
            $attendanceModel = $this->model('attendance');
            $reportData = $attendanceModel->getMonthlyReport($sectionId, $month);
        }
        
        $data = [
            'pageTitle' => 'Attendance Reports',
            'classes' => $classes,
            'reportData' => $reportData,
            'filters' => [
                'class_id' => $classId,
                'section_id' => $sectionId,
                'month' => $month
            ]
        ];
        
        $this->view('attendance/reports', $data);
    }
}
