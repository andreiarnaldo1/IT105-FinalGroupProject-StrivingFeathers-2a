import csv
import random
from faker import Faker

fake = Faker()

# SETTINGS
NUM_DEPARTMENTS = 10
NUM_INSTRUCTORS = 100
NUM_STUDENTS = 10000
NUM_COURSES = 200
NUM_ENROLLMENTS = 90000 

print("Step 1/5: Generating Departments...")
depts = ["IT", "Computer Science", "Engineering", "Business", "Arts", "Science", "Nursing", "Education", "Architecture", "Mathematics"]
with open('departments.csv', 'w', newline='') as f:
    writer = csv.writer(f)
    writer.writerow(['dept_id', 'dept_name'])
    for i, name in enumerate(depts, 1):
        writer.writerow([i, name])

print("Step 2/5: Generating Instructors...")
with open('instructors.csv', 'w', newline='') as f:
    writer = csv.writer(f)
    writer.writerow(['instructor_id', 'first_name', 'last_name', 'email', 'dept_id'])
    for i in range(1, NUM_INSTRUCTORS + 1):
        writer.writerow([i, fake.first_name(), fake.last_name(), fake.unique.email(), random.randint(1, NUM_DEPARTMENTS)])

print("Step 3/5: Generating Students...")
with open('students.csv', 'w', newline='') as f:
    writer = csv.writer(f)
    writer.writerow(['student_id', 'first_name', 'last_name', 'email', 'dob', 'enroll_date'])
    for i in range(1, NUM_STUDENTS + 1):
        writer.writerow([i, fake.first_name(), fake.last_name(), fake.unique.email(), fake.date_of_birth(minimum_age=18, maximum_age=25), '2024-01-15'])

print("Step 4/5: Generating Courses...")
with open('courses.csv', 'w', newline='') as f:
    writer = csv.writer(f)
    writer.writerow(['course_id', 'course_code', 'course_title', 'credits', 'dept_id'])
    for i in range(1, NUM_COURSES + 1):
        writer.writerow([i, f"CS{100+i}", fake.catch_phrase(), random.randint(1, 5), random.randint(1, NUM_DEPARTMENTS)])

print("Step 5/5: Generating 90,000 Enrollments...")
with open('enrollments.csv', 'w', newline='') as f:
    writer = csv.writer(f)
    writer.writerow(['enrollment_id', 'student_id', 'course_id', 'instructor_id', 'semester', 'academic_year', 'grade'])
    for i in range(1, NUM_ENROLLMENTS + 1):
        writer.writerow([i, random.randint(1, NUM_STUDENTS), random.randint(1, NUM_COURSES), random.randint(1, NUM_INSTRUCTORS), random.choice(['1st', '2nd', 'Summer']), 2024, round(random.uniform(1.0, 5.0), 2)])

print("\nSUCCESS! 100,000+ rows generated in 5 CSV files.")