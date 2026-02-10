-- Health & Medical Module Migration
-- Compatible with MySQL 5.6+

-- 1. Medical Records (Folders)
CREATE TABLE IF NOT EXISTS medical_records (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL, -- Student or Staff
    blood_group ENUM('A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'),
    allergies TEXT,
    chronic_conditions TEXT,
    immunization_history TEXT,
    emergency_contact_name VARCHAR(100),
    emergency_contact_phone VARCHAR(20),
    family_doctor_info TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY idx_user_medical (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Daily Clinic Visits
CREATE TABLE IF NOT EXISTS medical_clinic_visits (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    visit_date DATETIME NOT NULL,
    symptoms TEXT NOT NULL,
    diagnosis TEXT,
    treatment TEXT,
    temperature DECIMAL(4,1), -- Celsius
    weight DECIMAL(5,2), -- kg
    blood_pressure VARCHAR(10), -- e.g., 120/80
    referral_needed BOOLEAN DEFAULT FALSE,
    referral_notes TEXT,
    attended_by INT NOT NULL, -- Nurse or Doctor (Staff user)
    status ENUM('active', 'completed', 'referred') DEFAULT 'completed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (attended_by) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Medications Dispensed
CREATE TABLE IF NOT EXISTS medical_medications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    visit_id INT NOT NULL,
    medication_name VARCHAR(100) NOT NULL,
    dosage VARCHAR(50),
    frequency VARCHAR(50),
    duration VARCHAR(50),
    instructions TEXT,
    dispensed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (visit_id) REFERENCES medical_clinic_visits(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Health Screenings
CREATE TABLE IF NOT EXISTS medical_screenings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    screening_type VARCHAR(100) NOT NULL, -- e.g., Annual, Sports, Eye
    screening_date DATE NOT NULL,
    results TEXT,
    recommendations TEXT,
    conducted_by VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Sample Data for Admin (for testing visibility)
-- User #1 is usually Admin
INSERT INTO medical_records (user_id, blood_group, allergies, chronic_conditions) 
VALUES (1, 'B+', 'Peanuts, Penicillin', 'None');
