-- Safeguarding & Child Protection Module Migration
-- Compatible with MySQL 5.6+

-- 1. Safeguarding Concerns
CREATE TABLE IF NOT EXISTS safeguarding_concerns (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    recorded_by INT NOT NULL,
    severity ENUM('low', 'medium', 'high', 'critical') DEFAULT 'low',
    status ENUM('open', 'in_progress', 'referred', 'closed') DEFAULT 'open',
    category ENUM('neglect', 'physical_abuse', 'emotional_abuse', 'sexual_abuse', 'behavioral', 'family_issues', 'other') NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    is_confidential BOOLEAN DEFAULT 1,
    incident_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (recorded_by) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Chronology of Events (Actions Taken)
CREATE TABLE IF NOT EXISTS safeguarding_actions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    concern_id INT NOT NULL,
    action_by INT NOT NULL,
    action_type VARCHAR(100) NOT NULL, -- e.g., 'Internal Meeting', 'Parent Contact', 'Referral'
    description TEXT NOT NULL,
    action_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (concern_id) REFERENCES safeguarding_concerns(id) ON DELETE CASCADE,
    FOREIGN KEY (action_by) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Multi-agency Referrals
CREATE TABLE IF NOT EXISTS safeguarding_referrals (
    id INT PRIMARY KEY AUTO_INCREMENT,
    concern_id INT NOT NULL,
    agency_name VARCHAR(150) NOT NULL,
    referral_date DATE NOT NULL,
    contact_person VARCHAR(100),
    contact_info TEXT,
    reference_number VARCHAR(50),
    status ENUM('pending', 'accepted', 'rejected', 'ongoing', 'closed') DEFAULT 'pending',
    outcome TEXT,
    FOREIGN KEY (concern_id) REFERENCES safeguarding_concerns(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Safeguarding Audit Logs (Strict accountability)
CREATE TABLE IF NOT EXISTS safeguarding_audit_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    concern_id INT,
    student_id INT,
    action ENUM('view', 'create', 'update', 'delete', 'anonymize') NOT NULL,
    detail TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Safeguarding Attachments
CREATE TABLE IF NOT EXISTS safeguarding_attachments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    concern_id INT NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_type VARCHAR(50),
    uploaded_by INT NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (concern_id) REFERENCES safeguarding_concerns(id) ON DELETE CASCADE,
    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Add Safeguarding Lead flag to users table
ALTER TABLE users ADD COLUMN is_safeguarding_lead BOOLEAN DEFAULT 0;

-- Update super_admin to be safeguarding lead by default
UPDATE users SET is_safeguarding_lead = 1 WHERE role = 'super_admin';
