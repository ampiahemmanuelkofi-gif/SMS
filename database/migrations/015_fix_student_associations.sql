-- Fix Missing Student Tables
-- Compatible with MySQL 5.6+

-- 19. Student Categories
CREATE TABLE IF NOT EXISTS student_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    description VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add category_id to students table if not exists
SET @dropdown = (SELECT COUNT(*) FROM information_schema.columns WHERE table_name = 'students' AND column_name = 'category_id' AND table_schema = DATABASE());
SET @sql = IF(@dropdown = 0, 'ALTER TABLE students ADD COLUMN category_id INT AFTER photo', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- 20. Student Medical Info
CREATE TABLE IF NOT EXISTS student_medical (
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
CREATE TABLE IF NOT EXISTS student_disciplinary (
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

-- 22. Student Siblings
CREATE TABLE IF NOT EXISTS student_siblings (
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
CREATE TABLE IF NOT EXISTS student_documents (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    document_name VARCHAR(150) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    document_type VARCHAR(50),
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 24. Student Custom Fields
CREATE TABLE IF NOT EXISTS student_custom_fields (
    id INT PRIMARY KEY AUTO_INCREMENT,
    field_name VARCHAR(50) NOT NULL,
    field_type ENUM('text', 'number', 'date', 'select') DEFAULT 'text',
    field_options TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS student_custom_values (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    field_id INT NOT NULL,
    field_value TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (field_id) REFERENCES student_custom_fields(id) ON DELETE CASCADE,
    UNIQUE KEY unique_student_field (student_id, field_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
