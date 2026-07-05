<?php
session_start();
require '../models/connexion.php';
require '../models/User.php';

$userModel = new User($pdo);

$message = "";
$name = "";
$email = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $pass = $_POST['password'];
    $confirm_pass = $_POST['confirm_password'];

    // On vérifie les champs un par un, du plus général au plus précis, pour
    // pouvoir donner un message d'erreur qui dit vraiment ce qui cloche
    // plutôt qu'un "formulaire invalide" qui ne sert à rien à l'utilisateur.
    if (empty($name) || empty($email) || empty($pass) || empty($confirm_pass)) {
        $message = "Tous les champs sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "L'adresse email n'est pas valide.";
    } elseif (strlen($pass) < 6) {
        $message = "Le mot de passe doit contenir au moins 6 caractères.";
    } elseif ($pass !== $confirm_pass) {
        $message = "Les mots de passe ne correspondent pas.";
    } else {
        // Le statut admin est toujours forcé à 0 ici : on avait au départ une
        // case à cocher "Admin Account" sur ce formulaire public, ce qui
        // permettait à n'importe qui de se créer un compte administrateur.
        // On l'a retirée : seul un admin déjà connecté peut promouvoir
        // quelqu'un depuis admin_users.php.
        $result = $userModel->createUser($name, $email, $pass, 0);

        if ($result === "Account created successfully.") {
            // On ne redirige QUE si l'inscription a réussi. Avant, le
            // header("Location") partait dans tous les cas, même en cas
            // d'erreur ("email déjà utilisé" par exemple) — l'utilisateur
            // était renvoyé sur signin.php sans jamais voir le message
            // expliquant pourquoi ça n'avait pas marché.
            header("Location: signin.php");
            exit();
        } else {
            $message = $result;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body class="auth-page">

    <div class="form-container">

        <?php if ($message): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST">
            <h2>Sign Up</h2>

            <label>Username</label>
            <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" required>

            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <label>Confirm Password</label>
            <input type="password" name="confirm_password" required>

            <button type="submit">Register</button>
        </form>

        <p style="text-align: center; margin-top: 15px;">
            Already have an account? 
            <a href="signin.php">Login</a>
        </p>
    </div>

</body>
</html>
