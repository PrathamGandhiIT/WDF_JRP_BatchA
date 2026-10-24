-- 1. Create studenthub database
CREATE DATABASE IF NOT EXISTS studenthub;
USE studenthub;

CREATE TABLE IF NOT EXISTS students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    enrollment_number VARCHAR(20) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    mobile VARCHAR(15) NOT NULL,
    password VARCHAR(255) NOT NULL,
    course VARCHAR(50) NOT NULL,
    year INT NOT NULL,
    gender VARCHAR(20) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'student',
    remember_selector VARCHAR(255) DEFAULT NULL,
    remember_token_hash VARCHAR(255) DEFAULT NULL,
    remember_expires DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    user_name VARCHAR(150) NOT NULL,
    action VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Seed Admin and Student Accounts
-- Admin (Password: admin123)
-- Students (Password: raj123 / manish123)
INSERT INTO students (enrollment_number, name, username, email, mobile, password, course, year, gender, role) VALUES
('ADMIN01', 'System Administrator', 'admin', 'admin@charusat.edu.in', '9998887770', '$2y$10$tZ2R4Gq6u9kGq8.6gK7HGeD0iZ7iY.w2L4vM2l2n8j7O0k4.bN2S2', 'Admin', 4, 'Male', 'admin'),
('25DIT001', 'Raj Patel', 'raj_patel', 'raj.patel@charusat.edu.in', '9876543210', '$2y$10$w8z/0VfWsm.i1uBvD.2c4O6Y54mS1t92v6N/qXfW9W/L2Qk7Z2jty', 'DIT', 2, 'Male', 'student'),
('25DIT002', 'Manish Kumar', 'manish_k', 'manish.kumar@charusat.edu.in', '9823456789', '$2y$10$w8z/0VfWsm.i1uBvD.2c4O6Y54mS1t92v6N/qXfW9W/L2Qk7Z2jty', 'DIT', 2, 'Male', 'student')
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- 2. Create events_db database
CREATE DATABASE IF NOT EXISTS events_db;
USE events_db;

CREATE TABLE IF NOT EXISTS events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    event_name VARCHAR(150) NOT NULL,
    event_date DATE NOT NULL,
    venue VARCHAR(150) NOT NULL,
    description TEXT,
    poster VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO events (event_name, event_date, venue, description) VALUES
('Tech Fest 2026', '2026-11-15', 'Central Auditorium', 'Annual technological symposium. Coordinated by Raj Patel.'),
('Web Development Hackathon', '2026-11-28', 'IT Dept Lab 3', '36-hour hackathon. Faculty incharge: Manish Kumar.'),
('Cultural Night', '2026-12-10', 'Open Air Theatre', 'Annual cultural festival.')
ON DUPLICATE KEY UPDATE event_name=VALUES(event_name);
