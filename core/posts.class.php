<?php
    include_once 'db.class.php';

    class posts extends DB{

        function add_post($category_id,$title,$content,$featured_image){
            return DB::execute("INSERT INTO posts(category_id,title,content,featured_image) VALUES(?,?,?,?)", [$category_id,$title,$content,$featured_image]);
        }
        function fetch_posts(){
            return DB::fetchAll("SELECT *, posts.id, posts.status, posts.created_at FROM posts
            LEFT OUTER JOIN categories ON categories.id = posts.category_id
            ORDER BY posts.id DESC ", []);
        }

        function fetch_limited_posts($limit){
            return DB::fetchAll("SELECT *, posts.id, posts.status, posts.created_at FROM posts
            LEFT OUTER JOIN categories ON categories.id = posts.category_id
            ORDER BY posts.id DESC LIMIT $limit", []);
        }

        function fetch_post($id){
            return DB::fetch("SELECT *, posts.id, posts.status, posts.created_at FROM posts
            LEFT OUTER JOIN categories ON categories.id = posts.category_id
            WHERE posts.id = ? ",[$id] );
        }
        function delete_post($id){
            return DB::execute("DELETE FROM posts WHERE id = ? ",[$id] );
        }

        function update_post_status($status,$id){
            return DB::execute("UPDATE posts SET status = ? WHERE posts.id = ? ", [$status,$id]);
        }
       
        function posts_num(){
            return DB::num_row("SELECT id FROM posts ", []);
        }

        function fetch_last_post(){
            return DB::fetch("SELECT id FROM posts ORDER BY posts.id DESC LIMIT 1 ",[]);
        }

        function lecturer_posts_num($status,$content){
            return DB::num_row("SELECT id FROM posts WHERE content = ? ",[$content]);
        }



        #post comments        

        function fetch_post_comments($post_id){
            return DB::fetchAll("SELECT *,post_comments.id,post_comments.created_at FROM post_comments
            LEFT OUTER JOIN students on students.id = post_comments.sender
            WHERE post_id = ?
            ORDER BY post_comments.id DESC ", [$post_id]);
        }     



    }
?>