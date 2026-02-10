-- Communication Hub Module Migration
-- Compatible with MySQL 5.6+

-- 1. Announcements
CREATE TABLE IF NOT EXISTS communication_announcements (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    target_audience ENUM('all', 'staff', 'students', 'parents') DEFAULT 'all',
    posted_by INT NOT NULL,
    is_published TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (posted_by) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Private Messages
CREATE TABLE IF NOT EXISTS communication_messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    subject VARCHAR(255),
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    parent_id INT DEFAULT NULL, -- For threading
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (parent_id) REFERENCES communication_messages(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Communication Logs (Email/SMS tracking)
CREATE TABLE IF NOT EXISTS communication_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    type ENUM('email', 'sms', 'push') NOT NULL,
    recipient VARCHAR(255) NOT NULL,
    subject VARCHAR(255),
    content TEXT,
    status ENUM('pending', 'sent', 'failed') DEFAULT 'pending',
    error_message TEXT,
    sent_at DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Message Templates
CREATE TABLE IF NOT EXISTS communication_templates (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    subject VARCHAR(255),
    body TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Initial Template and Announcement
INSERT INTO communication_templates (name, subject, body) VALUES 
('Fee Reminder', 'School Fee Payment Reminder', 'Dear Parent, this is a reminder regarding the outstanding fees for your ward. Please settle as soon as possible.'),
('Event Invite', 'School Event Invitation', 'You are cordially invited to our upcoming school event on {date}. We look forward to seeing you!');

INSERT INTO communication_announcements (title, content, target_audience, posted_by) VALUES 
('Welcome to the New Term', 'We are excited to welcome all students back for the new academic term. Let\'s make it a great one!', 'all', 1);
