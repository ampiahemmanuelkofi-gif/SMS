<?php
/**
 * Promotions Controller
 * Handles student progression to new classes/sections
 */

class PromotionsController extends Controller {
    
    /**
     * Show promotion interface
     */
    public function index() {
        $this->requireRole(['super_admin', 'admin']);
        
        $academicsModel = $this->model('academics');
        $classes = $academicsModel->getClasses();
        
        $db = getDbConnection();
        $years = $db->query("SELECT * FROM academic_years ORDER BY start_date DESC")->fetchAll();
        
        $data = [
            'pageTitle' => 'Student Promotions',
            'classes' => $classes,
            'years' => $years,
            'filters' => [
                'from_class_id' => $this->get('from_class_id'),
                'from_section_id' => $this->get('from_section_id'),
                'to_year_id' => $this->get('to_year_id')
            ]
        ];
        
        $students = [];
        if ($data['filters']['from_section_id']) {
            $students = $db->query("SELECT * FROM students WHERE section_id = " . $data['filters']['from_section_id'] . " AND status = 'active'")->fetchAll();
        }
        $data['students'] = $students;
        
        $this->view('academics/promotions', $data);
    }
    
    /**
     * Process promotion
     */
    public function process() {
        $this->requireRole(['super_admin', 'admin']);
        
        if ($this->isPost()) {
            $db = getDbConnection();
            $data = $_POST;
            $studentIds = $data['student_ids'] ?? [];
            $toSectionId = $data['to_section_id'];
            $fromSectionId = $data['from_section_id'];
            $toYearId = $data['to_year_id'];
            
            if (empty($studentIds) || !$toSectionId || !$toYearId) {
                $this->setFlash('error', 'Please select students and the target section.');
                $this->redirect('promotions?from_section_id=' . $fromSectionId);
            }
            
            $db->beginTransaction();
            try {
                $model = $this->model('promotions');
                foreach ($studentIds as $studentId) {
                    // Log promotion
                    $model->logPromotion([
                        'student_id' => $studentId,
                        'from_section_id' => $fromSectionId,
                        'to_section_id' => $toSectionId,
                        'academic_year_id' => $toYearId,
                        'promoted_by' => Auth::getUserId(),
                        'promotion_date' => date('Y-m-d')
                    ]);
                    
                    // Update student section
                    $stmt = $db->prepare("UPDATE students SET section_id = ? WHERE id = ?");
                    $stmt->execute([$toSectionId, $studentId]);
                }
                
                $db->commit();
                $this->setFlash('success', count($studentIds) . ' students promoted successfully.');
            } catch (Exception $e) {
                $db->rollBack();
                $this->setFlash('error', 'Promotion failed: ' . $e->getMessage());
            }
        }
        
        $this->redirect('promotions?from_section_id=' . $fromSectionId);
    }
}
