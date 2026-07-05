<?php
session_start();

// Modifier un produit est ouvert à tout utilisateur connecté, mais on
// vérifie plus bas qu'il s'agit bien de SON produit (ou qu'on est admin).
if(!isset($_SESSION['user_id'])){
    header('Location: signin.php');
    exit();
}
include_once '../models/product.php';
include_once '../models/categorie.php';


$productModel= new Products();
$categoryModel = new Category();
$categories = $categoryModel->getAll();

$id = $_GET['id'];
$produit = $productModel->getId($id);

// Si l'id dans l'URL ne correspond à aucun produit (supprimé entre-temps,
// ou lien bidouillé), on renvoie simplement vers la liste plutôt que de
// laisser le reste de la page planter en essayant d'afficher un produit qui n'existe pas.
if (!$produit) {
    header("Location: admin_products.php");
    exit();
}

// On ne laisse modifier ce produit que son créateur, ou un admin. Un
// utilisateur normal qui essaierait de bidouiller l'URL pour éditer le
// produit de quelqu'un d'autre est renvoyé vers la liste.
$isAdmin = !empty($_SESSION['is_admin']);
$isOwner = $produit['created_by'] == $_SESSION['user_id'];
if (!$isAdmin && !$isOwner) {
    header("Location: admin_products.php");
    exit();
}

$message_erreur = "";

if(isset($_POST['submit'])){
    $name=$_POST['name'];
    $description=$_POST['description'];
    $price=$_POST['price'];
    $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : 0;
    // Par défaut on garde l'image déjà en base : si l'admin ne réuploade
    // rien, il ne faut surtout pas écraser picture par une valeur vide.
    $picture_name=$produit['picture'];
    if(empty($name) || empty($description) || empty($price)){
        $message_erreur = "Tous les champs sont obligatoires.";
    }else{
        if($_FILES['picture']['error'] ==0){
            $picture_name = time() . '_' . $_FILES['picture']['name'];
            move_uploaded_file($_FILES['picture']['tmp_name'], 'image/' . $picture_name);
        }
        $productModel->update($id,$name,$description,$price,$picture_name,$category_id);
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
    <title>Modifier un produit - MyShop</title>
    <!-- Même feuille de style que le reste de l'admin, plutôt que Bootstrap
         en CDN (voir le commentaire dans ajouter_products.php). -->
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
            <h1>Modifier le produit</h1>
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
                    <input type="text" name="name" value="<?= htmlspecialchars($produit['name']) ?>" required>

                    <label>Description</label>
                    <textarea name="description" rows="4" required><?= htmlspecialchars($produit['description']) ?></textarea>

                    <label>Prix (FCFA)</label>
                    <input type="number" name="price" min="1" value="<?= htmlspecialchars($produit['price']) ?>" required>

                    <label>Catégorie</label>
                    <select name="category_id">
                        <option value="">Aucune catégorie</option>
                        <?php foreach($categories as $cat): ?>
                            <option value="<?= $cat->id ?>" <?= ($produit['category_id'] == $cat->id) ? "selected" : "" ?>><?= htmlspecialchars($cat->name) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <label>Image actuelle</label>
                    <?php if (!empty($produit['picture'])): ?>
                        <div style="margin-bottom: 12px;">
                            <img src="image/<?= htmlspecialchars($produit['picture']) ?>" class="thumb" style="width:80px; height:80px;">
                        </div>
                    <?php else: ?>
                        <p class="no-thumb" style="margin-bottom: 12px;">Pas d'image</p>
                    <?php endif; ?>

                    <label>Changer l'image</label>
                    <input type="file" name="picture">

                    <button type="submit" name="submit">Enregistrer</button>
                </form>
            </div>

        </div>
    </div>
</div>

</body>
</html>