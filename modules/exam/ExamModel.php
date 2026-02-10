<?php
/**
 * Exam Model
 * Handles examinations, grade scales, and score recording
 */

class ExamModel extends Model {
    
    // --- Exam Types & Scales ---
    public function getExamTypes() {
        return $this->select("SELECT * FROM exam_types ORDER BY contribution_percentage DESC");
    }

    public function getGradeScales() {
        return $this->select("SELECT * FROM exam_grade_scales ORDER BY min_score DESC");
    }

    // --- Exams ---
    public function getExams($subjectId = null, $termId = null) {
        $sql = "SELECT e.*, et.name as type_name, s.name as subject_name, ay.name as year_name, t.name as term_name
                FROM exams e
                JOIN exam_types et ON e.exam_type_id = et.id
                JOIN subjects s ON e.subject_id = s.id
                JOIN academic_years ay ON e.academic_year_id = ay.id
                JOIN terms t ON e.term_id = t.id
                WHERE 1=1";
        $params = [];
        if ($subjectId) {
            $sql .= " AND e.subject_id = ?";
            $params[] = $subjectId;
        }
        if ($termId) {
            $sql .= " AND e.term_id = ?";
            $params[] = $termId;
        }
        $sql .= " ORDER BY e.exam_date DESC";
        return $this->select($sql, $params);
    }

    public function addExam($data) {
        return $this->insert('exams', $data);
    }

    // --- Marks Entry ---
    public function getExamMarks($examId) {
        return $this->select("
            SELECT m.*, u.full_name as student_name
            FROM exam_marks m
            JOIN users u ON m.student_id = u.id
            WHERE m.exam_id = ?
        ", [$examId]);
    }

    public function saveMark($data) {
        // Use REPLACE INTO or check exists for manual implementation
        $exists = $this->selectOne("SELECT id FROM exam_marks WHERE exam_id = ? AND student_id = ?", [$data['exam_id'], $data['student_id']]);
        if ($exists) {
            return $this->update('exam_marks', [
                'marks_obtained' => $data['marks_obtained'],
                'teacher_comment' => $data['teacher_comment'],
                'recorded_by' => $data['recorded_by']
            ], "id = :id", [':id' => $exists['id']]);
        } else {
            return $this->insert('exam_marks', $data);
        }
    }

    // --- Grade Calculation ---
    public function calculateGrade($score) {
        $scales = $this->getGradeScales();
        foreach ($scales as $scale) {
            if ($score >= $scale['min_score'] && $score <= $scale['max_score']) {
                return $scale;
            }
        }
        return ['grade' => 'N/A', 'remark' => 'N/A'];
    }

    // --- Report Cards ---
    public function getStudentTerminalScores($studentId, $termId) {
        // Aggregates marks for all subjects in a term
        return $this->select("
            SELECT s.name as subject_name, e.exam_type_id, et.name as type_name, et.contribution_percentage, m.marks_obtained, e.max_marks
            FROM exam_marks m
            JOIN exams e ON m.exam_id = e.id
            JOIN exam_types et ON e.exam_type_id = et.id
            JOIN subjects s ON e.subject_id = s.id
            WHERE m.student_id = ? AND e.term_id = ?
        ", [$studentId, $termId]);
    }
}
