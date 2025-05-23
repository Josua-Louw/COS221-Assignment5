<?php
//I have tested this and it has worked on my side

//The database singlton class
class Database {
    //Keeps track of connection
    private $conn;

    public static function instance()
    {
        static $instance = null; // remember that this only ever gets called once
        if($instance === null) $instance = new Database();
        return $instance;
    }

    private function __construct() { 
        $env = parse_ini_file('.env');
        //How the .env file should look like: *note you need to have Xammp running apache and mySQL
        /*
        host=127.0.0.1
        user=<your user name>
        password=<your password>
        DBname=<what you named the database on phpMyAdmin>
        */
        //Creates a connection
        $servername = $env['host'];
        $username = $env['user'];
        $password = $env['password'];
        $DBname = $env['DBname'];
        // Create connection
        try {
            $this->conn = new mysqli($servername, $username, $password, $DBname);
        } catch (Exception $e) {
            throw $e;
        }
        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function __destruct() {
        //deletes connection 
        $this->conn->close();
    }

    public function getConnection() { //sends connection to caller
        return $this->conn;
    }
}

?>