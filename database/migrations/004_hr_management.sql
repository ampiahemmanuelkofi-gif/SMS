-- Human Resources (HR) Management Migration
-- Compatible with MySQL 5.6+

-- 1. Departments
CREATE TABLE IF NOT EXISTS departments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Employees (Extended user profile)
CREATE TABLE IF NOT EXISTS employees (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    employee_id VARCHAR(20) UNIQUE NOT NULL,
    department_id INT,
    designation VARCHAR(100),
    joining_date DATE NOT NULL,
    qualification TEXT,
    experience_details TEXT,
    base_salary DECIMAL(15,2) DEFAULT 0,
    bank_name VARCHAR(100),
    account_number VARCHAR(50),
    tin_number VARCHAR(20),
    ssnit_number VARCHAR(20),
    status ENUM('active', 'on_leave', 'terminated', 'resigned') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL,
    INDEX idx_employee_id (employee_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Recruitment (Applicants)
CREATE TABLE IF NOT EXISTS applicants (
    id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    position_applied VARCHAR(100) NOT NULL,
    resume_path VARCHAR(255),
    status ENUM('new', 'shortlisted', 'interviewed', 'offered', 'hired', 'rejected') DEFAULT 'new',
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Staff Contracts
CREATE TABLE IF NOT EXISTS staff_contracts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    employee_id INT NOT NULL,
    contract_type ENUM('permanent', 'probation', 'contract', 'part_time') NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE,
    file_path VARCHAR(255),
    is_active BOOLEAN DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Leave Management
CREATE TABLE IF NOT EXISTS leave_types (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    days_allowed INT NOT NULL,
    is_paid BOOLEAN DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS leave_requests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    employee_id INT NOT NULL,
    leave_type_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    reason TEXT,
    status ENUM('pending', 'approved', 'rejected', 'cancelled') DEFAULT 'pending',
    approved_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
    FOREIGN KEY (leave_type_id) REFERENCES leave_types(id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Payroll
CREATE TABLE IF NOT EXISTS payroll_configs (
    setting_key VARCHAR(50) PRIMARY KEY,
    setting_value DECIMAL(10,4) NOT NULL,
    description VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS payrolls (
    id INT PRIMARY KEY AUTO_INCREMENT,
    employee_id INT NOT NULL,
    month INT NOT NULL,
    year INT NOT NULL,
    basic_salary DECIMAL(15,2) NOT NULL,
    allowances DECIMAL(15,2) DEFAULT 0,
    deductions DECIMAL(15,2) DEFAULT 0,
    ssnit_employee DECIMAL(15,2) DEFAULT 0, -- 5.5%
    ssnit_employer DECIMAL(15,2) DEFAULT 0, -- 13%
    income_tax DECIMAL(15,2) DEFAULT 0, -- PAYE
    net_salary DECIMAL(15,2) NOT NULL,
    status ENUM('draft', 'generated', 'paid') DEFAULT 'draft',
    paid_at DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_payroll (employee_id, month, year),
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed initial data
INSERT INTO departments (name) VALUES 
('Administration'), 
('Teaching'), 
('Accounts'), 
('Security'), 
('Maintenance');

INSERT INTO leave_types (name, days_allowed) VALUES 
('Annual Leave', 20), 
('Sick Leave', 10), 
('Maternity Leave', 90), 
('Study Leave', 15);

INSERT INTO payroll_configs (setting_key, setting_value, description) VALUES 
('ssnit_employee_rate', 0.055, 'Employee SSNIT contribution rate (5.5%)'),
('ssnit_employer_rate', 0.13, 'Employer SSNIT contribution rate (13%)'),
('tax_free_threshold', 402.00, 'Monthly tax-free threshold in GH₵');
