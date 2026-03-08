<?php 

include_once 'connexion.php';

class Products {

    private $conn;
     
    public function __construct() {
        $db = new BD();
        $this->conn = $db->getConnect();
    }

    public function getAll() {
        $requete = "SELECT * FROM products";
        $reponse = $this->conn->prepare($requete);
        $reponse->execute();
        return $reponse->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getId($id) {
        $requete = "SELECT * FROM products WHERE id = :id";
        $reponse = $this->conn->prepare($requete);
        $reponse->execute([':id' => $id]);
        return $reponse->fetch(PDO::FETCH_ASSOC);
    }

    public function create($name, $description, $price, $image) {
        $requete = "INSERT INTO products(name, description, price, image) 
                    VALUES(:name, :description, :price, :image)";
        $reponse = $this->conn->prepare($requete);
        return $reponse->execute([
            ':name' => $name,
            ':description' => $description,
            ':price' => $price,
            ':image' => $image
        ]);
    }

    public function update($id, $name, $description, $price, $image) {
        $requete = "UPDATE products 
                    SET name=:name, description=:description, price=:price, image=:image 
                    WHERE id=:id";
        $reponse = $this->conn->prepare($requete);
        return $reponse->execute([
            ':id' => $id,
            ':name' => $name,
            ':description' => $description,
            ':price' => $price,
            ':image' => $image
        ]);
    }

    public function delete($id) {
        $requete = "DELETE FROM products WHERE id=:id";
        $reponse = $this->conn->prepare($requete);
        return $reponse->execute([':id' => $id]);
    }

    public function searchProducts($name, $category, $price, $sort) {
        $sql = "SELECT * FROM products WHERE 1=1";
        $params = [];

        if(!empty($name)) {
            $sql .= " AND name LIKE ?";
            $params[] = "%$name%";
        }

        if(!empty($price)) {
            $sql .= " AND price <= ?";
            $params[] = $price;
        }

        if(!empty($sort)) {
            switch($sort) {
                case "name_asc":
                    $sql .= " ORDER BY name ASC";
                    break;
                case "name_desc":
                    $sql .= " ORDER BY name DESC";
                    break;
                case "price_asc":
                    $sql .= " ORDER BY price ASC";
                    break;
                case "price_desc":
                    $sql .= " ORDER BY price DESC";
                    break;
            }
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
