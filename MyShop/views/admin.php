<?php
session_start();
require_once "../models/connexion.php";

// Le tableau de bord est ouvert à tout utilisateur connecté (pas seulement
// les admins) : n'importe qui voit l'intégralité de l'interface (Users,
// Products, Categories). Ce qui change selon le rôle, ce sont les actions
// possibles à l'intérieur de chaque section : un utilisateur normal peut
// ajouter des produits et ne gérer que les siens, mais ne peut pas modifier
// les utilisateurs ni les catégories (voir admin_users.php et
// add_categorie.php, qui affichent les mêmes pages à tout le monde mais
// bloquent les actions réservées aux admins avec une alerte explicative).
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

$isAdmin = !empty($_SESSION['is_admin']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>

<meta charset="UTF-8">

<title>Admin Dashboard</title>

<link rel="stylesheet" href="main.css?v=<?= @filemtime(__DIR__ . '/main.css') ?: time() ?>">

</head>

<body>

<div class="admin-layout">


<div class="sidebar">

<h2 class="sidebar-logo"><span class="styleM">M</span>y<span class="style">~</span><span class="styleS">S</span>hop</h2>

<a href="admin.php">Dashboard</a>
<a href="admin_users.php">Users</a>
<a href="admin_products.php">Products</a>
<?php // Ce lien pointait déjà vers index.php mais s'appelait "Orders" :
      // un reliquat, il n'y a pas de fonctionnalité de commandes ici.
      // On le renomme pour que ce soit un vrai lien vers la boutique. ?>
<a href="index.php">Voir la boutique</a>
<a href="add_categorie.php">Categories</a>
<a href="logout.php" class="sidebar-logout">Se déconnecter</a>

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
<?php if (!$isAdmin): ?><br><span class="owner-note">Consultation seule : réservé aux admins pour modifier/supprimer.</span><?php endif; ?>
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
<?php if (!$isAdmin): ?><br><span class="owner-note">Consultation seule : réservé aux admins pour modifier/supprimer.</span><?php endif; ?>
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