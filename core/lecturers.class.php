<?php
    include_once 'db.class.php';

    class Lecturers extends DB{

        function register($staff_id,$fullname,$image,$email,$password){
            return DB::execute("INSERT INTO lecturers(staff_id,fullname,image,email,password) VALUES(?,?,?,?,?)", [$staff_id,$fullname,$image,$email,$password]);
        }
        
        function fetch_lecturers(){
            return DB::fetchAll("SELECT * FROM lecturers ORDER BY fullname ASC",[]);
        }
        function fetch_lecturer($email){
            return DB::fetch("SELECT * FROM lecturers WHERE email = ? OR id = ?",[$email,$email] );
        }
        function fetch_lecturer_rating($id){
            return DB::fetch("SELECT lecturer_rating FROM lecturers WHERE id = ? ",[$id] );
        }
        function update_lecturer($staff_id,$fullname,$image,$email,$id){
            return DB::execute("UPDATE lecturers SET staff_id =?, fullname =?, image =?, email =?, password =? WHERE id = ? ", [$staff_id,$fullname,$image,$email,$id]);
        }
        function update_password($password,$id){
            return DB::execute("UPDATE lecturers SET password =? WHERE id = ? ", [$password,$id]);
        }

        function lecturers_num(){
            return DB::num_row("SELECT id FROM lecturers ", []);
        }

        function check_email_existence($email){
            return DB::num_row("SELECT id FROM lecturers WHERE email = ? ", [$email]);
        }

        function login($email,$password){
            if (DB::num_row("SELECT id FROM lecturers WHERE email = ?  AND password = ? ", [$email,$password]) > 0) {
                return true;
            }
            else{
                return false;
            }
        }

        ###### lecturer's Assignments
        function fetch_lecturer_assignments($lecturer_id){
            return DB::fetchAll("SELECT *,assignments.id FROM assignments
            JOIN lecturers on lecturers.id = assignments.lecturer_id
            JOIN courses on courses.id = assignments.course_id
            WHERE assignments.lecturer_id = ?
            ORDER BY assignments.id DESC ",[$lecturer_id]);
        }
        function fetch_lecturer_assignments_num($lecturer_id){
            return DB::num_row("SELECT id FROM assignments WHERE assignments.lecturer_id = ? ",[$lecturer_id]);
        }

        function fetch_limited_lecturer_assignments($lecturer_id,$limit){
            return DB::fetchAll("SELECT *,assignments.id FROM assignments
            JOIN lecturers on lecturers.id = assignments.lecturer_id
            JOIN courses on courses.id = assignments.course_id
            WHERE assignments.lecturer_id = ?
            ORDER BY assignments.id DESC LIMIT $limit",[$lecturer_id]);
        }


        function fetch_lecturer_assignment_submissions($lecturer_id){
            return DB::fetchAll("SELECT *,assignment_submissions.id FROM assignment_submissions
            JOIN assignments on assignments.id = assignment_submissions.assignment_id
            JOIN students on students.id = assignment_submissions.student_id
            JOIN courses on courses.id = assignments.course_id
            WHERE assignments.lecturer_id = ?
            ORDER BY assignment_submissions.id DESC ",[$lecturer_id]);
        }
        function fetch_lecturer_assignment_submissions_num($lecturer_id){
            return DB::num_row("SELECT assignment_submissions.id FROM assignment_submissions 
            JOIN assignments on assignments.id = assignment_submissions.assignment_id
            WHERE assignments.lecturer_id = ? ",[$lecturer_id]);
        }

        function fetch_limited_lecturer_assignment_submissions($lecturer_id,$limit){
            return DB::fetchAll("SELECT *,assignment_submissions.id FROM assignment_submissions
            JOIN assignments on assignments.id = assignment_submissions.assignment_id
            JOIN students on students.id = assignment_submissions.student_id
            JOIN courses on courses.id = assignments.course_id
            WHERE assignments.lecturer_id = ?
            ORDER BY assignment_submissions.id DESC LIMIT $limit",[$lecturer_id]);
        }
    }
?>