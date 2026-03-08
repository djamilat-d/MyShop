<?php
require_once "../models/connexion.php";
require_once "../models/categorie.php";

$category = new Category();

if (isset($_POST['name']) && !empty($_POST['name'])) {

    $name = $_POST['name'];
    $parent = !empty($_POST['parent_id']) ? $_POST['parent_id'] : null;

    $category->add($name, $parent);

    header("Location: add_categorie.php");
    exit();
}

$categories = $category->getAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Ajouter catégorie</title>

<link rel="stylesheet" href="../style.css">

</head>

<body>

<div class="admin-layout">

<!-- SIDEBAR -->

<div class="sidebar">

<h2>MYSHOP</h2>

<a href="admin.php">Dashboard</a>
<a href="admin_users.php">Users</a>
<a href="admin_products.php">Products</a>
<a href="add_categorie.php" class="active">Categories</a>
<a href="logout.php">Logout</a>

</div>


<!-- MAIN -->

<div class="main-content">

<div class="header">

<h1>Categories</h1>

</div>


<div class="container">

<h2>Ajouter une catégorie</h2>

<form method="POST">

<input type="text"
name="name"
placeholder="Nom de la catégorie"
required>


<select name="parent_id">

<option value="">Catégorie principale</option>

<?php foreach($categories as $cat): ?>

<option value="<?= $cat->id ?>">
<?= $cat->name ?>
</option>

<?php endforeach; ?>

</select>


<button type="submit">Ajouter</button>

</form>


<h3>Liste des catégories</h3>

<table>

<tr>
<th>Nom catégorie</th>
<th>ID parent</th>
</tr>

<?php foreach($categories as $cate): ?>

<tr>

<td><?= $cate->name ?></td>
<td><?= $cate->parent_id ?></td>

</tr>

<?php endforeach; ?>

</table>

</div>

</div>

</div>

</body>
</html>