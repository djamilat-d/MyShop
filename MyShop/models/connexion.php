<?php

// Toute la logique de connexion à la base est regroupée ici, dans une seule
// classe. Comme ça, si un jour on change d'hébergeur ou de mot de passe,
// on a un seul endroit à modifier au lieu de chercher dans tous les fichiers.
class BD {

    private $host;
    private $dbname;
    private $username;
    private $password;
    private $conn = null;

    public function __construct(){

        // On regarde le nom de domaine sur lequel le site tourne pour savoir
        // si on est en local (XAMPP) ou en ligne (InfinityFree) : comme ça
        // pas besoin de changer ce fichier à la main à chaque fois qu'on
        // bascule entre développement et production.
        $host = $_SERVER['SERVER_NAME'] ?? '';

        if (strpos($host, 'freedev.app') !== false) {

            // Production (InfinityFree). Ces identifiants viennent du
            // vPanel, section "Bases de données MySQL".
            $this->host = "sql312.infinityfree.com";
            $this->dbname = "if0_42341538_my_shop";
            $this->username = "if0_42341538";
            $this->password = "QkV0MgiHMXk";

        } else {

            // Local (XAMPP/MAMP). On utilise 127.0.0.1 plutôt que
            // "localhost" : "localhost" pousse PDO à essayer une connexion
            // par socket Unix, et sur certaines installs ce socket n'existe
            // pas au bon endroit (erreur "SQLSTATE[HY000] [2002] No such
            // file or directory"). 127.0.0.1 force une connexion TCP
            // classique, qui marche partout.
            $this->host = "127.0.0.1";
            $this->dbname = "my_shop";
            $this->username = "root";
            $this->password = "";
        }
    }

    public function getConnect(){

        // On ne crée la connexion qu'une seule fois (singleton simplifié) :
        // pas besoin de rouvrir une connexion PDO à chaque appel de getConnect().
        if($this->conn === null){

            try{

                $this->conn = new PDO(
                    "mysql:host=".$this->host.";dbname=".$this->dbname.";charset=utf8",
                    $this->username,
                    $this->password
                );

                // ERRMODE_EXCEPTION : si une requête échoue, on veut une vraie
                // exception PHP (qu'on peut attraper), pas juste un warning
                // silencieux qu'on risque de louper.
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            }catch(PDOException $e){
                // On arrête tout de suite si la base n'est pas joignable :
                // continuer l'exécution sans connexion ferait planter toutes
                // les requêtes plus loin avec des erreurs moins claires.
                die("Erreur connexion : " . $e->getMessage());
            }

        }

        return $this->conn;
    }
}

// On instancie la connexion une fois ici et on l'expose en variable globale,
// pour que les fichiers qui font juste "require connexion.php" (comme
// signin.php) puissent utiliser $pdo directement sans réinstancier BD.
$database = new BD();
$pdo = $database->getConnect();

$conn = $pdo;

?>