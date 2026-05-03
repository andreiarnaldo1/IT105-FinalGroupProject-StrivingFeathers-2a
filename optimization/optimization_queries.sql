-- IT 105: Phase 5 - Database Optimization
-- Project: EduTrack Student Management System
-- Team: StrivingFeathers
-- SQL Developer: Arjiannah Carmelle Borleo

USE edutrack_db;

-- ========================================================
-- PART 1: OPTIMIZATION (INDEX CREATION)
-- ========================================================

-- 1. Optimizing student lookups by first name 
-- (Reduces search time from Full Table Scan to Indexed search)
CREATE INDEX idx_student_name ON Students(first_name);

-- 2. Optimizing enrollment lookups by semester
-- (Handles large-scale data searching across 90,000+ rows)
CREATE INDEX idx_enrollment_semester ON Enrollments(semester);

-- 3. Optimizing course lookups by title
-- (Speeds up catalog searching for students and admins)
CREATE INDEX idx_course_title ON Courses(course_title);


-- ========================================================
-- PART 2: PERFORMANCE VERIFICATION (EXPLAIN QUERIES)
-- ========================================================

-- Verify Student Search Optimization
-- (Should show 'ref' in type and low row count)
EXPLAIN SELECT * FROM Students WHERE first_name = 'Holly';

-- Verify Enrollment Search Optimization
-- (Should no longer scan all 90,000+ rows)
EXPLAIN SELECT * FROM Enrollments WHERE semester = '1st';

-- Verify Course Search Optimization
EXPLAIN SELECT * FROM Courses WHERE course_title = 'User-centric fresh-thinking protocol';