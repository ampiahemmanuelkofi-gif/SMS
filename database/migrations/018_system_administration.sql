-- System Administration Migration
-- Compatible with MySQL 5.6+

-- Add missing columns to users table
ALTER TABLE users ADD COLUMN last_login DATETIME AFTER is_active;

-- 1. System Settings table
CREATE TABLE IF NOT EXISTS system_settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_key (setting_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Audit Logs table
CREATE TABLE IF NOT EXISTS audit_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    action VARCHAR(100) NOT NULL,
    detail TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user (user_id),
    INDEX idx_action (action),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed default settings
INSERT INTO system_settings (setting_key, setting_value) VALUES 
('school_name', 'EAMP School Pro'),
('admin_email', 'admin@school.edu'),
('brand_color', '#4e73df'),
('timezone', 'Asia/Dubai')
ON DUPLICATE KEY UPDATE updated_at = updated_at;
