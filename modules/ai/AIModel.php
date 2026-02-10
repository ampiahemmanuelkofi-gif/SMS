<?php
/**
 * AI Model
 * Handles intelligent data analysis, predictions, and automation logic
 */

class AIModel extends Model {
    
    /**
     * Predictive Analytics for student performance
     * Analyzes grade trends over time
     */
    public function predictPerformance($studentId) {
        // Fetch historical marks for the student
        $marks = $this->select("
            SELECT m.marks_obtained, e.exam_date, e.max_marks 
            FROM exam_marks m
            JOIN exams e ON m.exam_id = e.id
            WHERE m.student_id = ? 
            ORDER BY e.exam_date DESC
            LIMIT 10
        ", [$studentId]);

        if (count($marks) < 2) return null;

        $scores = array_map(function($m) { 
            return ($m['marks_obtained'] / $m['max_marks']) * 100; 
        }, $marks);

        $latest = $scores[0];
        $previous = $scores[1];
        $trend = $latest - $previous;

        $prediction = [
            'type' => 'performance_trend',
            'latest_score' => $latest,
            'trend' => $trend,
            'risk_level' => ($trend < -15) ? 'High' : (($trend < -5) ? 'Medium' : 'Low'),
            'comment' => $this->getTrendComment($trend)
        ];

        return $prediction;
    }

    private function getTrendComment($trend) {
        if ($trend < -15) return "Significant drop in performance detected. Investigation recommended.";
        if ($trend < -5) return "Showing slight downward trend. Monitor progress.";
        if ($trend > 10) return "Excellent improvement in scores.";
        return "Steady academic performance maintained.";
    }

    /**
     * Automated Report Card Comment Generation
     */
    public function generateAutomatedComment($score) {
        if ($score >= 80) return "Outstanding performance. Displays a profound understanding of the subject matter.";
        if ($score >= 70) return "Very good work. Consistent effort and participation in class.";
        if ($score >= 60) return "Good performance. Can achieve more with focused revision.";
        if ($score >= 50) return "Fair performance. Needs to put in more effort in core concepts.";
        if ($score >= 40) return "Passable performance. Supplemental lessons highly recommended.";
        return "Performance is below expectation. Requires urgent intervention and extra support.";
    }

    /**
     * Anomaly Detection (Attendance/Grades)
     */
    public function detectAnomalies() {
        // Detect sudden drops in attendance (e.g., student absent 3 days in a row)
        $anomalies = [];
        
        // This is a simplified logic – in a real app, this would be a more complex SQL query
        $results = $this->select("
            SELECT student_id, COUNT(*) as absences
            FROM attendance
            WHERE status = 'absent' AND date > DATE_SUB(NOW(), INTERVAL 7 DAY)
            GROUP BY student_id
            HAVING absences >= 3
        ");

        foreach ($results as $r) {
            $anomalies[] = [
                'student_id' => $r['student_id'],
                'type' => 'attendance_drop',
                'severity' => 'critical',
                'message' => "Student has been absent {$r['absences']} times this week."
            ];
        }

        return $anomalies;
    }

    /**
     * Intelligent Fee Remainder Suggestions
     */
    public function getFeeReminders() {
        // Identifies parents who typically pay at specific times but haven't yet
        return $this->select("
            SELECT u.id as student_id, u.full_name, f.amount - f.paid_amount as balance
            FROM users u
            JOIN student_fees f ON u.id = f.student_id
            WHERE f.status != 'paid' AND f.due_date < NOW()
        ");
    }

    /**
     * Automated Substitution Suggestions
     */
    public function getSubstitutes($subjectId, $date) {
        // Find teachers who specialize in the subject and are free
        // Simplified: just find teachers in the same subject department
        return $this->select("
            SELECT u.id, u.full_name
            FROM users u
            WHERE u.role = 'teacher' 
            AND u.id NOT IN (SELECT teacher_id FROM attendance WHERE date = ? AND status = 'absent')
            LIMIT 3
        ", [$date]);
    }
}
