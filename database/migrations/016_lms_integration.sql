-- LMS Integration Module Migration
-- Compatible with MySQL 5.6+

-- 1. LMS Platforms Configuration
CREATE TABLE IF NOT EXISTS lms_platforms (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL, -- 'Google Classroom', 'Microsoft Teams', 'Moodle'
    platform_key VARCHAR(50) UNIQUE NOT NULL, -- 'google', 'microsoft', 'moodle'
    client_id TEXT,
    client_secret TEXT,
    redirect_uri TEXT,
    base_url TEXT, -- For Moodle
    is_active BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. LMS Course Mapping
CREATE TABLE IF NOT EXISTS lms_course_mapping (
    id INT PRIMARY KEY AUTO_INCREMENT,
    section_id INT NOT NULL,
    subject_id INT NOT NULL,
    platform_id INT NOT NULL,
    external_course_id VARCHAR(100) NOT NULL,
    external_course_name VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (section_id) REFERENCES sections(id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
    FOREIGN KEY (platform_id) REFERENCES lms_platforms(id) ON DELETE CASCADE,
    UNIQUE KEY unique_mapping (section_id, subject_id, platform_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. LMS Assignments (Synced)
CREATE TABLE IF NOT EXISTS lms_assignments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    course_mapping_id INT NOT NULL,
    external_assignment_id VARCHAR(100) NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    due_date DATETIME,
    max_points DECIMAL(10,2),
    sync_status ENUM('synced', 'pending', 'error') DEFAULT 'synced',
    last_synced_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_mapping_id) REFERENCES lms_course_mapping(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Content Repository
CREATE TABLE IF NOT EXISTS lms_content (
    id INT PRIMARY KEY AUTO_INCREMENT,
    subject_id INT,
    title VARCHAR(255) NOT NULL,
    content_type ENUM('file', 'video_link', 'text') NOT NULL,
    content_value TEXT, -- File path or video URL or text
    is_public BOOLEAN DEFAULT 1,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Online Quizzes
CREATE TABLE IF NOT EXISTS lms_quizzes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    subject_id INT,
    title VARCHAR(255) NOT NULL,
    duration_minutes INT,
    passing_score DECIMAL(5,2),
    is_active BOOLEAN DEFAULT 1,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Quiz Questions
CREATE TABLE IF NOT EXISTS lms_quiz_questions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    quiz_id INT NOT NULL,
    question_text TEXT NOT NULL,
    question_type ENUM('multiple_choice', 'true_false', 'short_answer') DEFAULT 'multiple_choice',
    points DECIMAL(5,2) DEFAULT 1.00,
    options_json TEXT, -- For multiple choice
    correct_answer TEXT,
    FOREIGN KEY (quiz_id) REFERENCES lms_quizzes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default platforms
INSERT INTO lms_platforms (name, platform_key) VALUES 
('Google Classroom', 'google'),
('Microsoft Teams', 'microsoft'),
('Moodle', 'moodle')
ON DUPLICATE KEY UPDATE name = VALUES(name);
