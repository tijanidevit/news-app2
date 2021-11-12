<?php
    include_once 'db.class.php';

    class Students extends DB{

        function register($matric_no,$fullname,$image,$email,$gender,$level_id,$password){
            return DB::execute("INSERT INTO students(matric_no,fullname,image,email,gender,level_id,password) VALUES(?,?,?,?,?,?,?)", [$matric_no,$fullname,$image,$email,$gender,$level_id,$password]);
        }
        
        function fetch_students(){
            return DB::fetchAll("SELECT * FROM students
            JOIN levels on levels.id = students.level_id
            ORDER BY fullname ASC",[]);
        }
        function fetch_student($email){
            return DB::fetch("SELECT * FROM students
            JOIN levels on levels.id = students.level_id
            WHERE email = ? OR id = ?",[$email,$email] );
        }
        function fetch_student_rating($id){
            return DB::fetch("SELECT student_rating FROM students WHERE id = ? ",[$id] );
        }
        function update_student($matric_no,$fullname,$image,$email,$id){
            return DB::execute("UPDATE students SET matric_no =?, fullname =?, image =?, email =?, password =? WHERE id = ? ", [$matric_no,$fullname,$image,$email,$id]);
        }
        function update_password($password,$id){
            return DB::execute("UPDATE students SET password =? WHERE id = ? ", [$password,$id]);
        }

        function students_num(){
            return DB::num_row("SELECT id FROM students ", []);
        }

        function check_email_existence($email){
            return DB::num_row("SELECT id FROM students WHERE email = ? ", [$email]);
        }

        function login($email,$password){
            if (DB::num_row("SELECT id FROM students WHERE email = ? AND password = ? ", [$email,$password]) > 0) {
                return true;
            }
            else{
                return false;
            }
        }

        ###### student's Assignment_submissions
        function student_assignments_num($student_id){
            return DB::num_row("SELECT id FROM assignment_submissions WHERE student_id = ? ",[$student_id]);
        }

        function fetch_student_assignment_submissions($student_id){
            return DB::fetchAll("SELECT *,assignment_submissions.id FROM assignment_submissions
            JOIN assignments on assignments.id = assignment_submissions.assignment_id
            JOIN courses on courses.id = assignments.course_id
            WHERE assignment_submissions.student_id = ?
            ORDER BY assignment_submissions.id DESC ",[$student_id]);
        }

        function fetch_limited_student_assignment_submissions($student_id,$limit){
            return DB::fetchAll("SELECT *,assignment_submissions.id FROM assignment_submissions
            JOIN assignments on assignments.id = assignment_submissions.assignment_id
            JOIN courses on courses.id = assignments.course_id
            WHERE assignment_submissions.student_id = ?
            ORDER BY assignment_submissions.id DESC LIMIT $limit",[$student_id]);
        }


        function fetch_limited_graded_assignment_submissions($student_id,$limit){
            return DB::fetchAll("SELECT *,assignment_submissions.id,assignment_submissions.created_at FROM assignment_submissions
            LEFT OUTER JOIN assignments on assignments.id = assignment_submissions.assignment_id
            LEFT OUTER JOIN lecturers on lecturers.id = assignments.lecturer_id
            LEFT OUTER JOIN courses on courses.id = assignments.course_id
            WHERE feedback <> '' AND student_id = ? 
            ORDER BY assignment_submissions.id DESC LIMIT $limit", [$student_id]);
        }

        function fetch_limited_ungraded_assignment_submissions($student_id,$limit){
            return DB::fetchAll("SELECT *,assignment_submissions.id,assignment_submissions.created_at FROM assignment_submissions
            LEFT OUTER JOIN assignments on assignments.id = assignment_submissions.assignment_id
            LEFT OUTER JOIN lecturers on lecturers.id = assignments.lecturer_id
            LEFT OUTER JOIN courses on courses.id = assignments.course_id
            WHERE feedback = '' AND student_id = ?  
            ORDER BY assignment_submissions.id DESC LIMIT $limit", [$student_id]);
        }
    }
?>