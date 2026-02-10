<?php
/**
 * Homework Controller
 */

class HomeworkController extends Controller {
    
    /**
     * List homework (role-based)
     */
    public function index() {
        $this->requireRole(['super_admin', 'admin', 'teacher', 'parent']);
        $role = Auth::getRole();
        $db = getDbConnection();
        
        $homework = [];
        
        if (in_array($role, ['super_admin', 'admin'])) {
            $homework = $db->query("
                SELECT h.*, s.name as section_name, c.name as class_name, sub.name as subject_name, u.full_name as teacher_name
                FROM homework h
                JOIN sections s ON h.section_id = s.id
                JOIN classes c ON s.class_id = c.id
                JOIN subjects sub ON h.subject_id = sub.id
                JOIN users u ON h.created_by = u.id
                ORDER BY h.created_at DESC
            ")->fetchAll();
        } elseif ($role === 'teacher') {
            $teacherId = Auth::getUserId();
            $homework = $db->query("
                SELECT h.*, s.name as section_name, c.name as class_name, sub.name as subject_name
                FROM homework h
                JOIN sections s ON h.section_id = s.id
                JOIN classes c ON s.class_id = c.id
                JOIN subjects sub ON h.subject_id = sub.id
                WHERE h.created_by = $teacherId
                ORDER BY h.created_at DESC
            ")->fetchAll();
        } elseif ($role === 'parent') {
            $parentEmail = $db->query("SELECT email FROM users WHERE id = " . Auth::getUserId())->fetchColumn();
            $homework = $db->query("
                SELECT h.*, s.name as section_name, c.name as class_name, sub.name as subject_name, u.full_name as teacher_name, stud.first_name as student_name
                FROM homework h
                JOIN sections s ON h.section_id = s.id
                JOIN classes c ON s.class_id = c.id
                JOIN subjects sub ON h.subject_id = sub.id
                JOIN users u ON h.created_by = u.id
                JOIN students stud ON stud.section_id = s.id
                WHERE stud.guardian_email = '$parentEmail'
                ORDER BY h.created_at DESC
            ")->fetchAll();
        }
        
        $data = [
            'pageTitle' => 'Homework & Assignments',
            'homework' => $homework
        ];
        
        $this->view('homework/index', $data);
    }
    
    /**
     * Show create homework form
     */
    public function create() {
        $this->requireRole('teacher');
        
        $db = getDbConnection();
        $teacherId = Auth::getUserId();
        
        // Get assigned classes and subjects
        $assignments = $db->query("
            SELECT DISTINCT sec.id as section_id, sec.name as section_name, c.name as class_name, sub.id as subject_id, sub.name as subject_name
            FROM teacher_assignments ta
            JOIN sections sec ON ta.section_id = sec.id
            JOIN classes c ON sec.class_id = c.id
            JOIN subjects sub ON ta.subject_id = sub.id
            WHERE ta.teacher_id = $teacherId
        ")->fetchAll();
        
        $data = [
            'pageTitle' => 'Post Homework',
            'assignments' => $assignments
        ];
        
        $this->view('homework/create', $data);
    }
    
    /**
     * Save homework
     */
    public function save() {
        $this->requireRole('teacher');
        
        if ($this->isPost()) {
            $data = Security::cleanArray($_POST);
            $db = getDbConnection();
            
            $stmt = $db->prepare("INSERT INTO homework (section_id, subject_id, title, description, deadline, created_by) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['section_id'],
                $data['subject_id'],
                $data['title'],
                $data['description'],
                $data['deadline'],
                Auth::getUserId()
            ]);
            
            $this->setFlash('success', 'Homework posted successfully.');
            $this->redirect('homework');
        }
    }
    
    /**
     * Delete homework
     */
    public function delete($id) {
        $this->requireRole(['super_admin', 'admin', 'teacher']);
        $db = getDbConnection();
        
        // Check permissions
        if (Auth::getRole() === 'teacher') {
            $stmt = $db->prepare("SELECT id FROM homework WHERE id = ? AND created_by = ?");
            $stmt->execute([$id, Auth::getUserId()]);
            if (!$stmt->fetch()) {
                $this->setFlash('error', 'Unauthorized action.');
                $this->redirect('homework');
            }
        }
        
        $stmt = $db->prepare("DELETE FROM homework WHERE id = ?");
        $stmt->execute([$id]);
        
        $this->setFlash('success', 'Homework deleted successfully.');
        $this->redirect('homework');
    }
}
