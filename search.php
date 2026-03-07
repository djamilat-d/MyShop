<?php
require_once 'connexion.php';

$name  = $_GET['name'] ?? '';
$price = $_GET['price'] ?? '';
$sort  = $_GET['sort'] ?? '';

$sql    = "SELECT * FROM products WHERE 1=1";
$params = [];

if (!empty($name)) {
    $sql .= " AND name LIKE :name";
    $params['name'] = "%$name%";
}

if (!empty($price)) {
    $sql .= " AND price <= :price";
    $params['price'] = $price;
}

switch ($sort) {
    case "name_asc":
        $sql .= " ORDER BY name ASC";
        break;
    case "name_desc":
        $sql .= " ORDER BY name DESC";
        break;
    case "price_asc":
        $sql .= " ORDER BY price ASC";
        break;
    case "price_desc":
        $sql .= " ORDER BY price DESC";
        break;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                Aucun produit trouvé.
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($products as $product): ?>
                    <div class="col-md-3 mb-4">
                        <div class="card h-100">
                            <?php if (!empty($product['image'])): ?>
                                <img src="uploads/<?= htmlspecialchars($product['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
                            <?php endif; ?>
                            
                            <div class="card-body">
                                <h5><?= htmlspecialchars($product['name']) ?></h5>
                                <p class="text-primary fw-bold"><?= htmlspecialchars($product['price']) ?> FCFA</p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
