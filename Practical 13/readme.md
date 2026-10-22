# Practical 13: Asynchronous Event Management with AJAX (Fetch API)

**Author:** Raj Patel & Manish Kumar

## Overview
A dynamic Single Page Application (SPA) implementing full asynchronous CRUD operations on events using JavaScript `fetch()`, PHP RESTful JSON API (`api.php`), and MySQL database without any page reload.

## Files
- `index.php` - Single-page interface with responsive event form and dynamic table
- `api.php` - RESTful backend API responding with JSON
- `script.js` - Client-side fetch logic for GET, POST, UPDATE, DELETE actions
- `style.css` - CSS styles for form grid, buttons, and event table
- `db.php` - MySQL database connection configuration
- `events_db.sql` - Database schema and initial sample records

## Setup & Testing Instructions
1. Import `events_db.sql` in phpMyAdmin or MySQL CLI.
2. Place the `Practical 13` directory in your XAMPP `htdocs` folder.
3. Open `http://localhost/Practical%2013/index.php` in your browser.
4. Test adding an event, editing existing events, and deleting events without page reloads.
