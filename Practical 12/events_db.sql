CREATE DATABASE IF NOT EXISTS events_db;
USE events_db;

CREATE TABLE IF NOT EXISTS events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    event_name VARCHAR(150) NOT NULL,
    event_date DATE NOT NULL,
    venue VARCHAR(150) NOT NULL,
    description TEXT,
    poster VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sample upcoming events coordinated by Raj & Manish
INSERT INTO events (event_name, event_date, venue, description, poster) VALUES
('Tech Fest 2026', '2026-11-15', 'Central Auditorium', 'Annual technological symposium with coding marathons and paper presentations. Coordinated by Raj Patel.', NULL),
('Web Development Hackathon', '2026-11-28', 'IT Department Lab 3', '36-hour nonstop web application development challenge. Faculty Incharge: Manish Kumar.', NULL),
('Cultural Gala & DJ Night', '2026-12-10', 'Open Air Theatre (OAT)', 'University annual cultural celebration with musical and dance performances.', NULL),
('National Robotics Challenge', '2026-12-20', 'Multi-Purpose Sports Hall', 'Inter-college autonomous robotics race and line following tournament.', NULL);
