<?php

require_once '../models/product.php';

$productModel = new Products();

$name = $_GET['name'] ?? '';
$category = $_GET['category'] ?? '';
$price = $_GET['price'] ?? '';
$sort = $_GET['sort'] ?? '';

$products = $productModel->searchProducts($name,$category,$price,$sort);

?>

<!DOCTYPE html>
<html lang="fr">
<head>

<meta charset="UTF-8">

<title>Résultats de recherche</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-4">

<h2 class="mb-4">Résultats de recherche</h2>

<a href="index.php" class="btn btn-secondary mb-3">Retour</a>

<?php if (empty($products)): ?>

<div class="alert alert-warning">
Aucun produit trouvé
</div>

<?php else: ?>

<div class="row">

<?php foreach ($products as $product): ?>

<div class="col-md-3 mb-4">

<div class="card h-100">

<?php if (!empty($product['picture'])): ?>

<img src="../images/<?= htmlspecialchars($product['picture']) ?>" 
class="card-img-top">

<?php endif; ?>

<div class="card-body">

<h5><?= htmlspecialchars($product['name']) ?></h5>

<p class="text-muted">
Catégorie : <?= htmlspecialchars($product['category_name']) ?>
</p>

<p class="text-primary fw-bold">

<?= htmlspecialchars($product['price']) ?> FCFA

</p>

</div>

</div>

</div>

<?php endforeach; ?>

</div>

<?php endif; ?>

</div>

</body>
</html>