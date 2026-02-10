-- Finance & Fee Management Migration
-- Compatible with MySQL 5.6+

-- 1. Fee Categories
CREATE TABLE IF NOT EXISTS fee_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    description VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Currencies
CREATE TABLE IF NOT EXISTS currencies (
    id INT PRIMARY KEY AUTO_INCREMENT,
    code CHAR(3) UNIQUE NOT NULL,
    name VARCHAR(50) NOT NULL,
    symbol VARCHAR(10),
    exchange_rate DECIMAL(15,6) DEFAULT 1.0, -- Relative to base currency
    is_base BOOLEAN DEFAULT 0,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Payment Plans
CREATE TABLE IF NOT EXISTS payment_plans (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    installment_count INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Update fee_structure to support categories
ALTER TABLE fee_structure ADD COLUMN category_id INT AFTER term_id;
ALTER TABLE fee_structure ADD FOREIGN KEY (category_id) REFERENCES fee_categories(id) ON DELETE CASCADE;

-- 5. Fee Discounts (Scholarships, Sibling discounts, etc.)
CREATE TABLE IF NOT EXISTS fee_discounts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    type ENUM('percentage', 'fixed') NOT NULL,
    value DECIMAL(10,2) NOT NULL,
    term_id INT NOT NULL,
    is_active BOOLEAN DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (term_id) REFERENCES terms(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Fee Invoices
CREATE TABLE IF NOT EXISTS fee_invoices (
    id INT PRIMARY KEY AUTO_INCREMENT,
    invoice_number VARCHAR(20) UNIQUE NOT NULL,
    student_id INT NOT NULL,
    term_id INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    discount_amount DECIMAL(10,2) DEFAULT 0,
    paid_amount DECIMAL(10,2) DEFAULT 0,
    status ENUM('unpaid', 'partially_paid', 'paid', 'cancelled') DEFAULT 'unpaid',
    due_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (term_id) REFERENCES terms(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 7. Invoice Items (Breakdown of fees)
CREATE TABLE IF NOT EXISTS invoice_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    invoice_id INT NOT NULL,
    category_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    description VARCHAR(255),
    FOREIGN KEY (invoice_id) REFERENCES fee_invoices(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES fee_categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 8. Chart of Accounts (Double-entry accounting)
CREATE TABLE IF NOT EXISTS chart_of_accounts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(20) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    type ENUM('asset', 'liability', 'equity', 'income', 'expense') NOT NULL,
    parent_id INT,
    is_active BOOLEAN DEFAULT 1,
    FOREIGN KEY (parent_id) REFERENCES chart_of_accounts(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 9. Ledger Entries
CREATE TABLE IF NOT EXISTS ledger_entries (
    id INT PRIMARY KEY AUTO_INCREMENT,
    account_id INT NOT NULL,
    transaction_date DATE NOT NULL,
    description TEXT,
    debit DECIMAL(15,2) DEFAULT 0,
    credit DECIMAL(15,2) DEFAULT 0,
    reference_type VARCHAR(50), -- e.g. 'fee_payment', 'invoice'
    reference_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (account_id) REFERENCES chart_of_accounts(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed initial records
INSERT INTO fee_categories (name, description) VALUES 
('Tuition', 'Core academic fees'),
('Boarding', 'Fees for hostel accommodation'),
('Transport', 'School bus services'),
('Activities', 'Extra-curricular activities');

INSERT INTO currencies (code, name, symbol, exchange_rate, is_base) VALUES 
('GHS', 'Ghanaian Cedi', 'GH₵', 1.0, 1),
('USD', 'US Dollar', '$', 12.5, 0),
('EUR', 'Euro', '€', 13.5, 0);

INSERT INTO chart_of_accounts (code, name, type) VALUES 
('1000', 'Cash and Bank', 'asset'),
('1100', 'Accounts Receivable', 'asset'),
('4000', 'Fee Income', 'income'),
('4100', 'Donations', 'income'),
('5000', 'Salaries and Wages', 'expense'),
('5100', 'Utilities', 'expense');
