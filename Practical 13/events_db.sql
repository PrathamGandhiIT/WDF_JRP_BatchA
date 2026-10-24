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

-- Seed sample events
INSERT INTO events (event_name, event_date, venue, description) VALUES
('Web Design Workshop', '2026-11-12', 'Lab 101, Computer Engineering', 'Hands-on session on HTML5, CSS3, and JavaScript Fetch API. Lead: Raj Patel.'),
('Cloud Computing Seminar', '2026-11-20', 'Seminar Hall 1', 'Introductory talk on cloud deployment and serverless architecture. Coordinator: Manish Kumar.'),
('Inter-College Coding Sprint', '2026-12-05', 'Central Computing Facility', 'Competitive programming contest with cash prizes and certificates.');
