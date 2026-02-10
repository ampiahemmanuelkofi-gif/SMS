<?php
/**
 * Assessments Controller
 */

class AssessmentsController extends Controller {
    
    protected $gradingService;

    public function __construct() {
        require_once 'GradingService.php';
        $this->gradingService = new GradingService();
    }
    
    /**
     * Default index (maps to entry)
     */
    public function index() {
        $this->entry();
    }
    
    /**
     * Marks entry interface
     */
    public function entry() {
        $this->requireRole(['super_admin', 'admin', 'teacher']);
        
        $academicsModel = $this->model('academics');
        $classes = $academicsModel->getClasses();
        $subjects = $academicsModel->getSubjects();
        
        $db = getDbConnection();
        $terms = $db->query("SELECT t.*, ay.name as year_name FROM terms t JOIN academic_years ay ON t.academic_year_id = ay.id WHERE t.is_current = 1 OR ay.is_current = 1")->fetchAll();
        $categories = $db->query("SELECT * FROM assessment_categories")->fetchAll();
        
        $sectionId = $this->get('section_id');
        $subjectId = $this->get('subject_id');
        $termId = $this->get('term_id');
        $categoryId = $this->get('category_id');
        
        $students = [];
        $existingMarks = [];
        
        if ($sectionId && $subjectId && $termId && $categoryId) {
            $model = $this->model('assessments');
            $students = $model->getStudentsForAssessment($sectionId);
            // Updated to use category_id instead of hardcoded type
            $marksData = $model->getExistingMarks($sectionId, $subjectId, $termId, $categoryId);
            foreach ($marksData as $m) {
                $existingMarks[$m['student_id']] = $m['marks'];
            }
        }
        
        $data = [
            'pageTitle' => 'Marks Entry',
            'classes' => $classes,
            'subjects' => $subjects,
            'terms' => $terms,
            'categories' => $categories,
            'students' => $students,
            'existingMarks' => $existingMarks,
            'filters' => [
                'class_id' => $this->get('class_id'),
                'section_id' => $sectionId,
                'subject_id' => $subjectId,
                'term_id' => $termId,
                'category_id' => $categoryId
            ]
        ];
        
        $this->view('assessments/entry', $data);
    }
    
    /**
     * Save marks
     */
    /**
     * Save marks
     */
    public function save() {
        $this->requireRole(['super_admin', 'admin', 'teacher']);
        
        if ($this->isPost()) {
            $data = $_POST;
            $model = $this->model('assessments');
            
            foreach ($data['marks'] as $studentId => $mark) {
                if ($mark === '') continue;
                
                $saveData = [
                    'student_id' => $studentId,
                    'section_id' => $data['section_id'],
                    'subject_id' => $data['subject_id'],
                    'term_id' => $data['term_id'],
                    // Storing category_id in assessment_type for now, or we should have altered the table. 
                    // Assuming assessment_type is flexible enough or we need to map it.
                    // For improved schema, we'd alter the table, but let's stick to using the column as varying type holder
                    'assessment_type' => $data['category_id'], 
                    'marks' => $mark
                ];
                
                $model->saveAssessment($saveData);
            }
            
            $this->setFlash('success', 'Marks saved successfully.');
        }
        
        $this->redirect('assessments/entry?' . http_build_query([
            'class_id' => $_POST['class_id'],
            'section_id' => $_POST['section_id'],
            'subject_id' => $_POST['subject_id'],
            'term_id' => $_POST['term_id'],
            'category_id' => $_POST['category_id']
        ]));
    }
    
