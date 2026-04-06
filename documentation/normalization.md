# Student Management System Normalization

## UNNORMALIZED FORM (UNF)

**StudentRecords**

| student_id | student_name       | courses              | teacher_names        | grades     |
|------------|------------------|----------------------|----------------------|------------|
| 1111       | Natasha Romanoff | Info.mngt, OD        | Mr. Lee, Ms. Ana     | 1.1, 1.5   |
| 2222       | Wanda Maximoff   | SADD                 | Mr. Cruz             | 1.3        |

---

## FIRST NORMAL FORM (1NF)

**StudentSubjects**

| student_id | student_name       | course       | teacher_name | grade |
|------------|------------------|-------------|--------------|-------|
| 1111       | Natasha Romanoff | Info. Mngt. | Mr. Lee      | 1.1   |
| 1111       | Natasha Romanoff | Org. Dev.   | Ms. Ana      | 1.5   |
| 2222       | Wanda Maximoff   | SADD        | Mr. Cruz     | 1.3   |

---

## SECOND NORMAL FORM (2NF)

### Student

| student_id | student_name       |
|------------|------------------|
| 1111       | Natasha Romanoff |
| 2222       | Wanda Maximoff   |

### Subject

| course      | teacher_name |
|------------|--------------|
| Info. Mngt.| Mr. Lee      |
| Org. Dev.  | Ms. Ana      |
| SADD       | Mr. Cruz     |

### Enrollment

| student_id | course       | grade |
|------------|-------------|-------|
| 1111       | Info. Mngt. | 1.1   |
| 1111       | Org. Dev.   | 1.5   |
| 2222       | SADD        | 1.3   |

---

## THIRD NORMAL FORM (3NF)

### Student

| student_id | first_name | last_name |
|------------|------------|-----------|
| 1111       | Natasha    | Romanoff  |
| 2222       | Wanda      | Maximoff  |

### Teacher

| teacher_id | teacher_name |
|------------|--------------|
| 1          | Mr. Lee      |
| 2          | Ms. Ana      |
| 3          | Mr. Cruz     |

### Subject

| course_id | course_name  | teacher_id |
|-----------|-------------|------------|
| 105       | Info. Mngt. | 1          |
| 103       | Org. Dev.   | 2          |
| 102       | SADD        | 3          |

### Enrollment

| enrollment_id | student_id | course_id |
|---------------|------------|-----------|
| 321           | 1111       | 105       |
| 231           | 1111       | 103       |
| 312           | 2222       | 102       |

### Grade

| student_id | enrollment_id | grade_value |
|------------|---------------|-------------|
| 1111       | 321           | 1.1         |
| 1111       | 231           | 1.5         |
| 2222       | 312           | 1.3         |