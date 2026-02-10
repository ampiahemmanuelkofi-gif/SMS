-- School Management System Database Schema
-- Compatible with MySQL 5.6+

-- Create database
CREATE DATABASE IF NOT EXISTS school_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE school_management;

-- 1. Users table (All roles: super_admin, admin, teacher, accountant, parent)
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    role ENUM('super_admin','admin','teacher','accountant','parent') NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20),
    full_name VARCHAR(100) NOT NULL,
    is_active BOOLEAN DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Academic years
CREATE TABLE academic_years (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(20) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    is_current BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_current (is_current)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Terms (Ghana: 1st, 2nd, 3rd Term)
CREATE TABLE terms (
    id INT PRIMARY KEY AUTO_INCREMENT,
    academic_year_id INT NOT NULL,
    name ENUM('1st Term','2nd Term','3rd Term') NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    is_current BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (academic_year_id) REFERENCES academic_years(id) ON DELETE CASCADE,
    INDEX idx_current (is_current)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Classes (Nursery 1 through JHS 3)
CREATE TABLE classes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(20) NOT NULL,
    level ENUM('nursery','primary','jhs') NOT NULL,
    capacity INT DEFAULT 40,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_level (level)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Sections (A, B, C)
CREATE TABLE sections (
    id INT PRIMARY KEY AUTO_INCREMENT,
    class_id INT NOT NULL,
    name VARCHAR(10) NOT NULL,
    class_teacher_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE,
    FOREIGN KEY (class_teacher_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_class (class_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Students
CREATE TABLE students (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    student_id VARCHAR(20) UNIQUE NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    date_of_birth DATE NOT NULL,
    gender ENUM('male','female','other') NOT NULL,
    guardian_name VARCHAR(100) NOT NULL,
    guardian_phone VARCHAR(20) NOT NULL,
    guardian_email VARCHAR(100),
    address TEXT,
    photo VARCHAR(255),
    section_id INT,
    admission_date DATE NOT NULL,
    status ENUM('active','graduated','transferred','suspended') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (section_id) REFERENCES sections(id) ON DELETE SET NULL,
    INDEX idx_student_id (student_id),
    INDEX idx_section (section_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 7. Subjects
CREATE TABLE subjects (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    code VARCHAR(10) NOT NULL UNIQUE,
    is_core BOOLEAN DEFAULT 1,
    level ENUM('nursery','primary','jhs','all') DEFAULT 'all',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_level (level)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 8. Teacher assignments (Who teaches what)
CREATE TABLE teacher_assignments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    teacher_id INT NOT NULL,
    section_id INT NOT NULL,
    subject_id INT NOT NULL,
    academic_year_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (teacher_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (section_id) REFERENCES sections(id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
    FOREIGN KEY (academic_year_id) REFERENCES academic_years(id) ON DELETE CASCADE,
    INDEX idx_teacher (teacher_id),
    INDEX idx_section (section_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 9. Attendance (Daily roll call)
CREATE TABLE attendance (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    date DATE NOT NULL,
    status ENUM('present','absent','late','excused') DEFAULT 'present',
    term_id INT NOT NULL,
    marked_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (term_id) REFERENCES terms(id) ON DELETE CASCADE,
    FOREIGN KEY (marked_by) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_attendance (student_id, date),
    INDEX idx_date (date),
    INDEX idx_student (student_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 10. Assessments (Marks/Grades)
CREATE TABLE assessments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    subject_id INT NOT NULL,
    term_id INT NOT NULL,
    assessment_type ENUM('classwork','homework','midterm','final','mock_bece') NOT NULL,
    marks_obtained DECIMAL(5,2) NOT NULL,
    marks_maximum DECIMAL(5,2) DEFAULT 100,
    remarks TEXT,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
    FOREIGN KEY (term_id) REFERENCES terms(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_student (student_id),
    INDEX idx_term (term_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 11. BECE results (Specific for JHS 3)
CREATE TABLE bece_results (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    academic_year_id INT NOT NULL,
    english_grade ENUM('A1','B2','B3','C4','C5','C6','D7','E8','F9'),
    maths_grade ENUM('A1','B2','B3','C4','C5','C6','D7','E8','F9'),
    science_grade ENUM('A1','B2','B3','C4','C5','C6','D7','E8','F9'),
    social_grade ENUM('A1','B2','B3','C4','C5','C6','D7','E8','F9'),
    elective1_grade ENUM('A1','B2','B3','C4','C5','C6','D7','E8','F9'),
    elective2_grade ENUM('A1','B2','B3','C4','C5','C6','D7','E8','F9'),
    aggregate INT,
    placement_school VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (academic_year_id) REFERENCES academic_years(id) ON DELETE CASCADE,
    UNIQUE KEY unique_bece (student_id, academic_year_id),
    INDEX idx_student (student_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 12. Fee structure
CREATE TABLE fee_structure (
    id INT PRIMARY KEY AUTO_INCREMENT,
    class_id INT NOT NULL,
    term_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    description VARCHAR(100) NOT NULL,
    due_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE,
    FOREIGN KEY (term_id) REFERENCES terms(id) ON DELETE CASCADE,
    INDEX idx_class (class_id),
    INDEX idx_term (term_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 13. Fee payments
CREATE TABLE fee_payments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_date DATE NOT NULL,
    payment_method ENUM('cash','mobile_money','bank_transfer','cheque') NOT NULL,
    reference_number VARCHAR(50),
    received_by INT NOT NULL,
    term_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (received_by) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (term_id) REFERENCES terms(id) ON DELETE CASCADE,
    INDEX idx_student (student_id),
    INDEX idx_term (term_id),
    INDEX idx_date (payment_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 14. Notices (School announcements)
CREATE TABLE notices (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    target_audience ENUM('all','teachers','parents','students') DEFAULT 'all',
    posted_by INT NOT NULL,
    expires_at DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (posted_by) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_audience (target_audience),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 15. Homework (Simple assignment tracking)
CREATE TABLE homework (
    id INT PRIMARY KEY AUTO_INCREMENT,
    section_id INT NOT NULL,
    subject_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    due_date DATE NOT NULL,
    attachment VARCHAR(255),
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (section_id) REFERENCES sections(id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_section (section_id),
    INDEX idx_due_date (due_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 16. Settings (School configuration)
CREATE TABLE settings (
    setting_key VARCHAR(50) PRIMARY KEY,
    setting_value TEXT,
    description VARCHAR(255),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 17. Login logs (Security)
CREATE TABLE login_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    login_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),
    user_agent TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_time (login_time)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 18. Promotions (Student class progression)
CREATE TABLE promotions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    from_section_id INT NOT NULL,
    to_section_id INT NOT NULL,
    academic_year_id INT NOT NULL,
    promoted_by INT NOT NULL,
    promotion_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (from_section_id) REFERENCES sections(id) ON DELETE CASCADE,
    FOREIGN KEY (to_section_id) REFERENCES sections(id) ON DELETE CASCADE,
    FOREIGN KEY (academic_year_id) REFERENCES academic_years(id) ON DELETE CASCADE,
    FOREIGN KEY (promoted_by) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_student (student_id),
    INDEX idx_year (academic_year_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 19. Student Categories
CREATE TABLE student_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    description VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add category_id to students table
ALTER TABLE students ADD COLUMN category_id INT AFTER photo;
ALTER TABLE students ADD FOREIGN KEY (category_id) REFERENCES student_categories(id) ON DELETE SET NULL;

-- 20. Student Medical Info
CREATE TABLE student_medical (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    blood_group VARCHAR(5),
    allergies TEXT,
    medical_conditions TEXT,
    emergency_contact_name VARCHAR(100),
    emergency_contact_phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    UNIQUE KEY unique_student_medical (student_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 21. Student Disciplinary Records
CREATE TABLE student_disciplinary (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    incident_date DATE NOT NULL,
    incident_description TEXT NOT NULL,
    action_taken TEXT,
    recorded_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (recorded_by) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 22. Student Siblings (Family linking)
CREATE TABLE student_siblings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    sibling_student_id INT NOT NULL,
    relationship_type VARCHAR(50) DEFAULT 'Sibling',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (sibling_student_id) REFERENCES students(id) ON DELETE CASCADE,
    UNIQUE KEY unique_sibling_pair (student_id, sibling_student_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 23. Student Documents
CREATE TABLE student_documents (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    document_name VARCHAR(150) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    document_type VARCHAR(50),
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 24. Student Custom Fields
CREATE TABLE student_custom_fields (
    id INT PRIMARY KEY AUTO_INCREMENT,
    field_name VARCHAR(50) NOT NULL,
    field_type ENUM('text', 'number', 'date', 'select') DEFAULT 'text',
    field_options TEXT, -- JSON or comma-separated for 'select' type
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE student_custom_values (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    field_id INT NOT NULL,
    field_value TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (field_id) REFERENCES student_custom_fields(id) ON DELETE CASCADE,
    UNIQUE KEY unique_student_field (student_id, field_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
-- 25. Admissions (Applications)
CREATE TABLE admissions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    application_number VARCHAR(20) UNIQUE NOT NULL,
    academic_year_id INT NOT NULL,
    class_id INT NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    date_of_birth DATE NOT NULL,
    gender ENUM('male','female','other') NOT NULL,
    guardian_name VARCHAR(100) NOT NULL,
    guardian_phone VARCHAR(20) NOT NULL,
    guardian_email VARCHAR(100),
    address TEXT,
    status ENUM('pending', 'interview_scheduled', 'tested', 'offered', 'accepted', 'rejected', 'waitlisted', 'enrolled') DEFAULT 'pending',
    application_fee_status ENUM('unpaid', 'paid', 'waived') DEFAULT 'unpaid',
    agent_id INT,
    source VARCHAR(50), -- e.g., 'Website', 'Referral', 'Agent'
    score_total DECIMAL(5,2) DEFAULT 0,
    waiting_list_rank INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (academic_year_id) REFERENCES academic_years(id),
    FOREIGN KEY (class_id) REFERENCES classes(id),
    INDEX idx_status (status),
    INDEX idx_app_no (application_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 26. Admission Documents
CREATE TABLE admission_documents (
    id INT PRIMARY KEY AUTO_INCREMENT,
    admission_id INT NOT NULL,
    document_name VARCHAR(150) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    is_verified BOOLEAN DEFAULT 0,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admission_id) REFERENCES admissions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 27. Admission Tests
CREATE TABLE admission_tests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    admission_id INT NOT NULL,
    test_name VARCHAR(100) NOT NULL,
    score DECIMAL(5,2),
    max_score DECIMAL(5,2) DEFAULT 100,
    remarks TEXT,
    test_date DATE,
    FOREIGN KEY (admission_id) REFERENCES admissions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 28. Admission Interviews
CREATE TABLE admission_interviews (
    id INT PRIMARY KEY AUTO_INCREMENT,
    admission_id INT NOT NULL,
    interview_date DATETIME NOT NULL,
    interviewer_id INT,
    feedback TEXT,
    recommendation ENUM('strongly_recommend', 'recommend', 'neutral', 'do_not_recommend'),
    status ENUM('scheduled', 'completed', 'cancelled', 'no_show') DEFAULT 'scheduled',
    FOREIGN KEY (admission_id) REFERENCES admissions(id) ON DELETE CASCADE,
    FOREIGN KEY (interviewer_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 29. Admission Enquiries (Prospects)
CREATE TABLE admission_enquiries (
    id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20),
    interested_class_id INT,
    status ENUM('new', 'contacted', 'applying', 'converted', 'lost') DEFAULT 'new',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (interested_class_id) REFERENCES classes(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 30. Admission Notes (Communication Tracking)
CREATE TABLE admission_notes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    admission_id INT,
    enquiry_id INT,
    note TEXT NOT NULL,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admission_id) REFERENCES admissions(id) ON DELETE SET NULL,
    FOREIGN KEY (enquiry_id) REFERENCES admission_enquiries(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
-- 31. Academic Programs (Curricula)
CREATE TABLE academic_programs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL UNIQUE, -- e.g., 'GES', 'British Curricula', 'IB'
    description VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add program_id to classes
ALTER TABLE classes ADD COLUMN program_id INT AFTER level;
ALTER TABLE classes ADD FOREIGN KEY (program_id) REFERENCES academic_programs(id) ON DELETE SET NULL;

-- 32. School Rooms
CREATE TABLE school_rooms (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    capacity INT DEFAULT 30,
    type ENUM('classroom', 'lab', 'library', 'hall', 'field') DEFAULT 'classroom',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 33. Class Timetable
CREATE TABLE class_timetable (
    id INT PRIMARY KEY AUTO_INCREMENT,
    academic_year_id INT NOT NULL,
    term_id INT NOT NULL,
    class_id INT NOT NULL,
    section_id INT NOT NULL,
    subject_id INT NOT NULL,
    teacher_id INT,
    room_id INT,
    day_of_week ENUM('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday') NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (academic_year_id) REFERENCES academic_years(id) ON DELETE CASCADE,
    FOREIGN KEY (term_id) REFERENCES terms(id) ON DELETE CASCADE,
    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE,
    FOREIGN KEY (section_id) REFERENCES sections(id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
    FOREIGN KEY (teacher_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (room_id) REFERENCES school_rooms(id) ON DELETE SET NULL,
    INDEX idx_schedule (day_of_week, start_time),
    INDEX idx_teacher_time (teacher_id, day_of_week, start_time) -- For conflict detection
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 34. Lesson Plans
CREATE TABLE lesson_plans (
    id INT PRIMARY KEY AUTO_INCREMENT,
    teacher_id INT NOT NULL,
    subject_id INT NOT NULL,
    class_id INT NOT NULL,
    week_number INT NOT NULL,
    topic VARCHAR(200) NOT NULL,
    objectives TEXT,
    materials_needed TEXT,
    content TEXT,
    status ENUM('draft', 'submitted', 'approved', 'rejected') DEFAULT 'draft',
    approval_feedback TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (teacher_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 35. Exam Schedules
CREATE TABLE exam_schedules (
    id INT PRIMARY KEY AUTO_INCREMENT,
    academic_year_id INT NOT NULL,
    term_id INT NOT NULL,
    exam_name VARCHAR(100) NOT NULL, -- e.g., 'Mid-Term 1', 'Finals'
    class_id INT NOT NULL,
    subject_id INT NOT NULL,
    exam_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    room_id INT,
    invigilator_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (academic_year_id) REFERENCES academic_years(id) ON DELETE CASCADE,
    FOREIGN KEY (term_id) REFERENCES terms(id) ON DELETE CASCADE,
    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES school_rooms(id) ON DELETE SET NULL,
    FOREIGN KEY (invigilator_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
