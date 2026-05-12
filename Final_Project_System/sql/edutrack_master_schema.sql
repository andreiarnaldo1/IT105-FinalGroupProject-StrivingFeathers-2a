-- ========================================================
-- PROJECT: EduTrack Student Management System
-- TEAM: StrivingFeathers (BSIS 2-A)
-- SCRIPT: Master Database Schema (Final Defense Version)
-- DEVELOPER: Arjiannah Carmelle Borleo (SQL Developer)
-- ========================================================

CREATE DATABASE IF NOT EXISTS edutrack_db;
USE edutrack_db;

-- ========================================================
-- SECTION 1: TABLES, CONSTRAINTS & NORMALIZATION (3NF)
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
    FOREIGN KEY (dept_id) REFERENCES Departments(dept_id) ON DELETE SET NULL
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
    credits INT NOT NULL CHECK (credits > 0),
    dept_id INT,
    FOREIGN KEY (dept_id) REFERENCES Departments(dept_id) ON DELETE CASCADE
);

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

CREATE TABLE IF NOT EXISTS audit_logs (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    old_grade DECIMAL(3,2),
    new_grade DECIMAL(3,2),
    changed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- ========================================================
-- SECTION 2: PERFORMANCE OPTIMIZATION (B-TREE INDEXING)
-- ========================================================
CREATE INDEX idx_student_name ON Students(first_name);
CREATE INDEX idx_enrollment_semester ON Enrollments(semester);
CREATE INDEX idx_course_title ON Courses(course_title);


-- ========================================================
-- SECTION 3: DATABASE AUTOMATION (TRIGGERS)
-- ========================================================
DELIMITER //
CREATE TRIGGER after_grade_update
AFTER UPDATE ON Enrollments
FOR EACH ROW
BEGIN
    IF OLD.grade <> NEW.grade THEN
        INSERT INTO audit_logs (student_id, old_grade, new_grade)
        VALUES (OLD.student_id, OLD.grade, NEW.grade);
    END IF;
END //
DELIMITER ;


-- ========================================================
-- SECTION 4: SECURITY (ROLE-BASED ACCESS CONTROL)
-- ========================================================
CREATE ROLE IF NOT EXISTS 'registrar_admin', 'student_viewer';
GRANT ALL PRIVILEGES ON edutrack_db.* TO 'registrar_admin';
GRANT SELECT ON edutrack_db.Students TO 'student_viewer';
GRANT SELECT ON edutrack_db.Courses TO 'student_viewer';
FLUSH PRIVILEGES;


-- ========================================================
-- SECTION 5: BUSINESS INTELLIGENCE (VIEW)
-- ========================================================
CREATE OR REPLACE VIEW view_department_popularity AS
SELECT d.dept_name, COUNT(e.enrollment_id) AS total_students
FROM Departments d
JOIN Courses c ON d.dept_id = c.dept_id
JOIN Enrollments e ON c.course_id = e.course_id
GROUP BY d.dept_name
ORDER BY total_students DESC;


-- ========================================================
-- SECTION 6: CRITICAL OPERATIONS (TRANSACTIONS)
-- Requirement: 1 transaction-based critical operation
-- ========================================================
DELIMITER //
CREATE PROCEDURE sp_RegisterStudent(
    IN p_fname VARCHAR(50), 
    IN p_lname VARCHAR(50), 
    IN p_email VARCHAR(100), 
    IN p_dob DATE
)
BEGIN
    -- Declare exit handler to rollback on any error
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    -- Start the Transaction
    START TRANSACTION;

    -- Perform the Insert
    INSERT INTO Students (first_name, last_name, email, date_of_birth) 
    VALUES (p_fname, p_lname, p_email, p_dob);

    -- If successful, Commit
    COMMIT;
END //
DELIMITER ;
