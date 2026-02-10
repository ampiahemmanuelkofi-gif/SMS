<?php
/**
 * Student Controller
 */

class StudentsController extends Controller {
    
    /**
     * List students
     */
    public function index() {
        $this->requireRole(['super_admin', 'admin']);
        
        $model = $this->model('students');
        
        // Handle filtering
        $classId = $this->get('class_id');
        $sectionId = $this->get('section_id');
        $search = $this->get('search');
        
        $students = $model->getAllStudents($classId, $sectionId, $search);
        
        // Get classes for filter
        $academicsModel = $this->model('academics');
        $classes = $academicsModel->getClasses();
        $sections = $classId ? $academicsModel->getSectionsByClass($classId) : [];
        
        $data = [
            'pageTitle' => 'Students',
            'students' => $students,
            'classes' => $classes,
            'sections' => $sections,
            'filters' => [
                'class_id' => $classId,
                'section_id' => $sectionId,
                'search' => $search
            ]
        ];
        
        $this->view('students/list', $data);
    }
    
    /**
     * Add new student
     */
    public function add() {
        $this->requireRole(['super_admin', 'admin']);
        
        $academicsModel = $this->model('academics');
        $classes = $academicsModel->getClasses();
        $model = $this->model('students');
        $categories = $model->getCategories();
        
        if ($this->isPost()) {
            $data = Security::cleanArray($_POST);
            $photo = isset($_FILES['photo']) ? $_FILES['photo'] : null;
            
            // Validation
            $validator = new Validator();
            $validator->required('first_name', $data['first_name'], 'First Name')
                     ->required('last_name', $data['last_name'], 'Last Name')
                     ->required('date_of_birth', $data['date_of_birth'], 'Date of Birth')
                     ->required('gender', $data['gender'], 'Gender')
                     ->required('guardian_name', $data['guardian_name'], 'Guardian Name')
                     ->required('guardian_phone', $data['guardian_phone'], 'Guardian Phone')
                     ->phone('guardian_phone', $data['guardian_phone'])
                     ->required('section_id', $data['section_id'], 'Class/Section')
                     ->required('admission_date', $data['admission_date'], 'Admission Date');
            
            if ($validator->isValid()) {
                // Handle photo upload
                $photoPath = null;
                if ($photo && !empty($photo['tmp_name'])) {
                    $upload = FileUpload::uploadImage($photo, UPLOADS_PATH . '/students');
                    if ($upload['success']) {
                        $photoPath = $upload['filename'];
                    } else {
                        $this->setFlash('error', $upload['error']);
                        $this->redirect('students/add');
                    }
                }
                
                // Prepare student data
                $studentData = [
                    'student_id' => $this->generateStudentId(),
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'date_of_birth' => $data['date_of_birth'],
                    'gender' => $data['gender'],
                    'guardian_name' => $data['guardian_name'],
                    'guardian_phone' => $data['guardian_phone'],
                    'guardian_email' => $data['guardian_email'],
                    'address' => $data['address'],
                    'photo' => $photoPath,
                    'category_id' => !empty($data['category_id']) ? $data['category_id'] : null,
                    'section_id' => $data['section_id'],
                    'admission_date' => $data['admission_date'],
                    'status' => 'active'
                ];
                
                $model = $this->model('students');
                if ($model->addStudent($studentData)) {
                    $this->setFlash('success', 'Student admitted successfully! Student ID: ' . $studentData['student_id']);
                    $this->redirect('students');
                } else {
                    $this->setFlash('error', 'Failed to add student. Please try again.');
                }
            } else {
                $this->setFlash('error', $validator->getFirstError());
            }
        }
        
        $data = [
            'pageTitle' => 'Admit Student',
            'classes' => $classes,
            'categories' => $categories
        ];
        
        $this->view('students/add', $data);
    }
    
    /**
     * View student profile
     */
    public function view_profile($id) {
        $this->requireRole(['super_admin', 'admin', 'teacher', 'parent']);
        
        // If parent, check if child
        if (Auth::getRole() === 'parent') {
            // TODO: Implement parent-student check
        }
        
        $model = $this->model('students');
        $student = $model->getStudentById($id);
        
        if (!$student) {
            $this->setFlash('error', 'Student not found.');
            $this->redirect('students');
        }
        
        $data = [
            'pageTitle' => 'Student Profile',
            'student' => $student,
            'medical' => $model->getMedicalInfo($id),
            'disciplinary' => $model->getDisciplinaryRecords($id),
            'siblings' => $model->getSiblings($id),
            'documents' => $model->getDocuments($id),
            'customFields' => $model->getCustomFieldsWithValues($id),
            'academicHistory' => $this->model('promotions')->getStudentPromotionHistory($id)
        ];
        
        $this->view('students/view', $data);
    }
    
