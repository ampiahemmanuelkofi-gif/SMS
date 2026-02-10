<?php
/**
 * Academics Controller
 */

class AcademicsController extends Controller {
    
    /**
     * Default index (redirect to classes)
     */
    /**
     * Default index (redirect to programs if any, else classes)
     */
    public function index() {
        $this->redirect('academics/classes');
    }

    /**
     * Manage Academic Programs
     */
    public function programs() {
        $this->requireRole(['super_admin', 'admin']);
        
        $model = $this->model('academics');
        $programs = $model->getPrograms();
        
        $data = [
            'pageTitle' => 'Academic Programs',
            'programs' => $programs
        ];
        
        $this->view('academics/programs', $data);
    }
    
    /**
     * Add Program
     */
    public function addProgram() {
        $this->requireRole(['super_admin', 'admin']);
        
        if ($this->isPost()) {
            $data = Security::cleanArray($_POST);
            $model = $this->model('academics');
            
            if (!empty($data['name'])) {
                if ($model->insert('academic_programs', ['name' => $data['name'], 'description' => $data['description']])) {
                    $this->setFlash('success', 'Program added successfully.');
                } else {
                    $this->setFlash('error', 'Failed to add program. It might already exist.');
                }
            } else {
                $this->setFlash('error', 'Program name is required.');
            }
        }
        $this->redirect('academics/programs');
    }

    /**
     * Manage classes
     */
    public function classes() {
        $this->requireRole(['super_admin', 'admin']);
        
        $model = $this->model('academics');
        $classes = $model->getClasses();
        
        $data = [
            'pageTitle' => 'Classes',
            'classes' => $classes
        ];
        
        $this->view('academics/classes', $data);
    }
    
    /**
     * Manage sections for a class
     */
    public function sections($classId) {
        $this->requireRole(['super_admin', 'admin']);
        
        $model = $this->model('academics');
        $class = $model->selectOne("SELECT * FROM classes WHERE id = ?", [$classId]);
        
        if (!$class) {
            $this->setFlash('error', 'Class not found.');
            $this->redirect('academics/classes');
        }
        
        $sections = $model->getSectionsByClass($classId);
        
        $data = [
            'pageTitle' => 'Sections for ' . $class['name'],
            'class' => $class,
            'sections' => $sections
        ];
        
        $this->view('academics/sections', $data);
    }
    
    /**
     * Manage academic years and terms
     */
    public function periods() {
        $this->requireRole(['super_admin', 'admin']);
        
        $model = $this->model('academics');
        $years = $model->getAcademicYears();
        $terms = $model->getTerms();
        
        $data = [
            'pageTitle' => 'Academic Periods',
            'years' => $years,
            'terms' => $terms
        ];
        
        $this->view('academics/periods', $data);
    }
    
