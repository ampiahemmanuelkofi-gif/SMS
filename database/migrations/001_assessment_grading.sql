-- Grading Systems
CREATE TABLE IF NOT EXISTS grading_systems (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Grading Scales
CREATE TABLE IF NOT EXISTS grading_scales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    system_id INT NOT NULL,
    grade VARCHAR(10) NOT NULL,
    min_score DECIMAL(5,2) NOT NULL,
    max_score DECIMAL(5,2) NOT NULL,
    gpa_point DECIMAL(4,2) DEFAULT 0.00,
    remark VARCHAR(100),
    FOREIGN KEY (system_id) REFERENCES grading_systems(id) ON DELETE CASCADE
);

-- Assessment Categories (Groupings like Classwork, Homework, Exam)
CREATE TABLE IF NOT EXISTS assessment_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Subject Weights (Configuration per class/subject)
CREATE TABLE IF NOT EXISTS subject_weights (
    id INT AUTO_INCREMENT PRIMARY KEY,
    class_id INT NOT NULL,
    subject_id INT NOT NULL,
    category_id INT NOT NULL,
    weight_percent DECIMAL(5,2) NOT NULL,
    term_id INT NOT NULL, -- Added term_id to allow different weights per term
    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES assessment_categories(id) ON DELETE CASCADE,
    FOREIGN KEY (term_id) REFERENCES terms(id) ON DELETE CASCADE
);

-- Skills (Non-academic)
CREATE TABLE IF NOT EXISTS skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL, -- Affective, Psychomotor
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Student Skill Ratings
CREATE TABLE IF NOT EXISTS student_skill_ratings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    term_id INT NOT NULL,
    skill_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (term_id) REFERENCES terms(id) ON DELETE CASCADE,
    FOREIGN KEY (skill_id) REFERENCES skills(id) ON DELETE CASCADE
);

-- Seed default data
INSERT INTO grading_systems (name, description) VALUES 
('WAEC (WASSCE)', 'West African Senior SchoolCertificate Examination'),
('GES (Basic)', 'Ghana Education Service Basic Education Certificate Examination'),
('GPA 4.0', 'Standard 4.0 GPA Scale');

-- Seed Categories
INSERT INTO assessment_categories (name) VALUES ('Classwork'), ('Homework'), ('Project'), ('Mid-term'), ('Final Exam');
