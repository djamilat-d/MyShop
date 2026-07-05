<?php
session_start();

// Cette page est ouverte à tout utilisateur connecté, admin ou non : voir
// la liste et ajouter un produit ne nécessite plus d'être admin. Seules la
// modification et la suppression restent limitées (voir plus bas, où on
// n'affiche ces boutons que sur les produits qu'on a soi-même créés,
// sauf pour un admin qui garde tous les droits).
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

$isAdmin = !empty($_SESSION['is_admin']);

include_once '../models/product.php';

$productModel = new Products();
$produits = $productModel->getAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Produits - MyShop</title>
    <link rel="stylesheet" href="main.css?v=<?= @filemtime(__DIR__ . '/main.css') ?: time() ?>">
</head>
<body>

<div class="admin-layout">

    <div class="sidebar">
        <h2 class="sidebar-logo"><span class="styleM">M</span>y<span class="style">~</span><span class="styleS">S</span>hop</h2>
        <a href="admin.php">Dashboard</a>
        <a href="admin_users.php">Users</a>
        <a href="admin_products.php" class="active">Products</a>
        <a href="index.php">Voir la boutique</a>
        <a href="add_categorie.php">Categories</a>
        <a href="logout.php" class="sidebar-logout">Se déconnecter</a>
    </div>

    <div class="main-content">
        
        <div class="header">
            <h1>Products Management</h1>
            <p>Welcome <?= htmlspecialchars($_SESSION['username']) ?></p>
        </div>

        <div class="container" style="display: block;">

            <div class="page-toolbar">
                <h2>Liste des Produits</h2>
                <a href="ajouter_products.php" class="btn-add">+ Ajouter un produit</a>
            </div>

            <?php if(isset($_GET['success'])): ?>
                <p class="alert-success">Action effectuée avec succès</p>
            <?php endif; ?>

            <?php if(isset($_GET['denied'])): ?>
                <p class="alert-info">Action refusée : tu ne peux modifier ou supprimer que les produits que tu as toi-même ajoutés (sauf si tu es administrateur).</p>
            <?php endif; ?>

            <?php if(empty($produits)): ?>
                <p class="empty-state">Aucun produit trouvé dans la base de données.</p>
            <?php else: ?>
                <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Nom</th>
                            <th>Catégorie</th>
                            <th>Prix</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($produits as $produit): ?>
                            <tr>
                                <td><?= $produit['id']; ?></td>
                                <td>
                                    <?php if(!empty($produit['picture'])): ?>
                                        <img src="image/<?= $produit['picture'];?>" alt="<?= htmlspecialchars($produit['name']); ?>" class="thumb">
                                    <?php else: ?>
                                        <span class="no-thumb">Pas d'image</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($produit['name']); ?></td>
                                <td>
                                    <?php if ($produit['category_name']): ?>
                                        <span class="badge-category"><?= htmlspecialchars($produit['category_name']); ?></span>
                                    <?php else: ?>
                                        <span class="badge-none">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="price-cell"><?= number_format($produit['price'], 0, ',', ' '); ?> FCFA</td>
                                <td>
                                    <?php
                                        // Modifier/Supprimer reste réservé à l'auteur du produit
                                        // (ou à un admin, qui garde tous les droits). Mais plutôt
                                        // que de simplement cacher les boutons pour les autres, on
                                        // les affiche quand même et on bloque le clic avec une
                                        // alerte explicative : sur un site de démonstration, ça
                                        // permet à quelqu'un qui explore le site de comprendre
                                        // le système de permissions au lieu de juste se demander
                                        // pourquoi les boutons ont disparu.
                                        $isOwner = $produit['created_by'] == $_SESSION['user_id'];
                                        $canManage = $isAdmin || $isOwner;
                                    ?>
                                    <div class="actions-cell">
                                    <?php if ($canManage): ?>
                                        <a href="edit_products.php?id=<?= $produit['id']; ?>" class="btn btn-edit">Modifier</a>
                                        <a href="../controllers/delete_products.php?id=<?= $produit['id'];?>"
                                           class="btn btn-delete"
                                           onclick="return confirm('Voulez-vous vraiment supprimer ce produit ?')">
                                           Supprimer
                                        </a>
                                    <?php else: ?>
                                        <a href="#" class="btn btn-edit btn-locked"
                                           onclick="alert('Tu ne peux pas modifier ce produit : il n\'a pas été ajouté par toi et tu n\'es pas administrateur.'); return false;">
                                           Modifier
                                        </a>
                                        <a href="#" class="btn btn-delete btn-locked"
                                           onclick="alert('Tu ne peux pas supprimer ce produit : il n\'a pas été ajouté par toi et tu n\'es pas administrateur.'); return false;">
                                           Supprimer
                                        </a>
                                    <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                </div>
            <?php endif; ?>

        </div> <!-- Fin container -->
    </div> <!-- Fin main-content -->
</div> <!-- Fin admin-layout -->

</body>
</html>
