<?php
session_start();

if(!isset($_SESSION['user_id']) || 
   (!isset($_SESSION['admin']) && !isset($_SESSION['is_admin']))){
    header('Location: signin.php');
    exit();
}
include_once '../models/product.php';

$message_erreur = '';

if(isset($_POST['submit'])){
    $name=$_POST['name'];
    $description=$_POST['description'];
    $price=$_POST['price'];
    $picture_name='';

    if(empty($name) || empty($description) || empty($price)){
        $message_erreur = "Tous les champs sont obligatoires.";
    }elseif($price <=0){
        $message_erreur="le prix doit etre superieur a 0";
    }else{
        if($_FILES['picture']['error']==0){
    
            $picture_name = time() . '_' . $_FILES['picture']['name'];
            move_uploaded_file($_FILES['picture']['tmp_name'], '../images/' . $picture_name);
        }
        $productModel= new Products();
        $productModel->create($name,$description,$price,$picture_name);
        header("Location: admin_products.php?success=1");
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Produit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">


    <h2>Ajouter un Produit</h2>
    <a href="admin_products.php" class="btn btn-secondary mb-3">Retour</a>

    <?php if ($message_erreur != '') { ?>
        <div class="alert alert-danger"><?php echo $message_erreur; ?></div>
    <?php } ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Nom du produit</label>
            <input class="form-control" type="text" name="name" required>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <!-- <input type="text" name="description" required> -->
            <textarea class="form-control" name="description" id="" required></textarea>
        </div>

        <div class="mb-3">
            <label>Prix (Frcfa)</label>
            <input class="form-control" type="number" name="price" required>
        </div>

        <div class="mb-3">
            <label>Image du produit</label>
            <input class="form-control" type="file" name="picture">
        </div>

        <button class="btn btn-primary" type="submit" name="submit">Enregistrer</button>
    </form>
</div>
</body>
</html>