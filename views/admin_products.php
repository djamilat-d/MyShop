<?php
session_start();

// Vérification admin cohérente
if (!isset($_SESSION['user_id']) || $_SESSION['admin'] != 1) {
    header("Location: signin.php");
    exit();
}

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
    <!-- Utilisation de ton fichier CSS pour la sidebar -->
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="admin-layout">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h2>MYSHOP</h2>
        <a href="admin.php">Dashboard</a>
        <a href="admin_users.php">Users</a>
        <a href="admin_products.php" class="active">Products</a>
        <a href="add_categorie.php">Categories</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        
        <div class="header">
            <h1>Products Management</h1>
            <p>Welcome <?= htmlspecialchars($_SESSION['username']) ?></p>
        </div>

        <div class="container">
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2>Liste des Produits</h2>
                <a href="ajouter_products.php" class="btn-edit" style="text-decoration: none; background-color: #28a745;">+ Ajouter un produit</a>
            </div>

            <?php if(isset($_GET['success'])): ?>
                <p class="alert" style="background: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 10px;">
                    Action effectuée avec succès
                </p>
            <?php endif; ?>

            <?php if(empty($produits)): ?>
                <p>Aucun produit trouvé dans la base de données.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Nom</th>
                            <th>Prix</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($produits as $produit): ?>
                            <tr>
                                <td><?= $produit['id']; ?></td>
                                <td>
                                    <?php if(!empty($produit['image'])): ?>
                                        <img src="../images/<?= $produit['image'];?>" alt="<?= htmlspecialchars($produit['name']); ?>" style="width:60px; height:60px; object-fit:cover; border-radius:5px;">
                                    <?php else: ?>
                                        <span style="color: #999; font-size: 0.8em;">Pas d'image</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($produit['name']); ?></td>
                                <td><?= number_format($produit['price'], 0, ',', ' '); ?> FCFA</td>
                                <td>
                                    <a href="edit_products.php?id=<?= $produit['id']; ?>" class="btn-edit">Modifier</a>
                                    <a href="../controllers/delete_products.php?id=<?= $produit['id'];?>" 
                                       class="btn-delete" 
                                       onclick="return confirm('Voulez-vous vraiment supprimer ce produit ?')">
                                       Supprimer
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

        </div> <!-- Fin container -->
    </div> <!-- Fin main-content -->
</div> <!-- Fin admin-layout -->

</body>
</html>
