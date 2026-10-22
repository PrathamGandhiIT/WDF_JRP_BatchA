# Practical 11: Student Management System (PHP + MySQL CRUD)

**Author:** Raj Patel & Manish Kumar

## Overview
A web-based Student Records Management application implementing full CRUD (Create, Read, Update, Delete) operations using PHP and MySQL with prepared statements and input validation.

## Files
- `db.php` - Database connection configuration
- `students.php` - View student listing with search and filter
- `add_student.php` - Form to insert a new student record
- `edit_student.php` - Form to update existing student record
- `delete_student.php` - Script to delete student record
- `style.css` - Custom styling
- `studenthub.sql` - Database schema and sample data

## Setup Instructions
1. Import `studenthub.sql` in phpMyAdmin or run in MySQL CLI:
   ```sql
   source studenthub.sql;
   ```
2. Put the `Practical 11` folder inside `htdocs` in your XAMPP directory.
3. Access in browser: `http://localhost/Practical%2011/students.php`
