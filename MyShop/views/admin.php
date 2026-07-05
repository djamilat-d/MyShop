<?php
session_start();
require_once "../models/connexion.php";

// Le tableau de bord est maintenant ouvert à tout utilisateur connecté (pas
// seulement les admins) : n'importe qui peut ajouter des produits ou des
// catégories. Seule la gestion des utilisateurs reste réservée aux vrais
// admins (voir admin_users.php, qui garde sa propre vérification stricte).
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

<link rel="stylesheet" href="../style.css">

</head>

<body>

<div class="admin-layout">


<div class="sidebar">

<h2 class="sidebar-logo"><span class="styleM">M</span>y<span class="style">~</span><span class="styleS">S</span>hop</h2>

<a href="admin.php">Dashboard</a>
<?php if ($isAdmin): ?>
<a href="admin_users.php">Users</a>
<?php endif; ?>
<a href="admin_products.php">Products</a>
<?php // Ce lien pointait déjà vers index.php mais s'appelait "Orders" :
      // un reliquat, il n'y a pas de fonctionnalité de commandes ici.
      // On le renomme pour que ce soit un vrai lien vers la boutique. ?>
<a href="index.php">Voir la boutique</a>
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

<?php if ($isAdmin): ?>
<div class="card">

<h2>👤 Users Management</h2>

<p>
Display, edit and delete users. Grant administrator privileges.
</p>

<a href="admin_users.php">
Manage Users
</a>

</div>
<?php endif; ?>


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