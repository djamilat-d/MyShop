<?php
session_start();
require_once "../models/connexion.php";

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: signin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>

<meta charset="UTF-8">

<title>Admin Dashboard</title>

<link rel="stylesheet" href="../style.css">

</head>

<body>

<div class="admin-layout">


<div class="sidebar">

<h2>MYSHOP</h2>

<a href="admin.php">Dashboard</a>
<a href="admin_users.php">Users</a>
<a href="admin_products.php">Products</a>
<a href="index.php">Orders</a>
<a href="add_categorie.php">Categories</a>
<a href="logout.php">Logout</a>

</div>



<div class="main-content">

<div class="header">

<h1>Admin Dashboard</h1>

<p>
Welcome <?= htmlspecialchars($_SESSION['username']) ?>
</p>

</div>


<div class="container">

<div class="card">

<h2>👤 Users Management</h2>

<p>
Display, edit and delete users. Grant administrator privileges.
</p>

<a href="admin_users.php">
Manage Users
</a>

</div>


<div class="card">

<h2>📦 Products Management</h2>

<p>
Manage shop products.
</p>

<a href="admin_products.php">
Manage Products
</a>

</div>


<div class="card">

<h2>📂 Categories Management</h2>

<p>
Manage product categories.
</p>

<a href="add_categorie.php">
Manage Categories
</a>

</div>

</div>

</div>

</div>

</body>
</html>