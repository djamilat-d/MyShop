<?php
session_start();

// Cette page n'avait au départ aucune vérification : n'importe qui, même
// pas connecté, pouvait ajouter des catégories juste en connaissant l'URL.
// Maintenant : tout utilisateur connecté peut VOIR cette page (formulaire et
// liste), pour avoir une vue complète du tableau de bord, admin ou pas. Mais
// créer/modifier/supprimer une catégorie reste réservé aux administrateurs :
// chaque action ci-dessous revérifie $isAdmin côté serveur avant d'écrire
// quoi que ce soit (sécurité réelle), et le formulaire/les boutons sont en
// plus bloqués côté interface avec une alerte explicative si on n'est pas
// admin, plutôt que d'être simplement cachés.
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

$isAdmin = !empty($_SESSION['is_admin']);

require_once "../models/connexion.php";
require_once "../models/categorie.php";

$category = new Category();

$message_erreur = "";
$message_succes = "";

// Supprimer une catégorie. On refuse si elle a encore des sous-catégories
// ou des produits rattachés : les supprimer en cascade sans prévenir
// pourrait faire disparaître des produits par surprise, donc on préfère
// bloquer et demander à l'utilisateur de d'abord les déplacer ailleurs.
if (isset($_GET['delete'])) {

    if (!$isAdmin) {
        $message_erreur = "Action réservée aux administrateurs : tu ne peux pas supprimer de catégorie.";
    } else {

        $deleteId = (int) $_GET['delete'];

        if ($category->countChildren($deleteId) > 0) {
            $message_erreur = "Impossible de supprimer : cette catégorie a des sous-catégories. Déplace-les ou supprime-les d'abord.";
        } elseif ($category->countProducts($deleteId) > 0) {
            $message_erreur = "Impossible de supprimer : des produits sont encore rattachés à cette catégorie.";
        } else {
            $category->delete($deleteId);
            header("Location: add_categorie.php?deleted=1");
            exit();
        }
    }
}

if (isset($_GET['deleted'])) {
    $message_succes = "Catégorie supprimée.";
}

// Modifier une catégorie existante (formulaire pré-rempli via ?edit=id).
if (isset($_POST['update_category'])) {

    if (!$isAdmin) {
        $message_erreur = "Action réservée aux administrateurs : tu ne peux pas modifier de catégorie.";
    } else {

        $id = (int) $_POST['id'];
        $name = $_POST['name'];
        $parent = !empty($_POST['parent_id']) ? (int) $_POST['parent_id'] : null;

        // Une catégorie ne peut pas être son propre parent : ça créerait une
        // boucle infinie le jour où on affiche/parcourt l'arborescence.
        if ($parent == $id) {
            $message_erreur = "Une catégorie ne peut pas être sa propre catégorie parente.";
        } elseif (empty($name)) {
            $message_erreur = "Le nom de la catégorie est obligatoire.";
        } else {
            $category->update($id, $name, $parent);
            header("Location: add_categorie.php");
            exit();
        }
    }
}

// Créer une nouvelle catégorie.
if (isset($_POST['name']) && !isset($_POST['update_category']) && !empty($_POST['name'])) {

    if (!$isAdmin) {
        $message_erreur = "Action réservée aux administrateurs : tu ne peux pas ajouter de catégorie.";
    } else {

        $name = $_POST['name'];
        // Une catégorie sans parent choisi dans le formulaire devient une
        // catégorie "racine" (parent_id = null), ce qui permet l'arborescence
        // à profondeur illimitée demandée dans le sujet.
        $parent = !empty($_POST['parent_id']) ? $_POST['parent_id'] : null;

        $category->add($name, $parent);

        header("Location: add_categorie.php");
        exit();
    }
}

$categories = $category->getAll();

// Petit index id -> nom, pour afficher le nom de la catégorie parente dans
// le tableau plutôt qu'un simple numéro qui ne veut rien dire pour un humain.
$categoryNames = [];
foreach ($categories as $cat) {
    $categoryNames[$cat->id] = $cat->name;
}

