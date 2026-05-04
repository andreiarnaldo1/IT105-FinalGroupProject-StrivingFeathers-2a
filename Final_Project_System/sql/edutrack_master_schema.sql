-- ========================================================
-- PROJECT: EduTrack Student Management System
-- TEAM: StrivingFeathers (BSIS 2-A)
-- SCRIPT: Master Database Schema (Full Structure)
-- DEVELOPER: Arjiannah Carmelle Borleo
-- ========================================================

CREATE DATABASE IF NOT EXISTS edutrack_db;
USE edutrack_db;

-- 1. TABLES & CONSTRAINTS
-- ========================================================

CREATE TABLE Departments (
    dept_id INT AUTO_INCREMENT PRIMARY KEY,
    dept_name VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE Instructors (
    instructor_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    dept_id INT,
    FOREIGN KEY (dept_id) REFERENCES Departments(dept_id)
);

CREATE TABLE Students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    date_of_birth DATE NOT NULL,
    enrollment_date DATE DEFAULT (CURRENT_DATE)
);

CREATE TABLE Courses (
    course_id INT AUTO_INCREMENT PRIMARY KEY,
    course_code VARCHAR(15) UNIQUE NOT NULL,
    course_title VARCHAR(100) NOT NULL,
    credits INT NOT NULL,
    dept_id INT,
    FOREIGN KEY (dept_id) REFERENCES Departments(dept_id)
);

CREATE TABLE Enrollments (
    enrollment_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    instructor_id INT NOT NULL,
    semester VARCHAR(20) NOT NULL,
    academic_year INT NOT NULL,
    grade DECIMAL(3,2) DEFAULT NULL,
    FOREIGN KEY (student_id) REFERENCES Students(student_id),
    FOREIGN KEY (course_id) REFERENCES Courses(course_id),
    FOREIGN KEY (instructor_id) REFERENCES Instructors(instructor_id)
);

CREATE TABLE audit_logs (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    old_grade DECIMAL(3,2),
    new_grade DECIMAL(3,2),
    action_type VARCHAR(50),
    changed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. OPTIMIZATION (INDEXES)
-- ========================================================
CREATE INDEX idx_student_name ON Students(first_name);
CREATE INDEX idx_enrollment_semester ON Enrollments(semester);
CREATE INDEX idx_course_title ON Courses(course_title);

-- 3. TRIGGERS
-- ========================================================
DELIMITER //
CREATE TRIGGER after_grade_update
AFTER UPDATE ON Enrollments
FOR EACH ROW
BEGIN
    IF OLD.grade <> NEW.grade THEN
        INSERT INTO audit_logs (student_id, old_grade, new_grade, action_type)
        VALUES (OLD.student_id, OLD.grade, NEW.grade, 'GRADE_UPDATE');
    END IF;
END //
DELIMITER ;

-- 4. ROLES & SECURITY
-- ========================================================
CREATE ROLE IF NOT EXISTS 'registrar_admin', 'student_viewer';
GRANT ALL PRIVILEGES ON edutrack_db.* TO 'registrar_admin';
GRANT SELECT ON edutrack_db.Students TO 'student_viewer';
GRANT SELECT ON edutrack_db.Courses TO 'student_viewer';
FLUSH PRIVILEGES;