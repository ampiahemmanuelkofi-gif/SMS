-- Transport Management Module Migration
-- Compatible with MySQL 5.6+

-- 1. Transport Vehicles (Fleet)
CREATE TABLE IF NOT EXISTS transport_vehicles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    plate_number VARCHAR(20) NOT NULL UNIQUE,
    vehicle_model VARCHAR(100) NOT NULL,
    capacity INT NOT NULL,
    driver_name VARCHAR(150),
    driver_phone VARCHAR(20),
    conductor_name VARCHAR(150),
    status ENUM('active', 'maintenance', 'out_of_service') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Transport Routes
CREATE TABLE IF NOT EXISTS transport_routes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    route_name VARCHAR(100) NOT NULL UNIQUE,
    start_point VARCHAR(255) NOT NULL,
    end_point VARCHAR(255) NOT NULL,
    base_fare DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    vehicle_id INT, -- Main vehicle assigned to this route
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (vehicle_id) REFERENCES transport_vehicles(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Route Stops (Optional but good for ETA/GPS)
CREATE TABLE IF NOT EXISTS transport_stops (
    id INT PRIMARY KEY AUTO_INCREMENT,
    route_id INT NOT NULL,
    stop_name VARCHAR(255) NOT NULL,
    pickup_time TIME,
    dropoff_time TIME,
    sort_order INT DEFAULT 0,
    FOREIGN KEY (route_id) REFERENCES transport_routes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Transport Assignments (Subscribers)
CREATE TABLE IF NOT EXISTS transport_assignments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL, -- Student or Staff
    route_id INT NOT NULL,
    stop_id INT,
    status ENUM('active', 'suspended', 'cancelled') DEFAULT 'active',
    start_date DATE NOT NULL,
    end_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (route_id) REFERENCES transport_routes(id) ON DELETE CASCADE,
    FOREIGN KEY (stop_id) REFERENCES transport_stops(id) ON DELETE SET NULL,
    UNIQUE KEY idx_user_route (user_id, route_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Maintenance Logs
CREATE TABLE IF NOT EXISTS transport_maintenance (
    id INT PRIMARY KEY AUTO_INCREMENT,
    vehicle_id INT NOT NULL,
    service_date DATE NOT NULL,
    service_type VARCHAR(100) NOT NULL, -- Oil Change, Tire Replacement, etc.
    cost DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    odometer_reading INT,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (vehicle_id) REFERENCES transport_vehicles(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Initial Vehicle and Route
INSERT INTO transport_vehicles (plate_number, vehicle_model, capacity, driver_name, driver_phone) VALUES 
('GV-1024-22', 'Toyota Coaster (30 Seater)', 30, 'Kofi Mensah', '0244123456'),
('GV-1025-22', 'Nissan Urvan (15 Seater)', 15, 'Ama Serwaa', '0200987654');

INSERT INTO transport_routes (route_name, start_point, end_point, base_fare, vehicle_id) VALUES 
('Madina - School', 'Madina Zongo Junction', 'School Campus', 5.00, 1),
('Adenta - School', 'Adenta Barrier', 'School Campus', 7.00, 2);
