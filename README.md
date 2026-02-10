# School Management System - MVP

A lightweight, XAMPP-compatible school management system designed for Ghana schools (Nursery through JHS 3) with BECE grading support.

## Features

✅ **Student Management** - Admission, profiles, photos, parent information  
✅ **Academic Structure** - Classes, sections, subjects, teacher assignments  
✅ **Attendance Tracking** - Daily attendance marking with reports  
✅ **Assessment & BECE Grading** - Marks entry with A1-F9 grading system  
✅ **Fee Management** - Fee structure, payments, receipts, balance tracking  
✅ **Communication** - Notice board and homework assignments  
✅ **Role-Based Access** - 5 user roles (Super Admin, Admin, Teacher, Accountant, Parent)  
✅ **Reports** - PDF generation for report cards, receipts, and analytics  

## System Requirements

- **Web Server:** Apache 2.4+ (XAMPP recommended)
- **PHP:** 5.6 or higher
- **Database:** MySQL 5.6 or higher
- **Browser:** Chrome, Firefox, Edge (latest versions)
- **Disk Space:** Minimum 100MB

## Installation

### Step 1: Install XAMPP

1. Download XAMPP from [https://www.apachefriends.org](https://www.apachefriends.org)
2. Install XAMPP to `C:\xampp` (Windows) or `/opt/lampp` (Linux)
3. Start Apache and MySQL services from XAMPP Control Panel

### Step 2: Extract Files

1. Extract the project files to `C:\xampp\htdocs\sch`
2. Ensure the directory structure looks like this:
   ```
   C:\xampp\htdocs\sch\
   ├── config/
   ├── core/
   ├── database/
   ├── modules/
   ├── templates/
   ├── assets/
   ├── uploads/
   └── index.php
   ```

### Step 3: Create Database

1. Open phpMyAdmin: [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
2. Or use MySQL command line:
   ```bash
   C:\xampp\mysql\bin\mysql.exe -u root
   ```
3. Import the database schema:
   ```bash
   C:\xampp\mysql\bin\mysql.exe -u root -e "SOURCE C:/xampp/htdocs/sch/database/schema.sql"
   ```
4. Import sample data (optional):
   ```bash
   C:\xampp\mysql\bin\mysql.exe -u root -e "SOURCE C:/xampp/htdocs/sch/database/seed.sql"
   ```

### Step 4: Configure Database Connection

1. Open `config/database.php`
2. Update database credentials if needed:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'school_management');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   ```

### Step 5: Set Permissions

Ensure the `uploads/` directory is writable:

**Windows:**
- Right-click `uploads` folder → Properties → Security → Edit → Allow "Full Control"

**Linux:**
```bash
chmod -R 755 uploads/
```

### Step 6: Access the System

1. Open your browser
2. Navigate to: [http://localhost/sch](http://localhost/sch)
3. Login with default credentials:
   - **Username:** `admin`
   - **Password:** `admin123`

## Default User Accounts

| Role | Username | Password | Description |
|------|----------|----------|-------------|
| Super Admin | admin | admin123 | Full system access |
| Admin | headmaster | admin123 | Student & staff management |
| Teacher | teacher1 | admin123 | Attendance & marks entry |
| Accountant | accountant | admin123 | Fee management only |
| Parent | parent1 | admin123 | View child's progress |

**⚠️ IMPORTANT:** Change all default passwords immediately after first login!

## Configuration

### School Information

Edit `config/constants.php` to customize:

```php
define('SCHOOL_NAME', 'Your School Name');
define('SCHOOL_MOTTO', 'Your School Motto');
define('SCHOOL_ADDRESS', 'Your Address');
define('SCHOOL_PHONE', '+233 XXX XXX XXX');
define('SCHOOL_EMAIL', 'info@yourschool.edu.gh');
```

### File Upload Limits

Edit `config/constants.php`:

```php
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
```

Also update `php.ini`:

```ini
upload_max_filesize = 5M
post_max_size = 8M
```

## Usage Guide

### 1. Student Admission

1. Login as Admin
2. Navigate to **Students** → **Add New Student**
3. Fill in student details and upload photo
4. Assign to class and section
5. Click **Save**

### 2. Mark Attendance

1. Login as Teacher
2. Navigate to **Attendance** → **Mark Attendance**
3. Select class and date
4. Mark each student as Present/Absent/Late/Excused
5. Click **Save**

### 3. Enter Marks

1. Login as Teacher
2. Navigate to **Assessments** → **Enter Marks**
3. Select class, subject, and term
4. Enter marks for each student
5. System automatically calculates BECE grades (A1-F9)

### 4. Record Fee Payment

1. Login as Accountant
2. Navigate to **Fees** → **Record Payment**
3. Select student
4. Enter amount and payment method
5. Generate receipt

### 5. Generate Reports

1. Login as Admin
2. Navigate to **Reports**
3. Select report type (Report Card, Attendance, Fees)
4. Choose filters (class, term, date range)
5. Click **Generate PDF**

## BECE Grading System

The system uses Ghana WAEC standard grading:

| Marks | Grade | Remark | Points |
|-------|-------|--------|--------|
| 75-100 | A1 | Excellent | 1 |
| 70-74 | B2 | Very Good | 2 |
| 65-69 | B3 | Good | 3 |
| 60-64 | C4 | Credit | 4 |
| 55-59 | C5 | Credit | 5 |
| 50-54 | C6 | Credit | 6 |
| 45-49 | D7 | Pass | 7 |
| 40-44 | E8 | Pass | 8 |
| 0-39 | F9 | Fail | 9 |

**Aggregate:** Sum of best 6 subjects (lower is better)

## Troubleshooting

### Error: "Database connection failed"

**Solution:**
1. Ensure MySQL is running in XAMPP
2. Verify database credentials in `config/database.php`
3. Check if database `school_management` exists

### Error: "Page not found (404)"

**Solution:**
1. Ensure `.htaccess` file exists in root directory
2. Enable `mod_rewrite` in Apache:
   - Edit `C:\xampp\apache\conf\httpd.conf`
   - Uncomment: `LoadModule rewrite_module modules/mod_rewrite.so`
   - Restart Apache

### Error: "File upload failed"

**Solution:**
1. Check `uploads/` directory permissions
2. Verify `php.ini` settings:
   ```ini
   upload_max_filesize = 5M
   post_max_size = 8M
   ```
3. Restart Apache after changes

### Blank page or white screen

**Solution:**
1. Enable error display in `index.php`:
   ```php
   error_reporting(E_ALL);
   ini_set('display_errors', 1);
   ```
2. Check Apache error logs: `C:\xampp\apache\logs\error.log`

## Security Best Practices

1. **Change Default Passwords** - Update all default user passwords
2. **Disable Error Display** - Set `display_errors = 0` in production
3. **Regular Backups** - Export database weekly
4. **Update PHP** - Keep PHP version updated
5. **Restrict Access** - Use firewall rules for production servers

## Backup & Restore

### Backup Database

```bash
C:\xampp\mysql\bin\mysqldump.exe -u root school_management > backup.sql
```

### Restore Database

```bash
C:\xampp\mysql\bin\mysql.exe -u root school_management < backup.sql
```

### Backup Files

Copy entire `uploads/` directory to external storage.

## Support

For issues or questions:
- **Email:** support@yourschool.edu.gh
- **Documentation:** See `docs/USER_MANUAL.md`

## License

Copyright © 2024 Ghana Model School. All rights reserved.

---

**Version:** 1.0.0 (MVP)  
**Last Updated:** February 2026
