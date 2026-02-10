-- Examination & Grading Module Migration
-- Compatible with MySQL 5.6+

-- 1. Exam Types
CREATE TABLE IF NOT EXISTS exam_types (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL UNIQUE, -- Mid-Term, Final, Class Test, Project
    contribution_percentage DECIMAL(5,2) DEFAULT 0.00, -- e.g. 30% for Mid-term
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Grade Scales
CREATE TABLE IF NOT EXISTS exam_grade_scales (
    id INT PRIMARY KEY AUTO_INCREMENT,
    grade VARCHAR(5) NOT NULL, -- A, B+, etc.
    min_score DECIMAL(5,2) NOT NULL, -- e.g. 80.00
    max_score DECIMAL(5,2) NOT NULL, -- e.g. 100.00
    remark VARCHAR(100), -- Excellent, Very Good
    point_value DECIMAL(3,2) DEFAULT 0.00, -- GPA point e.g. 4.0
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Exams
CREATE TABLE IF NOT EXISTS exams (
    id INT PRIMARY KEY AUTO_INCREMENT,
    exam_type_id INT NOT NULL,
    subject_id INT NOT NULL,
    academic_year_id INT NOT NULL,
    term_id INT NOT NULL,
    exam_date DATE,
    max_marks INT DEFAULT 100,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (exam_type_id) REFERENCES exam_types(id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
    FOREIGN KEY (academic_year_id) REFERENCES academic_years(id) ON DELETE CASCADE,
    FOREIGN KEY (term_id) REFERENCES terms(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Exam Marks
CREATE TABLE IF NOT EXISTS exam_marks (
    id INT PRIMARY KEY AUTO_INCREMENT,
    exam_id INT NOT NULL,
    student_id INT NOT NULL,
    marks_obtained DECIMAL(5,2) NOT NULL,
    teacher_comment TEXT,
    recorded_by INT NOT NULL,
    recorded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (exam_id) REFERENCES exams(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (recorded_by) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY idx_exam_student (exam_id, student_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Terminal Report Cards
CREATE TABLE IF NOT EXISTS exam_report_cards (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    term_id INT NOT NULL,
    total_score DECIMAL(7,2),
    average_score DECIMAL(5,2),
    grade VARCHAR(5),
    position INT,
    promoted_to_class_id INT, -- For end of year
    teacher_remarks TEXT,
    principal_remarks TEXT,
    is_published BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (term_id) REFERENCES terms(id) ON DELETE CASCADE,
    FOREIGN KEY (promoted_to_class_id) REFERENCES classes(id) ON DELETE SET NULL,
    UNIQUE KEY idx_student_term (student_id, term_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Initial Data
INSERT IGNORE INTO exam_types (name, contribution_percentage) VALUES 
('Class Test', 10.00),
('Assignment', 10.00),
('Mid-Term Exam', 30.00),
('End of Term Exam', 50.00);

INSERT IGNORE INTO exam_grade_scales (grade, min_score, max_score, remark, point_value) VALUES 
('A1', 80.00, 100.00, 'Excellent', 4.00),
('B2', 70.00, 79.99, 'Very Good', 3.50),
('B3', 60.00, 69.99, 'Good', 3.00),
('C4', 55.00, 59.99, 'Credit', 2.50),
('C5', 50.00, 54.99, 'Credit', 2.00),
('C6', 45.00, 49.99, 'Credit', 1.50),
('D7', 40.00, 44.99, 'Pass', 1.00),
('E8', 35.00, 39.99, 'Pass', 0.50),
('F9', 0.00, 34.99, 'Fail', 0.00);
