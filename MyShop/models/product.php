<?php 

include_once 'connexion.php';

class Products {

    private $conn;
     
    public function __construct() {
        $db = new BD();
        $this->conn = $db->getConnect();
    }

    public function getAll() {
        // LEFT JOIN et pas un simple JOIN : on veut quand même voir les
        // produits qui n'ont pas encore de catégorie assignée (category_id
        // à 0), au lieu qu'ils disparaissent silencieusement de la liste.
        $requete = "SELECT products.*, categories.name AS category_name
                    FROM products
                    LEFT JOIN categories ON products.category_id = categories.id";
        $reponse = $this->conn->prepare($requete);
        $reponse->execute();
        return $reponse->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getId($id) {
        // Même jointure que getAll(), pour que la page de détail produit et
        // la page d'édition affichent aussi le nom de la catégorie (et pas
        // juste son id, qui ne veut rien dire pour un humain).
        $requete = "SELECT products.*, categories.name AS category_name
                    FROM products
                    LEFT JOIN categories ON products.category_id = categories.id
                    WHERE products.id = :id";
        $reponse = $this->conn->prepare($requete);
        $reponse->execute([':id' => $id]);
        return $reponse->fetch(PDO::FETCH_ASSOC);
    }

   public function create($name, $description, $price, $picture, $category_id = 0, $created_by = null) {
        $requete = "INSERT INTO products(name, description, price, picture, category_id, created_by)
                    VALUES(:name, :description, :price, :picture, :category_id, :created_by)";
        $reponse = $this->conn->prepare($requete);
        return $reponse->execute([
            ':name' => $name,
            ':description' => $description,
            ':price' => $price,
            ':picture' => $picture,
            // 0 = "pas de catégorie". On aurait pu utiliser NULL, mais la
            // colonne existante en base est en NOT NULL avec 0 par défaut,
            // donc on garde cette convention plutôt que de casser le schéma.
            ':category_id' => $category_id ?: 0,
            // created_by retient qui a ajouté le produit, pour pouvoir
            // limiter plus tard la modification/suppression à cette
            // personne (ou à un admin).
            ':created_by' => $created_by
        ]);
    }

   public function update($id, $name, $description, $price, $picture, $category_id = 0) {
        $requete = "UPDATE products
                    SET name=:name, description=:description, price=:price, picture=:picture, category_id=:category_id
                    WHERE id=:id";
        $reponse = $this->conn->prepare($requete);
        return $reponse->execute([
            ':id' => $id,
            ':name' => $name,
            ':description' => $description,
            ':price' => $price,
            ':picture' => $picture,
            ':category_id' => $category_id ?: 0
        ]);
    }

    public function delete($id) {
        $requete = "DELETE FROM products WHERE id=:id";
        $reponse = $this->conn->prepare($requete);
        return $reponse->execute([':id' => $id]);
    }


    // Le moteur de recherche du sujet : on construit la requête morceau par
    // morceau, en n'ajoutant une condition que si le champ correspondant a
    // été rempli. Comme ça une recherche par prix seul, par nom seul, ou une
    // combinaison des deux, fonctionnent avec le même code.
    public function searchProducts($name, $category, $price, $sort) {

        $sql = "SELECT products.*, categories.name AS category_name
                FROM products
                LEFT JOIN categories ON products.category_id = categories.id
                WHERE 1=1";

        $params = [];

        if(!empty($name)) {
            $sql .= " AND products.name LIKE ?";
            $params[] = "%$name%";
        }

        // $category est maintenant l'id d'une catégorie choisie dans un menu
        // déroulant, pas du texte libre : plus fiable qu'un LIKE sur un nom
        // tapé à la main (fautes de frappe, casse, accents...).
        // On ne peut pas utiliser empty($category) ici : "0" (qui représente
        // "Autre", les produits sans catégorie) est considéré comme vide par
        // PHP alors que c'est une vraie valeur qu'on veut filtrer.
        if($category !== '' && $category !== null) {
            if ((int)$category === 0) {
                // "Autre" : produits qui n'ont pas encore de catégorie assignée.
                $sql .= " AND (products.category_id = 0 OR products.category_id IS NULL)";
            } else {
                $sql .= " AND products.category_id = ?";
                $params[] = (int)$category;
            }
        }

        if(!empty($price)) {
            // On traite le champ "prix" comme un prix MAXIMUM : plus logique
            // pour un client qui a un budget, plutôt qu'une égalité stricte.
            $sql .= " AND products.price <= ?";
            $params[] = $price;
        }

        if(!empty($sort)) {

            // On garde le tri dans un switch plutôt que d'insérer $sort
            // directement dans le ORDER BY : ça évite d'ouvrir une injection
            // SQL par ce paramètre, vu qu'on ne peut pas le préparer comme
            // une valeur classique.
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