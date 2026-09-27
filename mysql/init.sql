CREATE TABLE IF NOT EXISTS visitors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    mobile VARCHAR(15) NOT NULL,
    email VARCHAR(100),
    contact_person VARCHAR(100),
    visit_reason VARCHAR(50) NOT NULL,
    check_in TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    check_out TIMESTAMP NULL DEFAULT NULL,
    INDEX idx_visit_reason (visit_reason),
    INDEX idx_check_in (check_in)
);

CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL
);

-- Default login: username "admin" / password "admin123"
-- CHANGE THIS PASSWORD after first login (there's no "change password" screen yet — update
-- the hash directly in the DB using PHP's password_hash() until you add one).
INSERT INTO admins (username, password_hash) VALUES
('admin', '$2b$12$G6YWBjQKE/kB/0UGG/IOZuv50Vezx565TG9QbFy7KZFUwHSkXBLAO')
ON DUPLICATE KEY UPDATE username = username;
