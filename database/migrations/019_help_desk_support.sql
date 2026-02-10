-- Help Desk & Support Migration
-- Compatible with MySQL 5.6+

-- 1. Support Tickets
CREATE TABLE IF NOT EXISTS support_tickets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    subject VARCHAR(255) NOT NULL,
    category ENUM('bug', 'technical', 'billing', 'feature_request', 'other') DEFAULT 'technical',
    priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
    status ENUM('open', 'in_progress', 'resolved', 'closed') DEFAULT 'open',
    description TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Ticket Replies
CREATE TABLE IF NOT EXISTS ticket_replies (
    id INT PRIMARY KEY AUTO_INCREMENT,
    ticket_id INT NOT NULL,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ticket_id) REFERENCES support_tickets(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Knowledge Base / FAQ
CREATE TABLE IF NOT EXISTS support_faq (
    id INT PRIMARY KEY AUTO_INCREMENT,
    question VARCHAR(255) NOT NULL,
    answer TEXT NOT NULL,
    category VARCHAR(50) DEFAULT 'General',
    view_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_category (category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Feature Requests & Voting
CREATE TABLE IF NOT EXISTS feature_requests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    votes INT DEFAULT 0,
    status ENUM('pending', 'planned', 'in_development', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Feature Request Votes
CREATE TABLE IF NOT EXISTS feature_votes (
    request_id INT NOT NULL,
    user_id INT NOT NULL,
    PRIMARY KEY (request_id, user_id),
    FOREIGN KEY (request_id) REFERENCES feature_requests(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed initial FAQ
INSERT INTO support_faq (question, answer, category) VALUES 
('How do I reset my password?', 'To reset your password, click on "Forgot Password" on the login screen or contact your system administrator.', 'Account'),
('How to mark attendance offline?', 'The mobile app supports offline attendance. Your data will sync automatically once an internet connection is established.', 'Mobile App'),
('Where can I find student report cards?', 'Report cards are available in the Academics > Reports section or through the iParent portal.', 'Academic');
