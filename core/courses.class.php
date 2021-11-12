<?php
    include_once 'db.class.php';

    class courses extends DB{

        function add_course($course){
            return DB::execute("INSERT INTO courses(course) VALUES(?)", [$course]);
        }
        function fetch_courses(){
            return DB::fetchAll("SELECT * FROM courses ORDER BY course_code ",[]);
        }
        function fetch_limited_courses($limit){
            return DB::fetchAll("SELECT * FROM courses ORDER BY course_code LIMIT $limit ",[]);
        }
        function fetch_course($id){
            return DB::fetch("SELECT * FROM courses WHERE id = ? ",[$id] );
        }
        function delete_course($id){
            return DB::execute("DELETE FROM courses WHERE id = ? ",[$id] );
        }
        function update_course($course,$id){
            return DB::execute("UPDATE courses SET course = ?  WHERE id = ? ", [$course,$id]);
        }
        function courses_num(){
            return DB::num_row("SELECT id FROM courses ", []);
        }


        function check_course_existence($course){
            if (DB::num_row("SELECT id FROM courses WHERE course = ?", [$course]) > 0){
                return true;
            }
            else{
                return false;
            }
        }

        function check_edit_course_existence($course,$id){
            if (DB::num_row("SELECT id FROM courses WHERE course = ? AND id <> ? ", [$course,$id]) > 0) {
                return true;
            }
            else{
                return false;
            }
        }


        function fetch_course_assignments($course_id){
            return DB::fetchAll("SELECT *,assignments.id FROM assignments
            JOIN courses on courses.id = assignments.course_id
            WHERE assignments.course_id = ? 
            ORDER BY assignments.id DESC ",[$course_id]);
        }

        function course_assignments_num($course_id){
            return DB::num_row("SELECT assignments.id FROM assignments WHERE assignments.course_id = ?",[$course_id]);
        }
    }
?>