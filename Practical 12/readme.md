# Practical 12: Event Management with File Upload

**Author:** Raj Patel & Manish Kumar

## Overview
A dynamic event management web application that allows administrators to publish, edit, view, and delete campus events along with poster image file uploads (JPG, PNG, WebP) with validation and cleanup.

## Files
- `Event.php` - Public listing of upcoming campus events
- `Event.html` - Landing page with portal navigation
- `events.php` - Admin management table for events
- `add_event.php` - Event creation form with image upload
- `edit_event.php` - Event editing and poster replacement
- `delete_event.php` - Deletion handler that removes database record and poster file
- `admin_check.php` - Session guard ensuring only admins can manage events
- `login.php` & `logout.php` - Admin authentication (Default: `admin` / `admin123`)
- `db.php` - Database connection
- `Event.css` - Custom styling
- `events_db.sql` - Database schema & sample events
- `uploads/` - Folder for storing uploaded event posters

## Setup & Testing Instructions
1. Import `events_db.sql` in phpMyAdmin or MySQL CLI.
2. Place the `Practical 12` directory in your XAMPP `htdocs` folder.
3. Open `http://localhost/Practical%2012/Event.php` in your browser.
4. Click **Admin Login** (credentials `admin` / `admin123` or use the 1-Click Demo Login) to test adding, editing, and deleting events with posters.
