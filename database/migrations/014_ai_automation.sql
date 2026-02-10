-- AI & Automation Module Migration
-- Compatible with MySQL 5.6+

-- 1. AI Predictions & Alerts
CREATE TABLE IF NOT EXISTS ai_predictions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    prediction_type VARCHAR(50) NOT NULL, -- 'performance_drop', 'attendance_risk', 'success_prob'
    score_prediction DECIMAL(5,2),
    confidence_score DECIMAL(5,2),
    reasoning TEXT, -- Brief explanation of why the AI made this prediction
    is_notified BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Automation Logs
CREATE TABLE IF NOT EXISTS ai_automation_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    action_type VARCHAR(50) NOT NULL, -- 'fee_reminder', 'substitution_suggestion', 'report_comment'
    data_json TEXT, -- Parameters used for the action
    status VARCHAR(20) DEFAULT 'success',
    executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    performed_by INT,
    FOREIGN KEY (performed_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. AI Timetable Drafts
CREATE TABLE IF NOT EXISTS ai_timetable_drafts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    academic_year_id INT NOT NULL,
    term_id INT NOT NULL,
    config_json TEXT, -- Constraints and rules used for generation
    timetable_json LONGTEXT, -- Generated schedule data
    version INT DEFAULT 1,
    is_active BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (academic_year_id) REFERENCES academic_years(id) ON DELETE CASCADE,
    FOREIGN KEY (term_id) REFERENCES terms(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed some sample prediction types in a comment or meta table if needed
