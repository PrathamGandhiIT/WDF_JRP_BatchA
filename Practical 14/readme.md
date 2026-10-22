# Practical 14: Role-Based Dashboard & Audit Log System

**Author:** Raj Patel & Manish Kumar

## Overview
A comprehensive role-based access control portal with distinct Administrator and Student dashboards. Features live Open-Meteo weather integration for Changa, active campus notices, real-time security audit trails, and multi-database connectivity.

## Files
- `index.php` - Admin Dashboard displaying total statistics and recent registrations
- `Dashboard.php` - Student Dashboard displaying profile summary and live campus weather
- `Dashboard.html`, `Dashboard.css`, `Dashboard.js` - Dashboard assets and Open-Meteo weather fetch
- `students.php` - Admin view of student directory
- `events.php` - Campus events roster (events_db)
- `audit.php` - Security audit trail logging user actions
- `login.php` & `login.html`, `Login.css`, `Login.js` - Authentication interface with 1-Click demo logins
- `logout.php` - Session destroy and logout redirect
- `Register.html`, `Register.php`, `Register.css` - Student registration workflow
- `menu.php` - Role-aware responsive navigation bar
- `auth.php` - Authentication guard functions (`requireLogin()`, `requireAdmin()`, `redirectByRole()`)
- `db.php` - Dual database connection (`studenthub` & `events_db`)
- `style.css` - Admin dashboard styling
- `database.sql` - Complete database schema with pre-seeded test accounts

## Pre-Seeded Test Credentials
- **Admin:** Enrollment `ADMIN01` | Password: `admin123` (or click *1-Click Admin Login*)
- **Student:** Enrollment `25DIT001` | Password: `raj123` (or click *1-Click Student Login*)

## Setup Instructions
1. Import `database.sql` in phpMyAdmin or MySQL CLI.
2. Place the `Practical 14` directory into `htdocs` in your XAMPP installation.
3. Access in browser: `http://localhost/Practical%2014/login.php`
