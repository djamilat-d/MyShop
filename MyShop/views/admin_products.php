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
        <a href="admin_products.php" class="active">Products</a>
        <a href="add_categorie.php">Categories</a>
        <a href="logout.php">Logout</a>
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
                                        // On ne montre Modifier/Supprimer que si c'est notre
                                        // produit, ou qu'on est admin (qui garde tous les droits).
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
                                        <span class="owner-note">Pas le tien</span>
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
