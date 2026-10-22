-- Practical 15 Database Setup Script
-- Author: Raj Patel & Manish Kumar

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
);

-- Seed Administrator and Student Accounts
-- Admin: ADMIN01 / admin123
-- Students: 25DIT001 / raj123, 25DIT002 / manish123
INSERT INTO students (enrollment_number, name, username, email, mobile, password, course, year, gender, role) VALUES
('ADMIN01', 'Manish Sharma', 'admin', 'admin@charusat.edu.in', '9998887770', '$2y$10$tZ2R4Gq6u9kGq8.6gK7HGeD0iZ7iY.w2L4vM2l2n8j7O0k4.bN2S2', 'Admin', 4, 'Male', 'admin'),
('25DIT001', 'Raj Patel', 'raj_patel', 'raj.patel@charusat.edu.in', '9876543210', '$2y$10$w8z/0VfWsm.i1uBvD.2c4O6Y54mS1t92v6N/qXfW9W/L2Qk7Z2jty', 'DIT', 2, 'Male', 'student'),
('25DIT002', 'Manish Kumar', 'manish_k', 'manish.kumar@charusat.edu.in', '9823456789', '$2y$10$w8z/0VfWsm.i1uBvD.2c4O6Y54mS1t92v6N/qXfW9W/L2Qk7Z2jty', 'B.Tech', 3, 'Male', 'student'),
('25DIT003', 'Pooja Mehta', 'pooja_m', 'pooja.mehta@charusat.edu.in', '9712345678', '$2y$10$w8z/0VfWsm.i1uBvD.2c4O6Y54mS1t92v6N/qXfW9W/L2Qk7Z2jty', 'BCA', 1, 'Female', 'student'),
('25DIT004', 'Ankit Verma', 'ankit_v', 'ankit.verma@charusat.edu.in', '9654321098', '$2y$10$w8z/0VfWsm.i1uBvD.2c4O6Y54mS1t92v6N/qXfW9W/L2Qk7Z2jty', 'MCA', 2, 'Male', 'student')
ON DUPLICATE KEY UPDATE name=VALUES(name);
