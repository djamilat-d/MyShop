<?php
session_start();
require_once "../models/connexion.php";

if (!isset($_SESSION['user_id']) || $_SESSION['admin'] != 1) {
    header("Location: signin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="sidebar">
        <h2>MYSHOP</h2>
        <a href="admin.php">Dashboard</a>
        <a href="admin_users.php">Users</a>
        <a href="admin_products.php">Products</a>
        <a href="index.php">Orders</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="header">
        <h1>
            Admin Dashboard
            <a href="logout.php" class="logout">Logout</a>
        </h1>
        <p>Welcome <?= htmlspecialchars($_SESSION['username']) ?></p>
    </div>

    <div class="container">

        <div class="card">
            <h2>👤 Users Management</h2>
            <p>Display, edit and delete users. Grant administrator privileges.</p>
            <a href="admin_users.php">Manage Users</a>
        </div>

        <div class="card">
            <h2>📦 Products Management</h2>
            <p>Manage shop products (coming soon).</p>
            <a href="admin_products.php">Manage Products</a>
        </div>

        <div class="card">
            <h2>📂 Categories Management</h2>
            <p>Manage product categories (coming soon).</p>
            <a href="#">Manage Categories</a>
        </div>

    </div>

</body>
</html>