    /**
     * Edit student
     */
    public function edit($id) {
        $this->requireRole(['super_admin', 'admin']);
        
        $model = $this->model('students');
        $student = $model->getStudentById($id);
        
        if (!$student) {
            $this->setFlash('error', 'Student not found.');
            $this->redirect('students');
        }
        
        $academicsModel = $this->model('academics');
        $classes = $academicsModel->getClasses();
        $sections = $academicsModel->getSectionsByClass($student['class_id']);
        $categories = $model->getCategories();
        
        if ($this->isPost()) {
            $data = Security::cleanArray($_POST);
            $photo = isset($_FILES['photo']) ? $_FILES['photo'] : null;
            
            // Validation (re-use logic or create helper)
            $validator = new Validator();
            // ... (similar validation to add())
            
            if ($validator->isValid()) {
                // Handle photo upload
                $photoPath = $student['photo'];
                if ($photo && !empty($photo['tmp_name'])) {
                    $upload = FileUpload::uploadImage($photo, UPLOADS_PATH . '/students');
                    if ($upload['success']) {
                        // Delete old photo if exists
                        if ($student['photo']) {
                            FileUpload::deleteFile(UPLOADS_PATH . '/students/' . $student['photo']);
                        }
                        $photoPath = $upload['filename'];
                    }
                }
                
                $updateData = [
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'date_of_birth' => $data['date_of_birth'],
                    'gender' => $data['gender'],
                    'guardian_name' => $data['guardian_name'],
                    'guardian_phone' => $data['guardian_phone'],
                    'guardian_email' => $data['guardian_email'],
                    'address' => $data['address'],
                    'photo' => $photoPath,
                    'category_id' => !empty($data['category_id']) ? $data['category_id'] : null,
                    'section_id' => $data['section_id'],
                    'status' => $data['status']
                ];
                
                if ($model->updateStudent($id, $updateData)) {
                    $this->setFlash('success', 'Student updated successfully.');
                    $this->redirect('students/view_profile/' . $id);
                } else {
                    $this->setFlash('error', 'Failed to update student.');
                }
            }
        }
        
        $data = [
            'pageTitle' => 'Edit Student',
            'student' => $student,
            'classes' => $classes,
            'sections' => $sections,
            'categories' => $categories
        ];
        
        $this->view('students/edit', $data);
    }
    
    /**
     * Generate unique student ID
     */
    private function generateStudentId() {
        $db = getDbConnection();
        
        // Get prefix and current year from settings
        $stmt = $db->query("SELECT setting_key, setting_value FROM settings WHERE setting_key IN ('student_id_prefix', 'student_id_year', 'student_id_counter')");
        $settings = [];
        while ($row = $stmt->fetch()) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        
        $prefix = isset($settings['student_id_prefix']) ? $settings['student_id_prefix'] : 'SCH';
        $year = isset($settings['student_id_year']) ? $settings['student_id_year'] : date('Y');
        $counter = isset($settings['student_id_counter']) ? (int)$settings['student_id_counter'] + 1 : 1;
        
        // Update counter in database
        $db->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = 'student_id_counter'")->execute([$counter]);
        
        return $prefix . '-' . $year . '-' . str_pad($counter, 4, '0', STR_PAD_LEFT);
    }
    
    /**
     * Get sections for a class (AJAX)
     */
    public function get_sections($classId) {
        $academicsModel = $this->model('academics');
        $sections = $academicsModel->getSectionsByClass($classId);
        $this->json($sections);
    }
    
    /**
     * Search students (AJAX)
     */
    public function search_ajax() {
        $query = $this->get('q');
        if (!$query) {
            $this->json([]);
            return;
        }
        
        $model = $this->model('students');
        $results = $model->searchStudents($query);
        $this->json($results);
    }
    
    /**
     * Save medical info
     */
    public function save_medical() {
        $this->requireRole(['super_admin', 'admin', 'teacher']);
        
        if ($this->isPost()) {
            $data = Security::cleanArray($_POST);
            $model = $this->model('students');
            
            $saveData = [
                'student_id' => $data['student_id'],
                'blood_group' => $data['blood_group'],
                'allergies' => $data['allergies'],
                'medical_conditions' => $data['medical_conditions'],
                'emergency_contact_name' => $data['emergency_contact_name'],
                'emergency_contact_phone' => $data['emergency_contact_phone']
            ];
            
            if ($model->saveMedicalInfo($saveData)) {
                $this->setFlash('success', 'Medical info updated.');
            } else {
                $this->setFlash('error', 'Failed to update medical info.');
            }
            $this->redirect('students/view_profile/' . $data['student_id'] . '#medical');
        }
    }
    
    /**
     * Add disciplinary record
     */
    public function add_discipline() {
        $this->requireRole(['super_admin', 'admin', 'teacher']);
        
        if ($this->isPost()) {
            $data = Security::cleanArray($_POST);
            $model = $this->model('students');
            
            $saveData = [
                'student_id' => $data['student_id'],
                'incident_date' => $data['incident_date'],
                'incident_description' => $data['incident_description'],
                'action_taken' => $data['action_taken'],
                'recorded_by' => Auth::getUserId()
            ];
            
            if ($model->addDisciplinaryRecord($saveData)) {
                $this->setFlash('success', 'Disciplinary record added.');
            } else {
                $this->setFlash('error', 'Failed to add record.');
            }
            $this->redirect('students/view_profile/' . $data['student_id'] . '#discipline');
        }
    }
    
