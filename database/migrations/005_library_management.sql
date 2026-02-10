-- Library Management Module Migration
-- Compatible with MySQL 5.6+

-- 1. Book Categories
CREATE TABLE IF NOT EXISTS library_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Library Books (Catalog)
CREATE TABLE IF NOT EXISTS library_books (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(150),
    isbn VARCHAR(20),
    category_id INT,
    publisher VARCHAR(150),
    edition VARCHAR(50),
    publication_year INT,
    language VARCHAR(50) DEFAULT 'English',
    type ENUM('book', 'ebook', 'journal', 'media') DEFAULT 'book',
    digital_path VARCHAR(255), -- For ebooks and digital resources
    summary TEXT,
    cover_image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES library_categories(id) ON DELETE SET NULL,
    INDEX idx_title (title),
    INDEX idx_isbn (isbn)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Library Inventory (Physical Copies)
CREATE TABLE IF NOT EXISTS library_inventory (
    id INT PRIMARY KEY AUTO_INCREMENT,
    book_id INT NOT NULL,
    barcode VARCHAR(50) UNIQUE NOT NULL,
    location_shelf VARCHAR(50),
    status ENUM('available', 'issued', 'on_repair', 'lost', 'weeded') DEFAULT 'available',
    condition_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (book_id) REFERENCES library_books(id) ON DELETE CASCADE,
    INDEX idx_barcode (barcode)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Library Circulation
CREATE TABLE IF NOT EXISTS library_circulation (
    id INT PRIMARY KEY AUTO_INCREMENT,
    inventory_id INT NOT NULL,
    user_id INT NOT NULL, -- Student or Staff
    issued_by INT NOT NULL, -- Librarian (Staff User)
    issue_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    due_date DATE NOT NULL,
    return_date TIMESTAMP NULL,
    returned_to INT, -- Staff User
    status ENUM('issued', 'returned', 'overdue') DEFAULT 'issued',
    notes TEXT,
    FOREIGN KEY (inventory_id) REFERENCES library_inventory(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (issued_by) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (returned_to) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Library Fines
CREATE TABLE IF NOT EXISTS library_fines (
    id INT PRIMARY KEY AUTO_INCREMENT,
    circulation_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    paid_amount DECIMAL(10,2) DEFAULT 0,
    status ENUM('unpaid', 'partially_paid', 'paid', 'waived') DEFAULT 'unpaid',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    payment_date DATETIME,
    waived_by INT,
    FOREIGN KEY (circulation_id) REFERENCES library_circulation(id) ON DELETE CASCADE,
    FOREIGN KEY (waived_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Initial Categories
INSERT INTO library_categories (name, description) VALUES 
('Science', 'Books related to Physics, Chemistry, Biology, etc.'),
('Mathematics', 'Calculus, Geometry, Algebra, etc.'),
('Literature', 'Fiction, Poetry, and Classics'),
('History', 'World history and local heritage'),
('Geography', 'Maps, environmental studies, and travel'),
('Reference', 'Dictionaries, Encyclopedias, and Handbooks');
