<?php
session_start();

// Ajouter un produit est ouvert à tout utilisateur connecté, plus
// seulement aux admins. On garde uniquement la vérification de connexion.
if(!isset($_SESSION['user_id'])){
    header('Location: signin.php');
    exit();
}

$isAdmin = !empty($_SESSION['is_admin']);

include_once '../models/product.php';
include_once '../models/categorie.php';

$categoryModel = new Category();
$categories = $categoryModel->getAll();

$message_erreur = '';

if(isset($_POST['submit'])){
    $name=$_POST['name'];
    $description=$_POST['description'];
    $price=$_POST['price'];
    $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : 0;
    $picture_name='';

    if(empty($name) || empty($description) || empty($price)){
        $message_erreur = "Tous les champs sont obligatoires.";
    }elseif($price <=0){
        $message_erreur="le prix doit etre superieur a 0";
    }else{
        // L'upload d'image est optionnel : un produit peut être créé sans
        // photo (picture_name reste une chaîne vide dans ce cas). On préfixe
        // le nom du fichier par un timestamp pour ne jamais écraser une
        // image existante si deux personnes uploadent un fichier du même nom.
        if($_FILES['picture']['error']==0){

            $picture_name = time() . '_' . $_FILES['picture']['name'];
            move_uploaded_file($_FILES['picture']['tmp_name'], 'image/' . $picture_name);
        }
        $productModel= new Products();
        // On enregistre qui a créé ce produit : c'est ce qui permettra plus
        // tard de limiter la modification/suppression à cette personne.
        $productModel->create($name,$description,$price,$picture_name,$category_id,$_SESSION['user_id']);
        header("Location: admin_products.php?success=1");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Produit - MyShop</title>
    <!-- On utilise notre propre main.css, pas Bootstrap en CDN : c'est ce
         qui rendait la page de recherche blanche quand le CDN ne chargeait
         pas, on ne veut pas reproduire ce problème ici. -->
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
            <h1>Ajouter un produit</h1>
            <p>Welcome <?= htmlspecialchars($_SESSION['username']) ?></p>
        </div>

        <div class="container" style="display: block;">

            <div class="page-toolbar">
                <a href="admin_products.php" class="btn-cancel">&larr; Retour à la liste</a>
            </div>

            <?php if ($message_erreur != ''): ?>
                <p class="alert-info"><?= htmlspecialchars($message_erreur) ?></p>
            <?php endif; ?>

            <div class="form-box" style="max-width: 550px;">
                <form method="POST" enctype="multipart/form-data">

                    <label>Nom du produit</label>
                    <input type="text" name="name" required>

                    <label>Description</label>
                    <textarea name="description" rows="4" required></textarea>

                    <label>Prix (FCFA)</label>
                    <input type="number" name="price" min="1" required>

                    <label>Catégorie</label>
                    <select name="category_id">
                        <option value="">Aucune catégorie</option>
                        <?php foreach($categories as $cat): ?>
                            <option value="<?= $cat->id ?>"><?= htmlspecialchars($cat->name) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <label>Image du produit</label>
                    <input type="file" name="picture">

                    <button type="submit" name="submit">Enregistrer</button>
                </form>
            </div>

        </div>
    </div>
</div>

</body>
</html>