    /**
     * BECE Results Entry (JHS 3)
     */
    public function bece() {
        $this->requireRole(['super_admin', 'admin', 'teacher']);
        
        $db = getDbConnection();
        $years = $db->query("SELECT * FROM academic_years ORDER BY start_date DESC")->fetchAll();
        
        // Get only JHS 3 sections
        $sections = $db->query("SELECT sec.*, c.name as class_name FROM sections sec JOIN classes c ON sec.class_id = c.id WHERE c.level = 'jhs' AND c.name LIKE '%3%'")->fetchAll();
        
        $yearId = $this->get('year_id');
        $sectionId = $this->get('section_id');
        
        $students = [];
        $results = [];
        
        if ($sectionId && $yearId) {
            $students = $db->query("SELECT * FROM students WHERE section_id = $sectionId AND status = 'active'")->fetchAll();
            $existing = $db->query("SELECT * FROM bece_results WHERE academic_year_id = $yearId AND student_id IN (SELECT id FROM students WHERE section_id = $sectionId)")->fetchAll();
            foreach ($existing as $row) {
                $results[$row['student_id']] = $row;
            }
        }
        
        $data = [
            'pageTitle' => 'BECE Results',
            'years' => $years,
            'sections' => $sections,
            'students' => $students,
            'results' => $results,
            'filters' => [
                'year_id' => $yearId,
                'section_id' => $sectionId
            ]
        ];
        
        $this->view('assessments/bece', $data);
    }
    
