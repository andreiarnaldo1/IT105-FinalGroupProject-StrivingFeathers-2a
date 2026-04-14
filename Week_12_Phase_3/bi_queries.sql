USE edutrack_db;

-- Query 1: Average GPA per Department (KPI: Academic Performance)
SELECT d.dept_name, ROUND(AVG(e.grade), 2) AS average_gpa
FROM Enrollments e
JOIN Courses c ON e.course_id = c.course_id
JOIN Departments d ON c.dept_id = d.dept_id
GROUP BY d.dept_name
ORDER BY average_gpa ASC; -- Assuming 1.0 is best

-- Query 2: Enrollment Growth by Year/Semester (KPI: Scale)
SELECT academic_year, semester, COUNT(enrollment_id) AS total_students
FROM Enrollments
GROUP BY academic_year, semester
ORDER BY academic_year, semester;

-- Query 3: Most Popular Courses (Top 10)
SELECT c.course_title, d.dept_name, COUNT(e.student_id) AS enrollment_count
FROM Enrollments e
JOIN Courses c ON e.course_id = c.course_id
JOIN Departments d ON c.dept_id = d.dept_id
GROUP BY c.course_id
ORDER BY enrollment_count DESC
LIMIT 10;

-- Query 4: Instructor "Heavy Load" List (Instructors with > 1000 total students)
SELECT i.first_name, i.last_name, COUNT(e.student_id) AS total_students_handled
FROM Enrollments e
JOIN Instructors i ON e.instructor_id = i.instructor_id
GROUP BY i.instructor_id
HAVING total_students_handled > 1000;

-- Query 5: Failed/Low Grade Rate per Course
-- (Helps identify which subjects need more tutors)
SELECT c.course_title, COUNT(e.enrollment_id) AS low_grade_count
FROM Enrollments e
JOIN Courses c ON e.course_id = c.course_id
WHERE e.grade > 3.0 -- Assuming 3.0+ is a struggling grade
GROUP BY c.course_id
ORDER BY low_grade_count DESC;