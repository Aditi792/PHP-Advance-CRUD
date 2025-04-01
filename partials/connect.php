<?php

    class connect{
        private $db_server = "localhost";
        private $db_user = "root";
        private $db_name = "userdata";
        private $db_pass = "";
        protected $conn;

    public function __construct() {

        try{
            $dsn = "mysql:host={$this->db_server};
                dbname={$this->db_name};
                charset=utf8";
            $options = array(PDO::ATTR_PERSISTENT=>true);//optional
            $this->conn = new PDO($dsn,$this->db_user,$this->db_pass,$options);
        }
        catch(PDOException $e){
            echo "Connection Error" . $e->getMessage();
        }
    }
}
?>