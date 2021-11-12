<?php
    include_once 'db.class.php';

    class gists extends DB{

        function add_gist($category_id,$student_id,$title,$content,$featured_image){
            return DB::execute("INSERT INTO gists(category_id,student_id,title,content,featured_image) VALUES(?,?,?,?)", [$category_id,$student_id,$title,$content,$featured_image]);
        }
        function fetch_gists(){
            return DB::fetchAll("SELECT *, gists.id, gists.created_at, gists.status FROM gists
            LEFT OUTER JOIN categories ON categories.id = gists.category_id
            LEFT OUTER JOIN students ON students.id = gists.student_id
            ORDER BY gists.id DESC ", []);
        }

        function fetch_limited_gists($limit){
            return DB::fetchAll("SELECT *, gists.id, gists.created_at, gists.status FROM gists
            LEFT OUTER JOIN students ON students.id = gists.student_id
            LEFT OUTER JOIN categories ON categories.id = gists.category_id
            ORDER BY gists.id DESC LIMIT $limit", []);
        }

        function fetch_gist($id){
            return DB::fetch("SELECT *, gists.id, gists.created_at, gists.status FROM gists
            LEFT OUTER JOIN students ON students.id = gists.student_id
            LEFT OUTER JOIN categories ON categories.id = gists.category_id
            WHERE gists.id = ? ",[$id] );
        }
        function delete_gist($id){
            return DB::execute("DELETE FROM gists WHERE id = ? ",[$id] );
        }

        function update_gist_status($status,$id){
            return DB::execute("UPDATE gists SET status = ? WHERE gists.id = ? ", [$status,$id]);
        }
       
        function gists_num(){
            return DB::num_row("SELECT id FROM gists ", []);
        }

        function fetch_last_gist(){
            return DB::fetch("SELECT id FROM gists ORDER BY gists.id DESC LIMIT 1 ",[]);
        }

        function lecturer_gists_num($status,$content){
            return DB::num_row("SELECT id FROM gists WHERE content = ? ",[$content]);
        }



        #gist comments        

        function fetch_gist_comments($gist_id){
            return DB::fetchAll("SELECT *,comments.id,comments.created_at FROM comments
            LEFT OUTER JOIN students on students.id = comments.student_id
            WHERE gist_id = ?
            ORDER BY comments.id DESC ", [$gist_id]);
        }     



    }
?>