    /**
     * Add sibling
     */
    public function add_sibling() {
        $this->requireRole(['super_admin', 'admin']);
        
        if ($this->isPost()) {
            $data = Security::cleanArray($_POST);
            $model = $this->model('students');
            
            if ($model->addSibling($data['student_id'], $data['sibling_id'], $data['relationship'])) {
                $this->setFlash('success', 'Sibling linked successfully.');
            } else {
                $this->setFlash('error', 'Failed to link sibling.');
            }
            $this->redirect('students/view_profile/' . $data['student_id'] . '#siblings');
        }
    }
    
    /**
     * Upload document
     */
    public function upload_document() {
        $this->requireRole(['super_admin', 'admin']);
        
        if ($this->isPost()) {
            $data = Security::cleanArray($_POST);
            $file = $_FILES['document'];
            
            if ($file && !empty($file['tmp_name'])) {
                $upload = FileUpload::uploadDocument($file, UPLOADS_PATH . '/documents');
                if ($upload['success']) {
                    $model = $this->model('students');
                    $model->addDocument([
                        'student_id' => $data['student_id'],
                        'document_name' => $data['name'],
                        'file_path' => $upload['filename'],
                        'document_type' => $data['type']
                    ]);
                    $this->setFlash('success', 'Document uploaded.');
                } else {
                    $this->setFlash('error', $upload['error']);
                }
            }
            $this->redirect('students/view_profile/' . $data['student_id'] . '#documents');
        }
    }
    
    /**
     * Save custom fields
     */
    public function save_custom_fields() {
        $this->requireRole(['super_admin', 'admin']);
        
        if ($this->isPost()) {
            $data = $_POST; // Use raw for custom fields to handle array if needed, but we purified values later
            $studentId = $data['student_id'];
            $model = $this->model('students');
            
            if (isset($data['field'])) {
                foreach ($data['field'] as $fieldId => $value) {
                    $model->saveCustomFieldValue($studentId, $fieldId, Security::clean($value));
                }
            }
            
            $this->setFlash('success', 'Custom fields updated.');
            $this->redirect('students/view_profile/' . $studentId . '#custom');
        }
    }
    
    /**
     * Export students to CSV
     */
    public function export() {
        $this->requireRole(['super_admin', 'admin']);
        
        $model = $this->model('students');
        $students = $model->getAllStudents();
        
        $filename = "students_export_" . date('Y-m-d') . ".csv";
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Header
        fputcsv($output, ['Student ID', 'First Name', 'Last Name', 'Gender', 'DOB', 'Class', 'Section', 'Category', 'Admission Date', 'Status', 'Guardian Name', 'Guardian Phone']);
        
        // Data
        foreach ($students as $student) {
            fputcsv($output, [
                $student['student_id'],
                $student['first_name'],
                $student['last_name'],
                $student['gender'],
                $student['date_of_birth'],
                $student['class_name'],
                $student['section_name'],
                $student['category_name'],
                $student['admission_date'],
                $student['status'],
                $student['guardian_name'],
                $student['guardian_phone']
            ]);
        }
        
        fclose($output);
        exit;
    }
    
    /**
     * Bulk Import from CSV
     */
    public function import() {
        $this->requireRole(['super_admin', 'admin']);
        
        if ($this->isPost()) {
            if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] == 0) {
                $file = $_FILES['csv_file']['tmp_name'];
                $handle = fopen($file, "r");
                
                // Skip header
                fgetcsv($handle);
                
                $model = $this->model('students');
                $successCount = 0;
                $errorCount = 0;
                
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if (count($data) < 8) {
                        $errorCount++;
                        continue;
                    }
                    
                    // Column mapping: First Name, Last Name, Gender, DOB, Section ID, Guardian Name, Guardian Phone, Admission Date
                    $studentData = [
                        'student_id' => $this->generateStudentId(),
                        'first_name' => Security::clean($data[0]),
                        'last_name' => Security::clean($data[1]),
                        'gender' => strtolower(Security::clean($data[2])),
                        'date_of_birth' => Security::clean($data[3]),
                        'section_id' => (int)$data[4],
                        'guardian_name' => Security::clean($data[5]),
                        'guardian_phone' => Security::clean($data[6]),
                        'admission_date' => Security::clean($data[7]),
                        'status' => 'active'
                    ];
                    
                    if ($model->addStudent($studentData)) {
                        $successCount++;
                    } else {
                        $errorCount++;
                    }
                }
                
                fclose($handle);
                $this->setFlash('success', "Import complete! Imported: {$successCount}, Errors: {$errorCount}");
            } else {
                $this->setFlash('error', "Please select a valid CSV file.");
            }
            $this->redirect('students');
        }
    }
}
