# Practical 15: Role-Based Student & Admin Portal with Student Management

**Author:** Raj Patel & Manish Kumar

## Overview
A full-featured role-based web application providing distinct access levels for students and administrators. Features an interactive home portal with dark mode toggle and carousel, student academic profiles, and an administrator panel with search, CSRF protection, and student account deletion capabilities.

## Files
- `Home.html`, `Home.css`, `Home.js` - University portal landing page with carousel and theme switcher
- `admin_dashboard.php` - Administrator dashboard overview
- `dashboard.php` - Student dashboard profile view
- `student_management.php` - Student directory management with search and CSRF-protected deletion
- `login.php`, `Login.html`, `Login.css`, `Login.js` - User authentication and role dispatching
- `Register.html`, `Register.php`, `Register.css` - Student registration system
- `logout.php` - Session destroy and exit handler
- `auth.php` - Authorization guards (`requireRole("admin")`, `requireRole("student")`)
- `db.php` - MySQL connection file
- `studenthub.sql` - Complete database schema with pre-seeded test accounts

## Pre-Seeded Test Credentials
- **Admin:** `ADMIN01` or `admin` | Password: `admin123` (or click *1-Click Admin Login*)
- **Student:** `25DIT001` or `raj_patel` | Password: `raj123` (or click *1-Click Student Login*)

## Setup Instructions
1. Import `studenthub.sql` into MySQL/phpMyAdmin.
2. Move `Practical 15` folder to `htdocs` in your XAMPP directory.
3. Access in browser: `http://localhost/Practical%2015/Home.html`
