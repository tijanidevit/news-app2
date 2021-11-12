<?php
    class DB {
        public $host = 'us-cdbr-east-04.cleardb.com';
        public $dbname = 'heroku_929c655e0f8d398';
        public $password = 'f93b6393';
        public $username = 'bc2b081e14412e';

    //     'hostname' => 'us-cdbr-east-04.cleardb.com',
    // 'username' => 'bc2b081e14412e',
    // 'password' => 'f93b6393',
    // 'database' => 'heroku_929c655e0f8d398',
        
        public $conn;

        function __construct(){
            try {
                $this->conn = new PDO("mysql:host=$this->host;dbname=$this->dbname;", $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $ex) {
                echo 'Error in database connection '. $ex->getMessage();
            }
        }
        function prep($query){
            return $this->conn->prepare($query);
        }
        function execute($query,$value){
            $query = $this->prep($query);
            return $query->execute($value);
        }
        function fetch($query,$value){
            $query = $this->prep($query);
            $query->execute($value);
            return $query->fetch();
        }
        function fetchAll($query,$value){
            $query = $this->prep($query);
            $query->execute($value);
            return $query->fetchAll();
        }
        function num_row($query,$value){
            $query = $this->prep($query);
            $query->execute($value);
            return $query->rowCount();
        }
    }
?>