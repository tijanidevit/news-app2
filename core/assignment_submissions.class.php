<?php
    include_once 'db.class.php';

    class assignment_submissions extends DB{

        function add_assignment_submission($student_id,$type,$amount){
            return DB::execute("INSERT INTO assignment_submissions(student_id,type,amount) VALUES(?,?,?)", [$student_id,$type,$amount]);
        }
        function fetch_assignment_submissions(){
            return DB::fetchAll("SELECT *,assignment_submissions.id,assignment_submissions.created_at FROM assignment_submissions
            LEFT OUTER JOIN students on students.id = assignment_submissions.student_id
            LEFT OUTER JOIN assignments on assignments.id = assignment_submissions.assignment_id
            LEFT OUTER JOIN courses on courses.id = assignments.course_id
            ORDER BY assignment_submissions.id DESC ", []);
        }

        function fetch_limited_assignment_submissions($status,$limit){
            return DB::fetchAll("SELECT *,assignment_submissions.id,assignment_submissions.created_at FROM assignment_submissions
            LEFT OUTER JOIN students on students.id = assignment_submissions.student_id
            LEFT OUTER JOIN assignments on assignments.id = assignment_submissions.assignment_id
            LEFT OUTER JOIN courses on courses.id = assignments.course_id
            WHERE status = ? LIMIT $limit
            ORDER BY assignment_submissions.id DESC ", [$status]);
        }

        function fetch_graded_assignment_submissions(){
            return DB::fetchAll("SELECT *,assignment_submissions.id,assignment_submissions.created_at FROM assignment_submissions
            LEFT OUTER JOIN students on students.id = assignment_submissions.student_id
            LEFT OUTER JOIN assignments on assignments.id = assignment_submissions.assignment_id
            LEFT OUTER JOIN courses on courses.id = assignments.course_id
            WHERE feedback <> ''
            ORDER BY assignment_submissions.id DESC ", []);
        }

        function fetch_ungraded_assignment_submissions(){
            return DB::fetchAll("SELECT *,assignment_submissions.id,assignment_submissions.created_at FROM assignment_submissions
            LEFT OUTER JOIN students on students.id = assignment_submissions.student_id
            LEFT OUTER JOIN assignments on assignments.id = assignment_submissions.assignment_id
            LEFT OUTER JOIN courses on courses.id = assignments.course_id
            WHERE feedback = '' 
            ORDER BY assignment_submissions.id DESC ", []);
        }

        function fetch_assignment_submission($id){
            return DB::fetch("SELECT *,assignment_submissions.id,assignment_submissions.created_at,lecturers.image AS lec_image,lecturers.fullname AS lec_name FROM assignment_submissions             
            LEFT OUTER JOIN assignments on assignments.id = assignment_submissions.assignment_id
            LEFT OUTER JOIN lecturers on lecturers.id = assignments.lecturer_id
            LEFT OUTER JOIN students on students.id = assignment_submissions.student_id
            LEFT OUTER JOIN courses on courses.id = assignments.course_id
            WHERE assignment_submissions.id = ? ",[$id] );
        }

        function delete_assignment_submission($id){
            return DB::execute("DELETE FROM assignment_submissions WHERE id = ? ",[$id] );
        }

        function update_assignment_submission_status($status,$id){
            return DB::execute("UPDATE assignment_submissions SET status = ? WHERE id = ? ", [$status,$id]);
        }
       
        function assignment_submissions_num(){
            return DB::num_row("SELECT id FROM assignment_submissions ", []);
        }
       
        function check_review_existence($id){
            return DB::num_row("SELECT id FROM assignment_submissions WHERE id = ? AND feedback <> '' ", [$id]);
        } 
       
        function review_assignment($grade,$feedback,$id){
            return DB::execute("UPDATE assignment_submissions SET grade = ?, feedback = ? WHERE id = ? ", [$grade,$feedback,$id]);
        } 



       
        function check_student_assignment_submission($assignment_id,$student_id){
            return DB::num_row("SELECT id FROM assignment_submissions WHERE assignment_id = ? AND student_id = ? ", [$assignment_id,$student_id]);
        }

        function fetch_student_assignment_submission($assignment_id,$student_id){
            return DB::fetch("SELECT * FROM assignment_submissions WHERE assignment_id = ? AND student_id = ? ", [$assignment_id,$student_id]);
        }
    }
?>