    /**
     * Handle add academic year
     */
    public function addYear() {
        $this->requireRole(['super_admin', 'admin']);
        if ($this->isPost()) {
            $data = Security::cleanArray($_POST);
            $model = $this->model('academics');
            $model->insert('academic_years', [
                'name' => $data['name'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'is_current' => isset($data['is_current']) ? 1 : 0
            ]);
            if (isset($data['is_current'])) {
                $db = getDbConnection();
                $db->prepare("UPDATE academic_years SET is_current = 0 WHERE name != ?")->execute([$data['name']]);
            }
        }
        $this->redirect('academics/periods');
    }

    /**
     * Handle add term
     */
    public function addTerm() {
        $this->requireRole(['super_admin', 'admin']);
        if ($this->isPost()) {
            $data = Security::cleanArray($_POST);
            $model = $this->model('academics');
            $model->insert('terms', [
                'academic_year_id' => $data['academic_year_id'],
                'name' => $data['name'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'is_current' => isset($data['is_current']) ? 1 : 0
            ]);
            if (isset($data['is_current'])) {
                $db = getDbConnection();
                $db->prepare("UPDATE terms SET is_current = 0 WHERE id != LAST_INSERT_ID()")->execute([]);
            }
        }
        $this->redirect('academics/periods');
    }
    
    /**
     * Handle add class
     */
    public function addClass() {
        $this->requireRole(['super_admin', 'admin']);
        
        if ($this->isPost()) {
            $data = Security::cleanArray($_POST);
            
            $validator = new Validator();
            $validator->required('name', $data['name'], 'Class Name')
                     ->required('level', $data['level'], 'Academic Level');
            
            if ($validator->isValid()) {
                $model = $this->model('academics');
                if ($model->addClass(['name' => $data['name'], 'level' => $data['level']])) {
                    $this->setFlash('success', 'Class added successfully.');
                } else {
                    $this->setFlash('error', 'Failed to add class.');
                }
            } else {
                $this->setFlash('error', $validator->getFirstError());
            }
        }
        $this->redirect('academics/classes');
    }
    
    /**
     * Handle add section
     */
    public function addSection() {
        $this->requireRole(['super_admin', 'admin']);
        
        if ($this->isPost()) {
            $data = Security::cleanArray($_POST);
            
            $validator = new Validator();
            $validator->required('name', $data['name'], 'Section Name')
                     ->required('class_id', $data['class_id'], 'Class');
            
            if ($validator->isValid()) {
                $model = $this->model('academics');
                if ($model->addSection(['name' => $data['name'], 'class_id' => $data['class_id'], 'capacity' => $data['capacity']])) {
                    $this->setFlash('success', 'Section added successfully.');
                } else {
                    $this->setFlash('error', 'Failed to add section.');
                }
            } else {
                $this->setFlash('error', $validator->getFirstError());
            }
        }
        $this->redirect('academics/sections/' . $_POST['class_id']);
    }
    
    /**
     * Manage subjects
     */
    public function subjects() {
        $this->requireRole(['super_admin', 'admin']);
        
        $model = $this->model('academics');
        $subjects = $model->getAllSubjects();
        
        $data = [
            'pageTitle' => 'Subjects',
            'subjects' => $subjects
        ];
        
        $this->view('academics/subjects', $data);
    }
    
    /**
     * Handle add subject
     */
    public function addSubject() {
        $this->requireRole(['super_admin', 'admin']);
        
        if ($this->isPost()) {
            $data = Security::cleanArray($_POST);
            
            $validator = new Validator();
            $validator->required('name', $data['name'], 'Subject Name')
                     ->required('code', $data['code'], 'Subject Code')
                     ->required('level', $data['level'], 'Level')
                     ->required('type', $data['type'], 'Type');
            
            if ($validator->isValid()) {
                $model = $this->model('academics');
                if ($model->addSubject([
                    'name' => $data['name'], 
                    'code' => $data['code'], 
                    'level' => $data['level'], 
                    'type' => $data['type']
                ])) {
                    $this->setFlash('success', 'Subject added successfully.');
                } else {
                    $this->setFlash('error', 'Failed to add subject.');
                }
            } else {
                $this->setFlash('error', $validator->getFirstError());
            }
        }
        $this->redirect('academics/subjects');
    }
    
    /**
     * Assign teacher to class/section/subject
     */
    public function assignments() {
        $this->requireRole(['super_admin', 'admin']);
        
        $model = $this->model('academics');
        $assignments = $model->getTeacherAssignments();
        
        $data = [
            'pageTitle' => 'Teacher Assignments',
            'assignments' => $assignments
        ];
        
        $this->view('academics/assignments', $data);
    }

    
    /**
     * Handle assign teacher
     */
    public function assignTeacher() {
        $this->requireRole(['super_admin', 'admin']);
        
        if ($this->isPost()) {
            $data = Security::cleanArray($_POST);
            
            $validator = new Validator();
            $validator->required('teacher_id', $data['teacher_id'], 'Teacher')
                     ->required('section_id', $data['section_id'], 'Section')
                     ->required('subject_id', $data['subject_id'], 'Subject');
            
            if ($validator->isValid()) {
                $model = $this->model('academics');
                
                // Get current academic year
                $currentYear = $model->getCurrentAcademicYear();
                if (!$currentYear) {
                    $this->setFlash('error', 'Current academic year not set.');
                    $this->redirect('academics/assignments');
                }
                
                // Check if already assigned
                $check = $model->selectOne("SELECT id FROM teacher_assignments WHERE teacher_id = ? AND section_id = ? AND subject_id = ? AND academic_year_id = ?", 
                    [$data['teacher_id'], $data['section_id'], $data['subject_id'], $currentYear['id']]);
                
                if ($check) {
                    $this->setFlash('error', 'Teacher is already assigned to this class and subject.');
                } else {
                    if ($model->assignTeacher([
                        'teacher_id' => $data['teacher_id'],
                        'section_id' => $data['section_id'],
                        'subject_id' => $data['subject_id'],
                        'academic_year_id' => $currentYear['id']
                    ])) {
                        $this->setFlash('success', 'Teacher assigned successfully.');
                    } else {
                        $this->setFlash('error', 'Failed to assign teacher.');
                    }
                }
            } else {
                $this->setFlash('error', $validator->getFirstError());
            }
        }
        $this->redirect('academics/assignments');
    }
}
