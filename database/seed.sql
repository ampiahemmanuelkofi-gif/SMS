-- Sample Data for Testing
-- Run this after schema.sql

USE school_management;

-- Insert default admin user (username: admin, password: admin123)
INSERT INTO users (role, username, password, email, phone, full_name, is_active) VALUES
('super_admin', 'admin', '$2y$10$hE2.gr/S6mb3vveIspsZ7Ov.OpTvaR.0/Z4afFj8RjSCBeaibNLvm', 'admin@ghanamodelschool.edu.gh', '+233200000000', 'System Administrator', 1),
('admin', 'headmaster', '$2y$10$hE2.gr/S6mb3vveIspsZ7Ov.OpTvaR.0/Z4afFj8RjSCBeaibNLvm', 'headmaster@ghanamodelschool.edu.gh', '+233200000001', 'John Mensah', 1),
('teacher', 'teacher1', '$2y$10$hE2.gr/S6mb3vveIspsZ7Ov.OpTvaR.0/Z4afFj8RjSCBeaibNLvm', 'teacher1@ghanamodelschool.edu.gh', '+233200000002', 'Mary Asante', 1),
('accountant', 'accountant', '$2y$10$hE2.gr/S6mb3vveIspsZ7Ov.OpTvaR.0/Z4afFj8RjSCBeaibNLvm', 'accountant@ghanamodelschool.edu.gh', '+233200000003', 'Peter Osei', 1),
('parent', 'parent1', '$2y$10$hE2.gr/S6mb3vveIspsZ7Ov.OpTvaR.0/Z4afFj8RjSCBeaibNLvm', 'parent1@example.com', '+233200000004', 'Grace Boateng', 1);

-- Insert academic year 2024-2025
INSERT INTO academic_years (name, start_date, end_date, is_current) VALUES
('2024-2025', '2024-09-01', '2025-07-31', 1);

-- Insert terms
INSERT INTO terms (academic_year_id, name, start_date, end_date, is_current) VALUES
(1, '1st Term', '2024-09-01', '2024-12-15', 1),
(1, '2nd Term', '2025-01-06', '2025-04-15', 0),
(1, '3rd Term', '2025-04-28', '2025-07-31', 0);

-- Insert classes
INSERT INTO classes (name, level, capacity) VALUES
-- Nursery
('Nursery 1', 'nursery', 30),
('Nursery 2', 'nursery', 30),
-- Primary
('Primary 1', 'primary', 40),
('Primary 2', 'primary', 40),
('Primary 3', 'primary', 40),
('Primary 4', 'primary', 40),
('Primary 5', 'primary', 40),
('Primary 6', 'primary', 40),
-- JHS
('JHS 1', 'jhs', 45),
('JHS 2', 'jhs', 45),
('JHS 3', 'jhs', 45);

-- Insert sections
INSERT INTO sections (class_id, name, class_teacher_id) VALUES
-- Nursery sections
(1, 'A', 3),
(2, 'A', 3),
-- Primary sections
(3, 'A', 3),
(3, 'B', 3),
(4, 'A', 3),
(5, 'A', 3),
(6, 'A', 3),
(7, 'A', 3),
(8, 'A', 3),
-- JHS sections
(9, 'A', 3),
(9, 'B', 3),
(10, 'A', 3),
(11, 'A', 3);

-- Insert subjects
INSERT INTO subjects (name, code, is_core, level) VALUES
-- Core subjects (all levels)
('English Language', 'ENG', 1, 'all'),
('Mathematics', 'MATH', 1, 'all'),
('Science', 'SCI', 1, 'all'),
('Social Studies', 'SOC', 1, 'all'),
-- JHS specific
('Integrated Science', 'ISCI', 1, 'jhs'),
('Religious & Moral Education', 'RME', 1, 'jhs'),
('Ghanaian Language', 'GHL', 1, 'jhs'),
('French', 'FRE', 0, 'jhs'),
('Information Technology', 'ICT', 0, 'jhs'),
('Creative Arts', 'CA', 0, 'jhs'),
('Physical Education', 'PE', 0, 'all');

-- Insert sample students
INSERT INTO students (student_id, first_name, last_name, date_of_birth, gender, guardian_name, guardian_phone, guardian_email, address, section_id, admission_date, status) VALUES
('SCH-2024-0001', 'Kwame', 'Mensah', '2013-05-15', 'male', 'Grace Boateng', '+233200000004', 'parent1@example.com', 'Accra, Ghana', 10, '2024-09-01', 'active'),
('SCH-2024-0002', 'Ama', 'Asante', '2013-08-22', 'female', 'Samuel Asante', '+233200000005', 'parent2@example.com', 'Kumasi, Ghana', 10, '2024-09-01', 'active'),
('SCH-2024-0003', 'Kofi', 'Osei', '2014-03-10', 'male', 'Abena Osei', '+233200000006', 'parent3@example.com', 'Tema, Ghana', 6, '2024-09-01', 'active');

-- Insert fee structure
INSERT INTO fee_structure (class_id, term_id, amount, description, due_date) VALUES
-- Nursery fees
(1, 1, 500.00, 'Tuition Fee - 1st Term', '2024-09-15'),
(2, 1, 500.00, 'Tuition Fee - 1st Term', '2024-09-15'),
-- Primary fees
(3, 1, 600.00, 'Tuition Fee - 1st Term', '2024-09-15'),
(4, 1, 600.00, 'Tuition Fee - 1st Term', '2024-09-15'),
(5, 1, 600.00, 'Tuition Fee - 1st Term', '2024-09-15'),
(6, 1, 600.00, 'Tuition Fee - 1st Term', '2024-09-15'),
(7, 1, 650.00, 'Tuition Fee - 1st Term', '2024-09-15'),
(8, 1, 650.00, 'Tuition Fee - 1st Term', '2024-09-15'),
-- JHS fees
(9, 1, 750.00, 'Tuition Fee - 1st Term', '2024-09-15'),
(10, 1, 750.00, 'Tuition Fee - 1st Term', '2024-09-15'),
(11, 1, 800.00, 'Tuition Fee - 1st Term', '2024-09-15');

-- Insert sample settings
INSERT INTO settings (setting_key, setting_value, description) VALUES
('school_name', 'Ghana Model School', 'School name'),
('school_motto', 'Excellence in Education', 'School motto'),
('school_address', 'Accra, Ghana', 'School address'),
('school_phone', '+233 000 000 000', 'School phone number'),
('school_email', 'info@ghanamodelschool.edu.gh', 'School email'),
('current_academic_year', '1', 'Current academic year ID'),
('current_term', '1', 'Current term ID'),
('student_id_prefix', 'SCH', 'Prefix for student ID'),
('student_id_year', '2024', 'Year for student ID'),
('student_id_counter', '2', 'Counter for student ID');

-- Student Categories
INSERT INTO student_categories (name, description) VALUES
('Day Student', 'Non-boarding student'),
('Boarder', 'Student residing in school hostel'),
('Scholarship', 'Student receiving financial aid'),
('Special Needs', 'Student requiring additional support');

-- Custom Fields
INSERT INTO student_custom_fields (field_name, field_type) VALUES
('House Color', 'select'),
('Club/Society', 'text'),
('Previous School', 'text');

-- Insert sample notice
INSERT INTO notices (title, content, target_audience, posted_by, expires_at) VALUES
('Welcome to 2024-2025 Academic Year', 'We welcome all students, parents, and staff to the new academic year. Classes commence on September 1st, 2024.', 'all', 1, '2024-12-31');
