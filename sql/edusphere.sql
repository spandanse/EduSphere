-- edusphere.sql
DROP DATABASE IF EXISTS edusphere_db;
CREATE DATABASE edusphere_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE edusphere_db;

-- USERS
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('student','faculty','admin') NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- SUBJECTS
CREATE TABLE subjects (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  faculty_id INT NOT NULL,
  FOREIGN KEY (faculty_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ATTENDANCE: each row = one student one subject one date
CREATE TABLE attendance (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  subject_id INT NOT NULL,
  date DATE NOT NULL,
  status ENUM('present','absent') NOT NULL,
  FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE
);

-- MARKS: 3 internals stored
CREATE TABLE marks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  subject_id INT NOT NULL,
  internal1 INT NULL,
  internal2 INT NULL,
  internal3 INT NULL,
  FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE
);

-- FEEDBACK / QUERY MODULE
CREATE TABLE queries (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  faculty_id INT NOT NULL,
  subject_id INT NOT NULL,
  message TEXT,
  reply TEXT,
  status ENUM('open','replied') DEFAULT 'open',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (faculty_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE
);

-- Seed users (passwords as plain text initially: will be upgraded on first login)
INSERT INTO users (name, email, password, role) VALUES
('Spandan Sen','student1@example.com','password123','student'),
('Md. Shafi Aaghaz','student2@example.com','password123','student'),
('Mr. R. Prasad','faculty1@example.com','password123','faculty'),
('Admin User','admin@example.com','admin123','admin');

-- Seed subjects (faculty id = 3 above)
INSERT INTO subjects (name, faculty_id) VALUES
('Mathematics',3),
('Physics',3),
('Computer Science',3),
('Data Structures',3);

-- Seed some attendance rows for past few days (mix present/absent)
INSERT INTO attendance (student_id, subject_id, date, status) VALUES
(1,1,'2025-11-01','present'),
(1,1,'2025-11-02','absent'),
(1,1,'2025-11-03','present'),
(1,2,'2025-11-01','present'),
(1,3,'2025-11-01','present'),
(2,1,'2025-11-01','absent'),
(2,1,'2025-11-02','absent'),
(2,2,'2025-11-01','present'),
(2,3,'2025-11-01','present');

-- Seed marks
INSERT INTO marks (student_id, subject_id, internal1, internal2, internal3) VALUES
(1,1,78,82,80),
(1,2,70,75,72),
(1,3,88,85,90),
(2,1,60,65,63);


