<?php
session_start();

include_once '../models/product.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$productModel = new Products();
$produit = $productModel->getId($id);

if (!$produit) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($produit['name']) ?> - MyShop</title>
    <link rel="stylesheet" href="main.css?v=<?= @filemtime(__DIR__ . '/main.css') ?: time() ?>">
</head>
<body>

<nav class="navbar">
    <div class="logo"> <span class="styleM">M</span>y<span class="style">~</span><span class="styleS">s</span>hop
    </div>
    <div>
        <a href="index.php" class="btndcon">Retour à la boutique</a>
    </div>
</nav>

<section class="page-content" style="max-width: 900px;">
    <div style="display: flex; gap: 40px; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 280px;">
            <?php if (!empty($produit['picture'])): ?>
                <img src="image/<?= htmlspecialchars($produit['picture']) ?>" alt="<?= htmlspecialchars($produit['name']) ?>" style="width:100%; border-radius: 10px; object-fit: cover;">
            <?php else: ?>
                <div style="width:100%; height:300px; background:#eee; display:flex; align-items:center; justify-content:center; border-radius:10px;">Pas d'image</div>
            <?php endif; ?>
        </div>

        <div style="flex: 1; min-width: 280px;">
            <h1><?= htmlspecialchars($produit['name']) ?></h1>

            <?php if (!empty($produit['category_name'])): ?>
                <p style="color: gray;"><?= htmlspecialchars($produit['category_name']) ?></p>
            <?php endif; ?>

            <p class="price" style="font-size: 24px; margin: 15px 0;"><?= htmlspecialchars($produit['price']) ?> FCFA</p>

            <div class="description">
                <?= nl2br(htmlspecialchars($produit['description'])) ?>
            </div>

            <button class="achat" style="margin-top: 20px; max-width: 260px;">Acheter</button>
        </div>
    </div>
</section>

</body>
</html>
