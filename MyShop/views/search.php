<?php

require_once '../models/product.php';
require_once '../models/categorie.php';

$productModel = new Products();
$categoryModel = new Category();
$categoriesForSearch = $categoryModel->getAll();

$name = $_GET['name'] ?? '';
$category = $_GET['category'] ?? '';
$price = $_GET['price'] ?? '';
$sort = $_GET['sort'] ?? '';

$products = $productModel->searchProducts($name, $category, $price, $sort);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats de recherche - MyShop</title>
    <!-- On réutilise la feuille de style de la boutique pour que cette page
         ait le même look que le reste du site, au lieu de dépendre de
         Bootstrap (chargé depuis un CDN) qui ne s'affichait pas correctement. -->
    <link rel="stylesheet" href="styless.css?v=<?= @filemtime(__DIR__ . '/styless.css') ?: time() ?>">
</head>
<body>

    <nav class="navbar">
        <div class="logo"> <span class="styleM">M</span>y<span class="style">~</span><span class="styleS">s</span>hop
        </div>
        <div>
            <a href="index.php" class="btndcon">Retour à la boutique</a>
        </div>
    </nav>

    <section class="page-content">

        <h2 class="page-title">Résultats de recherche</h2>

        <!-- On garde le formulaire de recherche accessible ici, pré-rempli
             avec ce que l'utilisateur vient de chercher, pour lui éviter
             de devoir revenir en arrière pour affiner sa recherche. -->
        <div class="search-container" style="margin: 0 0 40px;">
            <form action="search.php" method="GET" class="search-bar">
                <input type="text" name="name" placeholder="Nom du produit" value="<?= htmlspecialchars($name) ?>">
                <select name="category">
                    <option value="">Toutes les catégories</option>
                    <?php foreach($categoriesForSearch as $cat): ?>
                        <option value="<?= $cat->id ?>" <?= ((string)$category === (string)$cat->id) ? 'selected' : '' ?>><?= htmlspecialchars($cat->name) ?></option>
                    <?php endforeach; ?>
                    <option value="0" <?= $category === '0' ? 'selected' : '' ?>>Autre</option>
                </select>
                <input type="number" name="price" placeholder="Prix max" value="<?= htmlspecialchars($price) ?>">
                <select name="sort">
                    <option value="">Trier</option>
                    <option value="name_asc" <?= $sort === 'name_asc' ? 'selected' : '' ?>>A → Z</option>
                    <option value="name_desc" <?= $sort === 'name_desc' ? 'selected' : '' ?>>Z → A</option>
                    <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Prix ↑</option>
                    <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Prix ↓</option>
                </select>
                <button type="submit">Rechercher</button>
            </form>
        </div>

        <?php if (empty($products)): ?>

            <p style="text-align:center; color: gray; font-size: 18px;">Aucun produit ne correspond à ta recherche.</p>

        <?php else: ?>

            <div class="grid">

                <?php foreach ($products as $product): ?>

                    <div class="card">
                        <a href="product_detail.php?id=<?= $product['id'] ?>" class="Coombes" style="display:block; text-decoration:none; color:inherit;">

                            <?php if (!empty($product['picture'])): ?>
                                <img src="image/<?= htmlspecialchars($product['picture']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="img">
                            <?php endif; ?>

                            <div class="info">
                                <h2><?= htmlspecialchars($product['name']) ?></h2>
                                <span class="price"><?= htmlspecialchars($product['price']) ?> FCFA</span>
                            </div>

                            <h3><?= $product['category_name'] ? htmlspecialchars($product['category_name']) : 'Non classé' ?></h3>
                        </a>
                    </div>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </section>

</body>
</html>
