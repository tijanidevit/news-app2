<?php
    include_once 'db.class.php';

    class categories extends DB{

        function add_category($category){
            return DB::execute("INSERT INTO categories(category) VALUES(?)", [$category]);
        }

        function fetch_categories(){
            return DB::fetchAll("SELECT * FROM categories ORDER BY category ", []);
        }

        function fetch_limited_categories($limit){
            return DB::fetchAll("SELECT * FROM categories ORDER BY id DESC LIMIT $limit", []);
        }

        function fetch_category($id){
            return DB::fetch("SELECT * FROM categories WHERE id = ? ",[$id] );
        }
        function delete_category($id){
            return DB::execute("DELETE FROM categories WHERE id = ? ",[$id] );
        }
       
        function categories_num(){
            return DB::num_row("SELECT id FROM categories ", []);
        }

        function fetch_last_category(){
            return DB::fetch("SELECT id FROM categories ORDER BY id DESC LIMIT 1 ",[]);
        }

        function lecturer_categories_num($status,$content){
            return DB::num_row("SELECT id FROM categories WHERE content = ? ",[$content]);
        }



        #category posts        
        function category_post_num($id){
            return DB::num_row("SELECT id FROM posts WHERE category_id = ? ", [$id]);
        }   



    }
?>