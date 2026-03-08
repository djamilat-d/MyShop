<?php
include_once 'connexion.php';
class User {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    
    public function createUser($username, $email, $password, $admin = 0) {

        $check = $this->pdo->prepare("SELECT id FROM users WHERE email = ?");
        $check->execute([$email]);

        if ($check->rowCount() > 0) {
            return "Email already exists.";
        }

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

        if ($user && password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['admin'] = $user['admin'];

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