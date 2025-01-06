<?php
class Database
{
    private $host = "localhost";
    private $db_name = "driveloc";
    private $username = "root";
    private $password = "";
    private $conn;
    private $error;

    public function __construct()
    {
        $this->conn = null;
        $this->connect();
    }

    public function connect()
    {
        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->db_name", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // echo "Connected to Database successfully";
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo $this->error;
        }
        
        return $this->conn;
    }

    public function disconnect() {

        $this->conn = null;

    }
}
