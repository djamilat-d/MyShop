<?php
session_start();

if(!isset($_SESSION['user_id']) || 
   (!isset($_SESSION['admin']) && !isset($_SESSION['is_admin']))){
    header('Location: signin.php');
    exit();
}

include_once '../models/product.php';

$productModel = new Products();
$produits = $productModel->getAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listes des Produits</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <h2>Listes des Produits</h2>

     <a href="ajouter_products.php" class="btn btn-success mb-3">+ Ajouter un produits</a>
     <a href="admin.php" class="btn btn-secondary mb-3">Retour</a>

     <?php if(isset($_GET['success'])){ ?>
    <div class="alert alert-success">Action effectuée avec succès</div>
    <?php } ?>

    <?php if(empty($produits)){ 
        echo "Aucun produit";
    }else{ ?>
    <table class="">
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Nom</th>
            <th>Prix</th>
            <th>Actions</th>
       </tr>
        <?php foreach($produits as $produit){?>
            <tr>
                <td><?php echo $produit['id']; ?></td>

                <td><?php if(!empty($produit['image'])){ ?>
                    <img src="../images/<?php echo $produit['image'];?>" alt="" class="rounded" style="width:auto; height:80px; object-fit:cover; border-radius:5px;">
                    <?php }else{?> pas d'image <?php } ?>
                </td>
                <td><?php echo $produit['name']; ?></td>
                <td><?php echo $produit['price']; ?> Frcfa</td>
                <td>
                    <a href="edit_products.php?id=<?php echo $produit['id']; ?>" class="btn btn-warning btn-sm">Modifier</a>
                    <a href="../controllers/delete_products.php?id=<?php echo $produit['id'];?>" class="btn btn-danger btn-sm" onclick="return confirm('voulez-vous vraiment supprimer ce produit?')">supprimer</a>
                </td>
            </tr>
            <?php } ?>
        
    </table>
    <?php } ?>
</div>
    
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>