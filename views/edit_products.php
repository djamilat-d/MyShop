<?php
session_start();

if(!isset($_SESSION['user_id']) || 
   (!isset($_SESSION['admin']) && !isset($_SESSION['is_admin']))){
    header('Location: signin.php');
    exit();
}
include_once '../models/product.php';


$productModel= new Products();

$id = $_GET['id'];
$produit = $productModel->getId($id);

if (!$produit) {
    header("Location: admin_products.php");
    exit();
}

$message_erreur = "";

if(isset($_POST['submit'])){
    $name=$_POST['name'];
    $description=$_POST['description'];
    $price=$_POST['price'];
    $picture_name=$produit['picture'];
    if(empty($name) || empty($description) || empty($price)){
        $message_erreur = "Tous les champs sont obligatoires.";
    }else{
        if($_FILES['picture']['error'] ==0){
            $picture_name = time() . '_' . $_FILES['picture']['name'];
            move_uploaded_file($_FILES['picture']['tmp_name'], '../images/' . $picture_name);
        }
        $productModel->update($id,$name,$description,$price,$picture_name);
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
    <title>Modifier un produit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4" style="max-width: 550px;">
    <h2>Modifier le produit</h2>
    <a href="admin_products.php" class="btn btn-secondary mb-3">Retour</a>
    <?php if ($message_erreur != '') { ?>
        <div class="alert alert-danger"><?php echo $message_erreur; ?></div>
    <?php } ?>

    <form action="" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label>Nom du produit</label>
        <input class="form-control" type="text" name="name" value="<?php echo $produit['name']; ?>" required>
    </div>

    <div class="mb-3">
        <label>Description</label>
        <textarea class="form-control" name="description" id="" required><?php echo $produit['description']; ?></textarea>
    </div>

    <div class="mb-3">
        <label>Prix (Frcfa)</label>
        <input class="form-control" type="number" name="price" value="<?php echo $produit['price']; ?>" required>
    </div>

    <div class="mb-3">
        <label>Image actuelle du produit</label>
        <?php if (!empty($produit['picture'])) { ?>
                <img src="../images/<?php echo $produit['picture']; ?>" class="rounded" style="width:auto; height:80px; object-fit:cover; border-radius:5px;"><br>
            <?php } else { ?>
                Pas d'image<br>
            <?php } ?>
        <label class="form-label mt-2">Changer l'image</label>
        <input class="form-control" type="file" name="picture">
    </div>

    <button class="btn btn-success" type="submit" name="submit">Enregistrer</button>
</form>
</div>

</body>
</html>