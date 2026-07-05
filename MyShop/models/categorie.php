<?php
require_once "connexion.php";

class Category {

    private $conn;

    public function __construct(){
        $db = new BD();
        $this->conn = $db->getConnect();
    }

    // parent_id est ce qui permet les catégories imbriquées demandées dans le
    // sujet (ex: "Mobilier" > "Chaises" > "Chaise en bois"). Une catégorie
    // sans parent (parent_id = null) est une catégorie de "premier niveau".
    // On n'a pas mis de limite de profondeur : rien n'empêche de creuser
    // aussi loin qu'on veut.
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

    public function getById($id){

        $sql = "SELECT * FROM categories WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([":id" => $id]);

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // Renommer une catégorie ou changer son parent. On ne permet pas ici
    // de choisir la catégorie elle-même comme parent (ça créerait une
    // boucle sans fin le jour où on affiche l'arborescence) : ce garde-fou
    // est fait côté vue, avant d'appeler cette méthode.
    public function update($id, $name, $parent = null){

        $sql = "UPDATE categories SET name = :name, parent_id = :parent WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ":name" => $name,
            ":parent" => $parent,
            ":id" => $id
        ]);
    }

    // Combien de sous-catégories pointent vers celle-ci comme parent. On
    // s'en sert pour empêcher une suppression qui laisserait des
    // sous-catégories "orphelines" (avec un parent_id qui ne pointe plus
    // vers rien).
    public function countChildren($id){

        $sql = "SELECT COUNT(*) FROM categories WHERE parent_id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([":id" => $id]);

        return (int) $stmt->fetchColumn();
    }

    // Combien de produits sont actuellement rangés dans cette catégorie.
    // Même logique que countChildren() : on ne veut pas qu'un produit se
    // retrouve avec un category_id qui ne correspond plus à rien.
    public function countProducts($id){

        $sql = "SELECT COUNT(*) FROM products WHERE category_id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([":id" => $id]);

        return (int) $stmt->fetchColumn();
    }

    public function delete($id){

        $sql = "DELETE FROM categories WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([":id" => $id]);
    }

}