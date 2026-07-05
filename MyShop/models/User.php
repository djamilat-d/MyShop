<?php
include_once 'connexion.php';
class User {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    
    public function createUser($username, $email, $password, $admin = 0) {

        // On vérifie l'email en base AVANT d'essayer d'insérer, plutôt que de
        // laisser MySQL renvoyer une erreur de contrainte UNIQUE : ça nous
        // donne un message clair ("Email already exists.") au lieu d'un
        // plantage brut, comme demandé dans le sujet (messages d'erreur explicites).
        $check = $this->pdo->prepare("SELECT id FROM users WHERE email = ?");
        $check->execute([$email]);

        if ($check->rowCount() > 0) {
            return "Email already exists.";
        }

        // On ne stocke jamais le mot de passe en clair : password_hash() avec
        // l'algo par défaut (bcrypt actuellement) s'occupe du salage et du
        // hachage. Même si la base fuite un jour, les mots de passe restent protégés.
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare(
            "INSERT INTO users (username, password, email, admin)
             VALUES (?, ?, ?, ?)"
        );

        if ($stmt->execute([$username, $hashed_password, $email, $admin])) {
            return "Account created successfully.";
        }

        return "Error while inserting.";
    }


    public function login($email, $password) {

        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // password_verify() recalcule le hash côté serveur et le compare à
        // celui stocké : on ne peut jamais "déchiffrer" un mot de passe haché,
        // seulement vérifier qu'il correspond.
        if ($user && password_verify($password, $user['password'])) {

            // C'est ici, et seulement ici, que la session utilisateur démarre.
            // is_admin est la clé qu'on utilise partout ailleurs (admin.php,
            // les pages de gestion produits/catégories...) pour savoir si la
            // personne connectée a le droit d'accéder à l'administration.
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = $user['admin'];

            return $user;
        }

        return false;
    }


    
    public function getAllUsers() {
        $stmt = $this->pdo->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateUser($id,$username,$email,$admin){
        $stmt = $this->pdo->prepare(
            "UPDATE users SET username=?, email=?, admin=? WHERE id=?"
        );
        return $stmt->execute([$username,$email,$admin,$id]);
    }

    public function deleteUser($id){
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id=?");
        return $stmt->execute([$id]);
    }
}