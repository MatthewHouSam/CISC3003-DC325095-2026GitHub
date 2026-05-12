CREATE DATABASE IF NOT EXISTS cisc3003_paper02a CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cisc3003_paper02a;

CREATE TABLE IF NOT EXISTS student_forms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL,
    age TINYINT UNSIGNED NOT NULL,
    message TEXT NOT NULL,
    programme VARCHAR(60) NOT NULL,
    study_mode VARCHAR(20) NOT NULL,
    skills VARCHAR(150) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO student_forms (full_name, email, age, message, programme, study_mode, skills)
VALUES ('Sample User', 'sample@example.com', 20, 'Sample record from SQL file.', 'Computer Science', 'Full-time', 'PHP, MySQL');
