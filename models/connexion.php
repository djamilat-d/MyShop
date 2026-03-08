<?php

class BD {

    private $host = "localhost";
    private $dbname = "my_shop";
    private $username = "root";
    private $password = "Akanoam";
    private $conn = null;

    public function getConnect(){

        if($this->conn === null){

            try{

                $this->conn = new PDO(
                    "mysql:host=".$this->host.";dbname=".$this->dbname.";charset=utf8",
                    $this->username,
                    $this->password
                );

                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            }catch(PDOException $e){
                die("Erreur connexion : " . $e->getMessage());
            }

        }

        return $this->conn;
    }
}


$database = new BD();
$pdo = $database->getConnect();


$conn = $pdo;

?>