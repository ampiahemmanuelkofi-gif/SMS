<?php
/**
 * Exam Controller
 * Manages grading, exam sessions, and report generation
 */

class ExamController extends Controller {
    
    public function index() {
        $this->requireRole(['super_admin', 'admin', 'teacher']);
        $model = $this->model('exam');
        $academics = $this->model('academics');
        
        $data = [
            'pageTitle' => 'Examination Management',
            'exams' => $model->getExams(),
            'exam_types' => $model->getExamTypes(),
            'subjects' => $academics->getAllSubjects(),
            'terms' => $academics->getTerms()
        ];
        
        $this->view('exam/index', $data);
    }

    public function addExam() {
        $this->requireRole(['super_admin', 'admin']);
        if ($this->isPost()) {
            $model = $this->model('exam');
            $academics = $this->model('academics');
            $currYear = $academics->getCurrentAcademicYear();
            
            $model->addExam([
                'exam_type_id' => $_POST['exam_type_id'],
                'subject_id' => $_POST['subject_id'],
                'academic_year_id' => $currYear['id'],
                'term_id' => $_POST['term_id'],
                'exam_date' => $_POST['exam_date'],
                'max_marks' => $_POST['max_marks']
            ]);
            $this->setFlash('success', "Exam session created.");
        }
        $this->redirect('exam');
    }

    public function marks($examId) {
        $this->requireRole(['super_admin', 'admin', 'teacher']);
        $model = $this->model('exam');
        $academics = $this->model('academics');
        $usersModel = $this->model('users');
        
        // Find exam
        $exams = $model->getExams();
        $exam = null;
        foreach ($exams as $e) { if ($e['id'] == $examId) { $exam = $e; break; } }
        
        if (!$exam) {
            $this->setFlash('error', "Exam session not found.");
            $this->redirect('exam');
        }

        if ($this->isPost()) {
            foreach ($_POST['marks'] as $studentId => $score) {
                if ($score === '') continue;
                $model->saveMark([
                    'exam_id' => $examId,
                    'student_id' => $studentId,
                    'marks_obtained' => $score,
                    'teacher_comment' => $_POST['comments'][$studentId] ?? '',
                    'recorded_by' => $_SESSION['user_id']
                ]);
            }
            $this->setFlash('success', "Marks saved successfully.");
            $this->redirect('exam/marks/' . $examId);
        }

        // Get students for the subject/class (simplified to all students for now, ideally filtered by class)
        $students = $usersModel->getUsersByRole('student');
        $existingMarks = [];
        foreach ($model->getExamMarks($examId) as $m) {
            $existingMarks[$m['student_id']] = $m;
        }

        $data = [
            'pageTitle' => 'Enter Marks: ' . $exam['subject_name'] . ' (' . $exam['type_name'] . ')',
            'exam' => $exam,
            'students' => $students,
            'existing_marks' => $existingMarks
        ];
        
        $this->view('exam/marks_entry', $data);
    }

    public function scales() {
        $this->requireRole(['super_admin', 'admin']);
        $model = $this->model('exam');
        
        $data = [
            'pageTitle' => 'Grading Scales',
            'scales' => $model->getGradeScales()
        ];
        
        $this->view('exam/grade_scales', $data);
    }

    public function report($studentId) {
        $this->requireRole(['super_admin', 'admin', 'teacher', 'parent', 'student']);
        $model = $this->model('exam');
        $academics = $this->model('academics');
        $usersModel = $this->model('users');
        
        $termId = $_GET['term_id'] ?? null;
        if (!$termId) {
            $currTerm = null;
            foreach ($academics->getTerms() as $t) { if ($t['is_current'] == 1) { $currTerm = $t; break; } }
            $termId = $currTerm ? $currTerm['id'] : null;
        }

        $student = $usersModel->getUserById($studentId);
        $scores = $model->getStudentTerminalScores($studentId, $termId);
        
        // Aggregate by subject
        $aggregated = [];
        foreach ($scores as $s) {
            $AggKey = $s['subject_name'];
            if (!isset($aggregated[$AggKey])) {
                $aggregated[$AggKey] = ['weighted_total' => 0, 'actual_total' => 0, 'details' => []];
            }
            $weighted = ($s['marks_obtained'] / $s['max_marks']) * $s['contribution_percentage'];
            $aggregated[$AggKey]['weighted_total'] += $weighted;
            $aggregated[$AggKey]['details'][] = $s;
        }

        $data = [
            'pageTitle' => 'Terminal Report Card',
            'student' => $student,
            'aggregated' => $aggregated,
            'model' => $model
        ];
        
        $this->view('exam/report_card', $data);
    }
}
