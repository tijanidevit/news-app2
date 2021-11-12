<?php
    include_once 'db.class.php';
    class Admin extends DB{

        function login($email,$password){
            if (DB::num_row("SELECT id FROM admin WHERE email = ? AND password = ? ", [$email,$password])) {
                return true;
            }
            else{
                return false;
            }
        }

        function check_email_existence($email){
            if (DB::num_row("SELECT id FROM admin WHERE email = ? ", [$email])) {
                return true;
            }
            else{
                return false;
            }
        }

        function fetch_admin($email){
            $admin = DB::fetch("SELECT id FROM admin WHERE email = ? OR id = ? ", [$email,$email]);
            if($admin){
                return $admin;
            }
            else{
                return false;
            }
        }
    }
?>