    /**
     * Save BECE results
     */
    public function save_bece() {
        $this->requireRole(['super_admin', 'admin']);
        
        if ($this->isPost()) {
            $db = getDbConnection();
            $yearId = $_POST['year_id'];
            
            foreach ($_POST['results'] as $studentId => $res) {
                // Calculate aggregate (simple sum of best 6 for this UI)
                $grades = array_filter($res);
                $points = [];
                foreach($grades as $g) {
                    $points[] = (int)substr($g, 1);
                }
                sort($points);
                $aggregate = array_sum(array_slice($points, 0, 6));
                
                $check = $db->query("SELECT id FROM bece_results WHERE student_id = $studentId AND academic_year_id = $yearId")->fetch();
                
                if ($check) {
                    $stmt = $db->prepare("UPDATE bece_results SET english_grade = ?, maths_grade = ?, science_grade = ?, social_grade = ?, elective1_grade = ?, elective2_grade = ?, aggregate = ? WHERE id = ?");
                    $stmt->execute([$res['english'], $res['maths'], $res['science'], $res['social'], $res['elective1'], $res['elective2'], $aggregate, $check['id']]);
                } else {
                    $stmt = $db->prepare("INSERT INTO bece_results (student_id, academic_year_id, english_grade, maths_grade, science_grade, social_grade, elective1_grade, elective2_grade, aggregate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$studentId, $yearId, $res['english'], $res['maths'], $res['science'], $res['social'], $res['elective1'], $res['elective2'], $aggregate]);
                }
            }
            
            $this->setFlash('success', 'BECE results updated successfully.');
        }
        
        $this->redirect('assessments/bece?year_id=' . $_POST['year_id'] . '&section_id=' . $_POST['section_id']);
    }
    
    /**
     * Report Card View
     */
    public function reportCard($studentId) {
        $this->requireRole(['super_admin', 'admin', 'teacher', 'parent']);
        
        $db = getDbConnection();
        $termId = $this->get('term_id');
        
        if (!$termId) {
            $currentTermData = $db->query("SELECT id FROM terms WHERE is_current = 1 LIMIT 1")->fetch();
            $termId = $currentTermData['id'];
        }
        
        $student = $db->query("SELECT s.*, sec.name as section_name, c.name as class_name FROM students s JOIN sections sec ON s.section_id = sec.id JOIN classes c ON sec.class_id = c.id WHERE s.id = $studentId")->fetch();
        $term = $db->query("SELECT t.*, ay.name as year_name FROM terms t JOIN academic_years ay ON t.academic_year_id = ay.id WHERE t.id = $termId")->fetch();
        
        // Get marks
        $subjectMarks = $db->query("
            SELECT sub.name as subject_name, 
                   MAX(CASE WHEN assessment_type = 'class_score' THEN marks ELSE 0 END) as class_score,
                   MAX(CASE WHEN assessment_type = 'exam' THEN marks ELSE 0 END) as exam_score
            FROM assessments a
            JOIN subjects sub ON a.subject_id = sub.id
            WHERE a.student_id = $studentId AND a.term_id = $termId
            GROUP BY sub.id
        ")->fetchAll();
        
        // Attendance
        $attendance = [
            'total' => $db->query("SELECT COUNT(*) FROM attendance WHERE student_id = $studentId AND term_id = $termId")->fetchColumn(),
            'present' => $db->query("SELECT COUNT(*) FROM attendance WHERE student_id = $studentId AND term_id = $termId AND status = 'present'")->fetchColumn(),
            'absent' => $db->query("SELECT COUNT(*) FROM attendance WHERE student_id = $studentId AND term_id = $termId AND status = 'absent'")->fetchColumn()
        ];
        
        $data = [
            'pageTitle' => 'Student Report Card',
            'student' => $student,
            'term' => $term,
            'subjectMarks' => $subjectMarks,
            'attendance' => $attendance
        ];
        
        $this->view('assessments/report-card', $data);
    }
    /**
     * Skills Assessment Interface
     */
    public function skills() {
        $this->requireRole(['super_admin', 'admin', 'teacher']);
        
        $academicsModel = $this->model('academics');
        $classes = $academicsModel->getClasses();
        
        $db = getDbConnection();
        $terms = $db->query("SELECT t.*, ay.name as year_name FROM terms t JOIN academic_years ay ON t.academic_year_id = ay.id WHERE t.is_current = 1 OR ay.is_current = 1")->fetchAll();
        $skills = $db->query("SELECT * FROM skills ORDER BY category, name")->fetchAll();
        
        $sectionId = $this->get('section_id');
        $termId = $this->get('term_id');
        
        $students = [];
        $existingRatings = [];
        
        if ($sectionId && $termId) {
            $model = $this->model('assessments');
            $students = $model->getStudentsForAssessment($sectionId);
            
            // Get existing ratings
            $rows = $db->query("SELECT * FROM student_skill_ratings WHERE term_id = $termId AND student_id IN (SELECT id FROM students WHERE section_id = $sectionId)")->fetchAll();
            foreach ($rows as $row) {
                $existingRatings[$row['student_id']][$row['skill_id']] = $row['rating'];
            }
        }
        
        $data = [
            'pageTitle' => 'Skills Assessment',
            'classes' => $classes,
            'terms' => $terms,
            'skills' => $skills,
            'students' => $students,
            'existingRatings' => $existingRatings,
            'filters' => [
                'class_id' => $this->get('class_id'),
                'section_id' => $sectionId,
                'term_id' => $termId
            ]
        ];
        
        $this->view('assessments/skills', $data);
    }
    
    /**
     * Save Skills
     */
    public function saveSkills() {
        $this->requireRole(['super_admin', 'admin', 'teacher']);
        
        if ($this->isPost()) {
            $data = $_POST;
            $db = getDbConnection();
            
            foreach ($data['ratings'] as $studentId => $studentSkills) {
                foreach ($studentSkills as $skillId => $rating) {
                    if ($rating === '') continue;
                    
                    // Check existence
                    $check = $db->query("SELECT id FROM student_skill_ratings WHERE student_id = $studentId AND skill_id = $skillId AND term_id = {$data['term_id']}")->fetch();
                    
                    if ($check) {
                        $stmt = $db->prepare("UPDATE student_skill_ratings SET rating = ? WHERE id = ?");
                        $stmt->execute([$rating, $check['id']]);
                    } else {
                        $stmt = $db->prepare("INSERT INTO student_skill_ratings (student_id, term_id, skill_id, rating) VALUES (?, ?, ?, ?)");
                        $stmt->execute([$studentId, $data['term_id'], $skillId, $rating]);
                    }
                }
            }
            
            $this->setFlash('success', 'Skills assessments saved successfully.');
        }
        
        $this->redirect('assessments/skills?' . http_build_query([
            'class_id' => $_POST['class_id'],
            'section_id' => $_POST['section_id'],
            'term_id' => $_POST['term_id']
        ]));
    }

    /**
     * Assessment Setup Dispatcher
     */
    public function setup($action = 'index', ...$params) {
        $this->requireRole(['super_admin', 'admin']);

        switch ($action) {
            case 'index':
                $gradingSystems = $this->gradingService->getGradingSystems();
                return $this->view('assessments/setup', [
                    'pageTitle' => 'Assessment Setup',
                    'gradingSystems' => $gradingSystems
                ]);

            case 'scales':
                $systemId = $params[0] ?? null;
                if (!$systemId) $this->redirect('assessments/setup');
                
                $scales = $this->gradingService->getScales($systemId);
                $db = getDbConnection();
                $system = $db->query("SELECT * FROM grading_systems WHERE id = $systemId")->fetch();
                
                return $this->view('assessments/scales', [
                    'pageTitle' => 'Manage Grading Scales: ' . ($system['name'] ?? ''),
                    'system' => $system,
                    'scales' => $scales
                ]);

            case 'saveScale':
                if ($this->isPost()) {
                    $data = $_POST;
                    $db = getDbConnection();
                    if (isset($data['id']) && $data['id']) {
                        $stmt = $db->prepare("UPDATE grading_scales SET grade=?, min_score=?, max_score=?, gpa_point=?, remark=? WHERE id=?");
                        $stmt->execute([$data['grade'], $data['min_score'], $data['max_score'], $data['gpa_point'], $data['remark'], $data['id']]);
                        $this->setFlash('success', 'Scale updated.');
                    } else {
                        $stmt = $db->prepare("INSERT INTO grading_scales (system_id, grade, min_score, max_score, gpa_point, remark) VALUES (?, ?, ?, ?, ?, ?)");
                        $stmt->execute([$data['system_id'], $data['grade'], $data['min_score'], $data['max_score'], $data['gpa_point'], $data['remark']]);
                        $this->setFlash('success', 'Scale added.');
                    }
                    $this->redirect('assessments/setup/scales/' . $_POST['system_id']);
                }
                $this->redirect('assessments/setup');

            case 'weights':
                $academicsModel = $this->model('academics');
                $classes = $academicsModel->getClasses();
                $subjects = $academicsModel->getSubjects();
                
                $db = getDbConnection();
                $categories = $db->query("SELECT * FROM assessment_categories")->fetchAll();
                $terms = $db->query("SELECT * FROM terms WHERE is_current = 1")->fetchAll();
                
                $classId = $this->get('class_id');
                $subjectId = $this->get('subject_id');
                $termId = $this->get('term_id') ?? ($terms[0]['id'] ?? 0);
                
                $currentWeights = [];
                if ($classId && $subjectId) {
                    $currentWeights = $this->gradingService->getSubjectWeights($classId, $subjectId, $termId);
                }
                
                return $this->view('assessments/weights', [
                    'pageTitle' => 'Assessment Weight Configuration',
                    'classes' => $classes,
                    'subjects' => $subjects,
                    'categories' => $categories,
                    'terms' => $terms,
                    'filters' => [
                        'class_id' => $classId,
                        'subject_id' => $subjectId,
                        'term_id' => $termId
                    ],
                    'currentWeights' => $currentWeights
                ]);

            case 'saveWeights':
                if ($this->isPost()) {
                    $data = $_POST;
                    $db = getDbConnection();
                    $stmt = $db->prepare("DELETE FROM subject_weights WHERE class_id = ? AND subject_id = ? AND term_id = ?");
                    $stmt->execute([$data['class_id'], $data['subject_id'], $data['term_id']]);
                    
                    $totalWeight = 0;
                    if (isset($data['weights'])) {
                        $stmt = $db->prepare("INSERT INTO subject_weights (class_id, subject_id, category_id, weight_percent, term_id) VALUES (?, ?, ?, ?, ?)");
                        foreach ($data['weights'] as $catId => $weight) {
                            if ($weight > 0) {
                                $stmt->execute([$data['class_id'], $data['subject_id'], $catId, $weight, $data['term_id']]);
                                $totalWeight += $weight;
                            }
                        }
                    }
                    
                    if ($totalWeight != 100) {
                        $this->setFlash('warning', "Weights saved, but total is $totalWeight% (should be 100%).");
                    } else {
                        $this->setFlash('success', 'Weights saved successfully.');
                    }
                    $this->redirect('assessments/setup/weights?' . http_build_query([
                        'class_id' => $_POST['class_id'],
                        'subject_id' => $_POST['subject_id'],
                        'term_id' => $_POST['term_id']
                    ]));
                }
                $this->redirect('assessments/setup');

            default:
                header("HTTP/1.0 404 Not Found");
                require_once TEMPLATES_PATH . '/errors/404.php';
                exit;
        }
    }
}
