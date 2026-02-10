-- API & Integration Platform Migration
-- Compatible with MySQL 5.6+

-- 1. API Keys
CREATE TABLE IF NOT EXISTS api_keys (
    id INT PRIMARY KEY AUTO_INCREMENT,
    client_name VARCHAR(100) NOT NULL,
    api_key VARCHAR(64) NOT NULL UNIQUE,
    api_secret VARCHAR(64) NOT NULL,
    permissions TEXT, -- JSON or comma-separated list
    is_active BOOLEAN DEFAULT TRUE,
    expires_at DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_used_at DATETIME
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Webhooks
CREATE TABLE IF NOT EXISTS api_webhooks (
    id INT PRIMARY KEY AUTO_INCREMENT,
    event_type VARCHAR(50) NOT NULL, -- student.created, exam.published, etc.
    target_url TEXT NOT NULL,
    secret_token VARCHAR(100), -- For payload verification
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. API Logs (Rate Limiting & Auditing)
CREATE TABLE IF NOT EXISTS api_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    api_key_id INT NOT NULL,
    endpoint VARCHAR(255) NOT NULL,
    method VARCHAR(10) NOT NULL,
    ip_address VARCHAR(45),
    response_code INT,
    duration_ms INT,
    requested_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (api_key_id) REFERENCES api_keys(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed a Default Sandbox Key (For dev/docs)
INSERT IGNORE INTO api_keys (client_name, api_key, api_secret, permissions) VALUES 
('Sandbox Client', 'sb_key_dev_12345', 'sb_secret_dev_67890', 'all');
