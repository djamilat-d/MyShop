<?php
session_start();
require '../models/User.php';

$userModel = new User($pdo);

$message = "";
$email   = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $pass  = $_POST['password'];

    if (empty($email) || empty($pass)) {
        $message = "Veuillez obligatoirement remplir ces champs.";
    } else {
        // Toute la vérification (email existe, mot de passe correct, mise à
        // jour de la session) est déléguée à User::login(). On évite de
        // dupliquer cette logique ici : avant, ce fichier refaisait la même
        // requête SQL à la main, avec une clé de session différente
        // ($_SESSION['admin'] au lieu de $_SESSION['is_admin']), ce qui
        // cassait la vérification admin sur d'autres pages.
        $user = $userModel->login($email, $pass);

        if ($user) {
            // On distingue les deux profils dès la connexion : un admin va
            // direct sur son tableau de bord, un client va sur la boutique.
            // Pas de raison de faire cliquer un admin deux fois pour y arriver.
            if ($user['admin'] == 1) {
                header("Location: admin.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            // On reste volontairement vague ("Invalid email or password")
            // plutôt que de dire "cet email n'existe pas" : ça évite de
            // confirmer à un attaquant quels emails sont enregistrés.
            $message = "Invalid email or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="main.css?v=<?= @filemtime(__DIR__ . '/main.css') ?: time() ?>">
</head>
<body class="auth-page">

    <div class="form-container">

        <?php if ($message): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST">
            <h2>Login</h2>

            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit">Login</button>
        </form>

        <p>
            No account? 
            <a href="signup.php">Register</a>
        </p>

    </div>

</body>
</html>