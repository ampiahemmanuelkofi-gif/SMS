-- Cafeteria/Food Services Module Migration
-- Compatible with MySQL 5.6+

-- 1. Meal Types
CREATE TABLE IF NOT EXISTS cafeteria_meal_types (
    id INT PRIMARY KEY AUTO_INCREMENT,
    type_name VARCHAR(50) NOT NULL UNIQUE, -- Breakfast, Lunch, Dinner, Snack
    start_time TIME,
    end_time TIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Menus
CREATE TABLE IF NOT EXISTS cafeteria_menus (
    id INT PRIMARY KEY AUTO_INCREMENT,
    meal_date DATE NOT NULL,
    meal_type_id INT NOT NULL,
    menu_item VARCHAR(255) NOT NULL,
    description TEXT,
    is_published BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (meal_type_id) REFERENCES cafeteria_meal_types(id) ON DELETE CASCADE,
    UNIQUE KEY idx_date_meal_type (meal_date, meal_type_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Dietary Restrictions (Student Specific)
CREATE TABLE IF NOT EXISTS cafeteria_dietary_restrictions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    restriction_type ENUM('allergy', 'religious', 'medical', 'other') NOT NULL,
    details TEXT NOT NULL, -- e.g., "No Beef", "Peanut Allergy"
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY idx_user_restriction (user_id, details(50))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Meal Attendance Logs
CREATE TABLE IF NOT EXISTS cafeteria_meal_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    menu_id INT NOT NULL,
    attended_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    marked_by INT NOT NULL, -- Staff user
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (menu_id) REFERENCES cafeteria_menus(id) ON DELETE CASCADE,
    FOREIGN KEY (marked_by) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Initial Data
INSERT IGNORE INTO cafeteria_meal_types (type_name, start_time, end_time) VALUES 
('Breakfast', '07:00:00', '08:30:00'),
('Lunch', '12:30:00', '14:00:00'),
('Dinner', '18:30:00', '20:00:00');

-- Example Menu for Today
INSERT IGNORE INTO cafeteria_menus (meal_date, meal_type_id, menu_item, description, is_published) VALUES 
(CURDATE(), 1, 'Continental Breakfast', 'Boiled eggs, toast, tea and fruits', 1),
(CURDATE(), 2, 'Jollof Rice & Chicken', 'Spicy jollof with grilled chicken and salad', 1),
(CURDATE(), 3, 'Banku & Tilapia', 'Fermented corn dough with grilled fish', 1);
