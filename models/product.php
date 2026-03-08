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

   public function create($name, $description, $price, $picture) {
        $requete = "INSERT INTO products(name, description, price, picture) 
                    VALUES(:name, :description, :price, :picture)";
        $reponse = $this->conn->prepare($requete);
        return $reponse->execute([
            ':name' => $name,
            ':description' => $description,
            ':price' => $price,
            ':picture' => $picture 
        ]);
    }

   public function update($id, $name, $description, $price, $picture) {
        $requete = "UPDATE products 
                    SET name=:name, description=:description, price=:price, picture=:picture
                    WHERE id=:id"; 
        $reponse = $this->conn->prepare($requete);
        return $reponse->execute([
            ':id' => $id,
            ':name' => $name,
            ':description' => $description,
            ':price' => $price,
            ':picture' => $picture 
        ]);
    }

    public function delete($id) {
        $requete = "DELETE FROM products WHERE id=:id";
        $reponse = $this->conn->prepare($requete);
        return $reponse->execute([':id' => $id]);
    }

    /* RECHERCHE CORRIGÉE */

    public function searchProducts($name, $category, $price, $sort) {

        $sql = "SELECT products.*, categories.name AS category_name
                FROM products
                LEFT JOIN categories ON products.id_category = categories.id
                WHERE 1=1";

        $params = [];

        if(!empty($name)) {
            $sql .= " AND products.name LIKE ?";
            $params[] = "%$name%";
        }

        if(!empty($category)) {
            $sql .= " AND categories.name LIKE ?";
            $params[] = "%$category%";
        }

        if(!empty($price)) {
            $sql .= " AND products.price <= ?";
            $params[] = $price;
        }

        if(!empty($sort)) {

            switch($sort) {

                case "name_asc":
                    $sql .= " ORDER BY products.name ASC";
                    break;

                case "name_desc":
                    $sql .= " ORDER BY products.name DESC";
                    break;

                case "price_asc":
                    $sql .= " ORDER BY products.price ASC";
                    break;

                case "price_desc":
                    $sql .= " ORDER BY products.price DESC";
                    break;

            }
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}