-- ========================================================
-- PROJECT: EduTrack Student Management System
-- TEAM: StrivingFeathers (BSIS 2-A)
-- SCRIPT: Master Database Schema (Complete Integrated Version)
-- DEVELOPER: Arjiannah Carmelle Borleo (SQL Developer)
-- ========================================================

-- Initialize the Database
CREATE DATABASE IF NOT EXISTS edutrack_db;
USE edutrack_db;

-- ========================================================
-- SECTION 1: TABLES, CONSTRAINTS & NORMALIZATION (3NF)
-- ========================================================

-- 1. Departments Table
CREATE TABLE Departments (
    dept_id INT AUTO_INCREMENT PRIMARY KEY,
    dept_name VARCHAR(100) NOT NULL UNIQUE
);

-- 2. Instructors Table
CREATE TABLE Instructors (
    instructor_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    dept_id INT,
    FOREIGN KEY (dept_id) REFERENCES Departments(dept_id) ON DELETE SET NULL
);

-- 3. Students Table (Stores 10,000+ Records)
CREATE TABLE Students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    date_of_birth DATE NOT NULL,
    enrollment_date DATE DEFAULT (CURRENT_DATE)
);

-- 4. Courses Table
CREATE TABLE Courses (
    course_id INT AUTO_INCREMENT PRIMARY KEY,
    course_code VARCHAR(15) UNIQUE NOT NULL,
    course_title VARCHAR(100) NOT NULL,
    credits INT NOT NULL CHECK (credits > 0),
    dept_id INT,
    FOREIGN KEY (dept_id) REFERENCES Departments(dept_id) ON DELETE CASCADE
);

-- 5. Enrollments Table (Junction Table for Many-to-Many Relationship)
-- This table handles the bulk 90,000+ records.
CREATE TABLE Enrollments (
    enrollment_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    instructor_id INT NOT NULL,
    semester VARCHAR(20) NOT NULL,
    academic_year INT NOT NULL,
    grade DECIMAL(3,2) DEFAULT NULL,
    FOREIGN KEY (student_id) REFERENCES Students(student_id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES Courses(course_id) ON DELETE CASCADE,
    FOREIGN KEY (instructor_id) REFERENCES Instructors(instructor_id) ON DELETE CASCADE
);

-- 6. Audit Logs Table (For Database Triggers)
CREATE TABLE IF NOT EXISTS audit_logs (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    old_grade DECIMAL(3,2),
    new_grade DECIMAL(3,2),
    action_type VARCHAR(50) DEFAULT 'GRADE_UPDATE',
    changed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- ========================================================
-- SECTION 2: PERFORMANCE OPTIMIZATION (B-TREE INDEXING)
-- ========================================================

-- Speeds up student lookups by name
CREATE INDEX idx_student_name ON Students(first_name);

-- Speeds up enrollment filtering for large-scale reporting
CREATE INDEX idx_enrollment_semester ON Enrollments(semester);

-- Speeds up course catalog searching
CREATE INDEX idx_course_title ON Courses(course_title);


-- ========================================================
-- SECTION 3: DATABASE AUTOMATION (TRIGGERS)
-- ========================================================

-- Requirement: Automated Audit for Grade Changes
DELIMITER //
CREATE TRIGGER after_grade_update
AFTER UPDATE ON Enrollments
FOR EACH ROW
BEGIN
    -- Log the change only if the grade value actually changed
    IF OLD.grade <> NEW.grade THEN
        INSERT INTO audit_logs (student_id, old_grade, new_grade)
        VALUES (OLD.student_id, OLD.grade, NEW.grade);
    END IF;
END //
DELIMITER ;


-- ========================================================
-- SECTION 4: SECURITY (ROLE-BASED ACCESS CONTROL)
-- ========================================================

-- 1. Create specific database roles
CREATE ROLE IF NOT EXISTS 'registrar_admin', 'student_viewer';

-- 2. Grant Admin full control over the database
GRANT ALL PRIVILEGES ON edutrack_db.* TO 'registrar_admin';

-- 3. Grant Student restricted read-only access
GRANT SELECT ON edutrack_db.Students TO 'student_viewer';
GRANT SELECT ON edutrack_db.Courses TO 'student_viewer';
GRANT SELECT ON edutrack_db.Instructors TO 'student_viewer';

-- 4. Apply changes
FLUSH PRIVILEGES;


-- ========================================================
-- NOTES FOR DEFENSE:
-- Transactions are implemented via the PHP frontend (add_student.php)
-- using begin_transaction(), commit(), and rollback() logic.
-- ========================================================
