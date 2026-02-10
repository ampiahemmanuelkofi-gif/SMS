-- Boarding/Hostel Management Module Migration
-- Compatible with MySQL 5.6+

-- 1. Hostels
CREATE TABLE IF NOT EXISTS hostel_hostels (
    id INT PRIMARY KEY AUTO_INCREMENT,
    hostel_name VARCHAR(100) NOT NULL UNIQUE,
    hostel_type ENUM('boys', 'girls', 'mixed') NOT NULL,
    capacity INT NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Rooms
CREATE TABLE IF NOT EXISTS hostel_rooms (
    id INT PRIMARY KEY AUTO_INCREMENT,
    hostel_id INT NOT NULL,
    room_number VARCHAR(20) NOT NULL,
    floor VARCHAR(20),
    capacity INT NOT NULL, -- Number of beds in this room
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (hostel_id) REFERENCES hostel_hostels(id) ON DELETE CASCADE,
    UNIQUE KEY idx_hostel_room (hostel_id, room_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Beds
CREATE TABLE IF NOT EXISTS hostel_beds (
    id INT PRIMARY KEY AUTO_INCREMENT,
    room_id INT NOT NULL,
    bed_number VARCHAR(20) NOT NULL,
    status ENUM('available', 'occupied', 'maintenance') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (room_id) REFERENCES hostel_rooms(id) ON DELETE CASCADE,
    UNIQUE KEY idx_room_bed (room_id, bed_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Allocations
CREATE TABLE IF NOT EXISTS hostel_allocations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    bed_id INT NOT NULL,
    academic_year_id INT, -- Link to academics if needed
    allotted_on DATE NOT NULL,
    vacated_on DATE NULL,
    status ENUM('active', 'vacated') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (bed_id) REFERENCES hostel_beds(id) ON DELETE CASCADE,
    UNIQUE KEY idx_student_active_bed (student_id, bed_id, status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Leave Management (Outings/Holidays)
CREATE TABLE IF NOT EXISTS hostel_leave (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    leave_type ENUM('weekend', 'holiday', 'medical', 'other') NOT NULL,
    out_date DATETIME NOT NULL,
    expected_in_date DATETIME NOT NULL,
    actual_in_date DATETIME NULL,
    reason TEXT,
    status ENUM('pending', 'approved', 'rejected', 'in_progress', 'returned') DEFAULT 'pending',
    approved_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Discipline & Incidents
CREATE TABLE IF NOT EXISTS hostel_incidents (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    incident_date DATE NOT NULL,
    category ENUM('noise', 'curfew', 'cleanliness', 'theft', 'other') NOT NULL,
    description TEXT NOT NULL,
    action_taken TEXT,
    reported_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (reported_by) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Initial Data
INSERT INTO hostel_hostels (hostel_name, hostel_type, capacity) VALUES 
('Nelson Mandela Hall', 'boys', 100),
('Florence Nightingale Hall', 'girls', 100);

-- Room for Mandela Hall
INSERT INTO hostel_rooms (hostel_id, room_number, floor, capacity) VALUES 
(1, 'M-101', 'Ground', 4),
(1, 'M-102', 'Ground', 4);

-- Beds for Room M-101
INSERT INTO hostel_beds (room_id, bed_number) VALUES 
(1, 'B-1'), (1, 'B-2'), (1, 'B-3'), (1, 'B-4');
