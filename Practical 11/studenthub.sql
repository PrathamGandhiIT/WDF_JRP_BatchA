CREATE DATABASE IF NOT EXISTS studenthub;
USE studenthub;

CREATE TABLE IF NOT EXISTS students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    mobile VARCHAR(15) NOT NULL,
    password VARCHAR(255) NOT NULL,
    course VARCHAR(50) NOT NULL,
    year INT NOT NULL,
    gender VARCHAR(20) NOT NULL
);

-- Sample records for testing
INSERT INTO students (name, username, email, mobile, password, course, year, gender) VALUES
('Raj Patel', 'raj_patel', 'raj.patel@charusat.edu.in', '9876543210', '$2y$10$w8z/0VfWsm.i1uBvD.2c4O6Y54mS1t92v6N/qXfW9W/L2Qk7Z2jty', 'DIT', 2, 'Male'),
('Manish Kumar', 'manish_k', 'manish.kumar@charusat.edu.in', '9823456789', '$2y$10$w8z/0VfWsm.i1uBvD.2c4O6Y54mS1t92v6N/qXfW9W/L2Qk7Z2jty', 'B.Tech', 3, 'Male'),
('Pooja Mehta', 'pooja_m', 'pooja.mehta@charusat.edu.in', '9712345678', '$2y$10$w8z/0VfWsm.i1uBvD.2c4O6Y54mS1t92v6N/qXfW9W/L2Qk7Z2jty', 'BCA', 1, 'Female'),
('Ankit Sharma', 'ankit_s', 'ankit.sharma@charusat.edu.in', '9654321098', '$2y$10$w8z/0VfWsm.i1uBvD.2c4O6Y54mS1t92v6N/qXfW9W/L2Qk7Z2jty', 'MCA', 2, 'Male'),
('Neha Joshi', 'neha_j', 'neha.joshi@charusat.edu.in', '9543210987', '$2y$10$w8z/0VfWsm.i1uBvD.2c4O6Y54mS1t92v6N/qXfW9W/L2Qk7Z2jty', 'DIT', 1, 'Female');
