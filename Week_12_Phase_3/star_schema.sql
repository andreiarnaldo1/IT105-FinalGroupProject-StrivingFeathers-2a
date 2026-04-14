Table fact_performance {
  performance_id int [pk]
  student_key int [ref: > dim_student.student_key]
  course_key int [ref: > dim_course.course_key]
  time_key int [ref: > dim_time.time_key]
  final_grade decimal
}

Table dim_student {
  student_key int [pk]
  name varchar
  email varchar
}

Table dim_course {
  course_key int [pk]
  title varchar
  department varchar
}

Table dim_time {
  time_key int [pk]
  semester varchar
  academic_year int
}