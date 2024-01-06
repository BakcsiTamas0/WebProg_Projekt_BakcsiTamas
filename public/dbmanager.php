<?php

class DbManager
{

    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "phpprojekt";
    private $conn;

    public function __construct()
    {
        $this->servername = "localhost";
        $this->username = "root";
        $this->password = "";
        $this->dbname = "phpprojekt";
    }

    public function connect()
    {
        try{
            $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
            return $conn;
        } catch (Exception $e){
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public function executeQuery($sql)
    {
        try{
            $conn = $this->connect();
            $result = $conn->query($sql);
            $conn->close();
            return $result;
        } catch (Exception $e){
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public function closeConnection()
    {
        try{
            $conn = $this->connect();
            $conn->close();
        } catch (Exception $e){
            echo "Connection failed: " . $e->getMessage();
        }
    }
}

?>