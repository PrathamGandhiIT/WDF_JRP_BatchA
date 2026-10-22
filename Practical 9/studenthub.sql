-- Practical 9: Secure User Registration with Database Insert, Duplicate Email Check, and Password Hashing
-- Database: studenthub

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
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample Seed Data
INSERT INTO students (enrollment_number, name, username, email, mobile, password, course, year, gender, role) VALUES
('25DIT018', 'Pratham Gandhi', 'pratham_g', '25dit018@charusat.edu.in', '9876543210', '$2y$10$w8z/0VfWsm.i1uBvD.2c4O6Y54mS1t92v6N/qXfW9W/L2Qk7Z2jty', 'Information Technology', 2, 'Male', 'student')
ON DUPLICATE KEY UPDATE name=VALUES(name);
