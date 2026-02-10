-- Asset & Inventory Management Module Migration
-- Compatible with MySQL 5.6+

-- 1. Inventory Categories
CREATE TABLE IF NOT EXISTS inventory_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(100) NOT NULL UNIQUE,
    category_type ENUM('asset', 'consumable') NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Fixed Assets
CREATE TABLE IF NOT EXISTS inventory_assets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    asset_name VARCHAR(255) NOT NULL,
    category_id INT NOT NULL,
    serial_number VARCHAR(100) UNIQUE,
    model_number VARCHAR(100),
    brand VARCHAR(100),
    purchase_date DATE,
    purchase_cost DECIMAL(15,2),
    location VARCHAR(100), -- Room No, Office, etc.
    assigned_to INT, -- Link to users(id)
    status ENUM('active', 'under_repair', 'disposed', 'lost') DEFAULT 'active',
    condition_rank ENUM('new', 'good', 'fair', 'poor') DEFAULT 'good',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES inventory_categories(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Consumable Items
CREATE TABLE IF NOT EXISTS inventory_consumables (
    id INT PRIMARY KEY AUTO_INCREMENT,
    item_name VARCHAR(255) NOT NULL,
    category_id INT NOT NULL,
    sku VARCHAR(50) UNIQUE,
    current_stock INT DEFAULT 0,
    min_stock_level INT DEFAULT 5,
    unit_of_measure VARCHAR(20) DEFAULT 'pieces', -- boxes, reams, liters, etc.
    last_restock_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES inventory_categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Stock Movement Logs
CREATE TABLE IF NOT EXISTS inventory_stock_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    consumable_id INT NOT NULL,
    log_type ENUM('add', 'subtract') NOT NULL,
    quantity INT NOT NULL,
    reason VARCHAR(255),
    performed_by INT NOT NULL,
    logged_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (consumable_id) REFERENCES inventory_consumables(id) ON DELETE CASCADE,
    FOREIGN KEY (performed_by) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Maintenance Records
CREATE TABLE IF NOT EXISTS inventory_maintenance (
    id INT PRIMARY KEY AUTO_INCREMENT,
    asset_id INT NOT NULL,
    maintenance_date DATE NOT NULL,
    maintenance_type ENUM('routine', 'repair', 'upgrade') NOT NULL,
    description TEXT,
    cost DECIMAL(15,2) DEFAULT 0.00,
    conducted_by VARCHAR(255),
    next_due_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (asset_id) REFERENCES inventory_assets(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Initial Categories
INSERT IGNORE INTO inventory_categories (category_name, category_type) VALUES 
('Electronics & IT', 'asset'),
('Furniture', 'asset'),
('Lab Equipment', 'asset'),
('Stationery', 'consumable'),
('Cleaning Supplies', 'consumable'),
('Kitchen Supplies', 'consumable');

-- Seed Sample Assets
INSERT IGNORE INTO inventory_assets (asset_name, category_id, serial_number, brand, location, status) VALUES 
('Office Desktop PC', 1, 'SN-PC-001', 'HP', 'Admin Office', 'active'),
('Staff Room Table', 2, 'SN-FUR-001', 'Generic', 'Staff Room', 'active');

-- Seed Sample Consumables
INSERT IGNORE INTO inventory_consumables (item_name, category_id, current_stock, min_stock_level, unit_of_measure) VALUES 
('A4 Paper Reams', 4, 20, 5, 'reams'),
('Whiteboard Markers', 4, 50, 10, 'pieces');
