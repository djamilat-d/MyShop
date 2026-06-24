<?php
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
    // $confirm_pass =$_POST['confirm_password'];
    $admin = isset($_POST['admin']) ? 1 : 0;
    $message = $userModel->createUser($name, $email, $pass, $admin);
    header("Location: signin.php");

    // if($pass !== $confirm_pass){
    //     $message = "impossible";
    // }else{
    //     $message = $userModel->createUser($name, $email, $pass, $admin);

    //     if($message === "Account created successfully."){
    //         header("Location: index.php");
    //         exit();
    //     }

    // }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="style.css">
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

            <div style="margin-bottom: 15px;">
                <label><input type="checkbox" name="admin" style="width: auto; margin-right: 10px;"> Admin Account</label>
            </div>

            <button type="submit">Register</button>
        </form>

        <p style="text-align: center; margin-top: 15px;">
            Already have an account? 
            <a href="signin.php">Login</a>
        </p>
    </div>

</body>
</html>