// Si on vient de cliquer sur "Modifier", on charge la catégorie concernée
// pour pré-remplir le formulaire au-dessus du tableau.
$editCategory = null;
if (isset($_GET['edit'])) {
    $editCategory = $category->getById((int) $_GET['edit']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Ajouter catégorie</title>

<link rel="stylesheet" href="../style.css">

</head>

<body>

<div class="admin-layout">


<div class="sidebar">

<h2 class="sidebar-logo"><span class="styleM">M</span>y<span class="style">~</span><span class="styleS">S</span>hop</h2>

<a href="admin.php">Dashboard</a>
<a href="admin_users.php">Users</a>
<a href="admin_products.php">Products</a>
<a href="index.php">Voir la boutique</a>
<a href="add_categorie.php" class="active">Categories</a>
<a href="logout.php" class="sidebar-logout">Se déconnecter</a>

</div>



<div class="main-content">

<div class="header">

<h1>Categories</h1>

</div>


<div class="container" style="display: block;">

<div class="page-toolbar">
<h2><?= $editCategory ? "Modifier la catégorie" : "Ajouter une catégorie" ?></h2>
</div>

<?php if ($message_erreur): ?>
<p class="alert-info"><?= htmlspecialchars($message_erreur) ?></p>
<?php endif; ?>

<?php if ($message_succes): ?>
<p class="alert-success"><?= htmlspecialchars($message_succes) ?></p>
<?php endif; ?>

<?php if (!$isAdmin): ?>
<p class="owner-note" style="margin-bottom: 10px;">Tu peux consulter les catégories, mais seul un administrateur peut en ajouter, modifier ou supprimer.</p>
<?php endif; ?>

<div class="form-box">
<form method="POST"
<?php if (!$isAdmin): ?> onsubmit="alert('Action réservée aux administrateurs : tu ne peux pas ajouter ou modifier de catégorie.'); return false;"<?php endif; ?>>


<?php if ($editCategory): ?>
<input type="hidden" name="id" value="<?= $editCategory->id ?>">
<?php endif; ?>

<input type="text"
name="name"
placeholder="Nom de la catégorie"
value="<?= $editCategory ? htmlspecialchars($editCategory->name) : '' ?>"
required>


<select name="parent_id">

<option value="">Catégorie principale</option>

<?php foreach($categories as $cat): ?>

    <?php // Une catégorie ne peut pas devenir sa propre catégorie parente,
          // donc on ne se propose pas soi-même dans la liste en mode édition. ?>
    <?php if (!$editCategory || $cat->id != $editCategory->id): ?>
        <option value="<?= $cat->id ?>" <?= ($editCategory && $editCategory->parent_id == $cat->id) ? "selected" : "" ?>>
        <?= htmlspecialchars($cat->name) ?>
        </option>
    <?php endif; ?>

<?php endforeach; ?>

</select>


<?php if ($editCategory): ?>
    <div class="form-actions">
        <button type="submit" name="update_category">Enregistrer</button>
        <a href="add_categorie.php" class="btn-cancel">Annuler</a>
    </div>
<?php else: ?>
    <button type="submit">Ajouter</button>
<?php endif; ?>

</form>
</div>


<h3 style="margin-bottom: 15px;">Liste des catégories</h3>

<?php if (empty($categories)): ?>

<p class="empty-state">Aucune catégorie pour l'instant.</p>

<?php else: ?>

<div class="table-wrap">
<table>

<tr>
<th>Nom catégorie</th>
<th>Catégorie parente</th>
<th>Actions</th>
</tr>

<?php foreach($categories as $cate): ?>

<tr>

<td><?= htmlspecialchars($cate->name) ?></td>
<td>
    <?php if ($cate->parent_id && isset($categoryNames[$cate->parent_id])): ?>
        <span class="badge-category"><?= htmlspecialchars($categoryNames[$cate->parent_id]) ?></span>
    <?php else: ?>
        <span class="badge-none">Catégorie principale</span>
    <?php endif; ?>
</td>
<td>
    <div class="actions-cell">
    <?php if ($isAdmin): ?>
        <a class="btn btn-edit" href="?edit=<?= $cate->id ?>">Modifier</a>
        <a class="btn btn-delete"
           href="?delete=<?= $cate->id ?>"
           onclick="return confirm('Supprimer cette catégorie ?')">
           Supprimer
        </a>
    <?php else: ?>
        <a href="#" class="btn btn-edit btn-locked"
           onclick="alert('Action réservée aux administrateurs : tu ne peux pas modifier de catégorie.'); return false;">
           Modifier
        </a>
        <a href="#" class="btn btn-delete btn-locked"
           onclick="alert('Action réservée aux administrateurs : tu ne peux pas supprimer de catégorie.'); return false;">
           Supprimer
        </a>
    <?php endif; ?>
    </div>
</td>

</tr>

<?php endforeach; ?>

</table>
</div>

<?php endif; ?>

</div>

</div>

</div>

</body>
</html>