-- Practical 8: MySQL Schema Design, ER Model, and Database Connectivity
-- Database: studenthub

CREATE DATABASE IF NOT EXISTS studenthub;
USE studenthub;

-- 1. Students Table
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
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    role ENUM('student', 'faculty', 'admin') NOT NULL DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Events Table
CREATE TABLE IF NOT EXISTS events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    event_name VARCHAR(150) NOT NULL,
    event_date DATE NOT NULL,
    venue VARCHAR(150) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Registrations Table (Foreign Key Relationship)
CREATE TABLE IF NOT EXISTS registrations (
    registration_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    event_id INT NOT NULL,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('registered', 'attended', 'cancelled') DEFAULT 'registered',
    CONSTRAINT fk_registration_student FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_registration_event FOREIGN KEY (event_id) REFERENCES events(event_id) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE KEY uq_student_event (student_id, event_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample Seed Data
INSERT INTO students (enrollment_number, name, username, email, mobile, password, course, year, gender, role) VALUES
('25DIT018', 'Pratham Gandhi', 'pratham_g', '25dit018@charusat.edu.in', '9876543210', '$2y$10$w8z/0VfWsm.i1uBvD.2c4O6Y54mS1t92v6N/qXfW9W/L2Qk7Z2jty', 'Information Technology', 2, 'Male', 'student'),
('ADMIN01', 'System Administrator', 'admin', 'admin@charusat.edu.in', '9998887770', '$2y$10$tZ2R4Gq6u9kGq8.6gK7HGeD0iZ7iY.w2L4vM2l2n8j7O0k4.bN2S2', 'Administration', 4, 'Male', 'admin')
ON DUPLICATE KEY UPDATE name=VALUES(name);

INSERT INTO events (event_name, event_date, venue, description) VALUES
('Annual Tech Symposium', '2026-11-20', 'Central Auditorium', 'Annual technological conference with guest speakers.'),
('Web Dev Hackathon', '2026-12-05', 'IT Lab 2', '24-hour web development hackathon.')
ON DUPLICATE KEY UPDATE event_name=VALUES(event_name);
