<?php
require_once "connexion.php";

class Category {

    private $conn;

    public function __construct(){
        $db = new BD();
        $this->conn = $db->getConnect();
    }

    public function add($name, $parent = null){

        $sql = "INSERT INTO categories (name, parent_id) VALUES (:name, :parent)";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ":name" => $name,
            ":parent" => $parent
        ]);
    }


    public function getAll(){

        $sql = "SELECT * FROM categories";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

}