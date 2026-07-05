<?php
session_start();

require_once "../models/connexion.php";
require_once "../models/User.php";

// Pas connecté du tout : direction la page de connexion, comme partout ailleurs.
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

// Tout utilisateur connecté peut VOIR cette page (la liste complète des
// utilisateurs), pour avoir une vue d'ensemble du tableau de bord. Mais
// modifier ou supprimer un utilisateur reste réservé aux administrateurs :
// les deux blocs ci-dessous revérifient $isAdmin avant d'écrire quoi que ce
// soit en base (sécurité réelle), et les boutons Modifier/Supprimer sont en
// plus bloqués avec une alerte côté interface si on n'est pas admin.
$isAdmin = !empty($_SESSION['is_admin']);

$userObj = new User($pdo);
$message = "";

if (isset($_GET['delete'])) {
    if (!$isAdmin) {
        $message = "Action réservée aux administrateurs : tu ne peux pas supprimer d'utilisateur.";
    } else {
        $id = (int) $_GET['delete'];
        if ($id != $_SESSION['user_id']) {
            $userObj->deleteUser($id);
            $message = "User deleted successfully.";
        } else {
            $message = "You cannot delete yourself.";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_user'])) {
    if (!$isAdmin) {
        $message = "Action réservée aux administrateurs : tu ne peux pas modifier d'utilisateur.";
    } else {
        $id = (int) $_POST['id'];
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $admin = isset($_POST['admin']) ? 1 : 0;

        if (!empty($username) && !empty($email)) {
            $userObj->updateUser($id, $username, $email, $admin);
            $message = "User updated.";
        } else {
            $message = "All fields required.";
        }
    }
}

$editUser = null;
if (isset($_GET['edit'])) {
    $id = (int) $_GET['edit'];
    $editUser = $userObj->getUserById($id);
}

$users = $userObj->getAllUsers();
?>

<!DOCTYPE html>
<html lang="fr">
<head>

<meta charset="UTF-8">
<title>Users Management</title>

<link rel="stylesheet" href="main.css?v=<?= @filemtime(__DIR__ . '/main.css') ?: time() ?>">

</head>

<body>

<div class="admin-layout">

<div class="sidebar">

<h2 class="sidebar-logo"><span class="styleM">M</span>y<span class="style">~</span><span class="styleS">S</span>hop</h2>

<a href="admin.php">Dashboard</a>
<a href="admin_users.php" class="active">Users</a>
<a href="admin_products.php">Products</a>
<a href="index.php">Voir la boutique</a>
<a href="add_categorie.php">Categories</a>
<a href="logout.php" class="sidebar-logout">Se déconnecter</a>

</div>


<div class="main-content">

<div class="header">

<h1>Users Management</h1>

<p>Welcome <?= htmlspecialchars($_SESSION['username']) ?></p>

</div>


<div class="container" style="display: block;">

<div class="page-toolbar">
<h2>Liste des utilisateurs</h2>
</div>

<?php if ($message): ?>
<p class="alert-info"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>


<?php if ($editUser): ?>

<div class="form-box">

<h3>Modifier l'utilisateur</h3>

<?php if (!$isAdmin): ?>
<p class="owner-note" style="margin-bottom: 10px;">Consultation seule : seul un administrateur peut enregistrer ces modifications.</p>
<?php endif; ?>

<form method="POST"
<?php if (!$isAdmin): ?> onsubmit="alert('Action réservée aux administrateurs : tu ne peux pas modifier d\'utilisateur.'); return false;"<?php endif; ?>>

<input type="hidden" name="id" value="<?= $editUser['id'] ?>">

<input type="text"
name="username"
value="<?= htmlspecialchars($editUser['username']) ?>"
required>

<input type="email"
name="email"
value="<?= htmlspecialchars($editUser['email']) ?>"
required>

<label class="checkbox-label">

<input type="checkbox"
name="admin"
<?= $editUser['admin'] ? "checked" : "" ?>>

Administrateur

</label>

<div class="form-actions">
<button type="submit" name="update_user">
Enregistrer
</button>
<a href="admin_users.php" class="btn-cancel">Annuler</a>
</div>

</form>

</div>

<?php endif; ?>


<div class="table-wrap">
<table>

<thead>

<tr>

<th>ID</th>
<th>Nom d'utilisateur</th>
<th>Email</th>
<th>Admin</th>
<th>Actions</th>

</tr>

</thead>

<tbody>

<?php foreach ($users as $user): ?>

<tr>

<td><?= $user['id'] ?></td>
<td><?= htmlspecialchars($user['username']) ?></td>
<td><?= htmlspecialchars($user['email']) ?></td>
<td>
    <?php if ($user['admin']): ?>
        <span class="badge-admin">Admin</span>
    <?php else: ?>
        <span class="badge-none">Utilisateur</span>
    <?php endif; ?>
</td>

<td>

<div class="actions-cell">

<?php if ($isAdmin): ?>

<a class="btn btn-edit"
href="?edit=<?= $user['id'] ?>">Modifier</a>

<?php if ($user['id'] != $_SESSION['user_id']): ?>

<a class="btn btn-delete"
href="?delete=<?= $user['id'] ?>"
onclick="return confirm('Supprimer cet utilisateur ?')">

Supprimer

</a>

<?php else: ?>

<span class="owner-note">C'est toi</span>

<?php endif; ?>

<?php else: ?>

<a href="#" class="btn btn-edit btn-locked"
onclick="alert('Action réservée aux administrateurs : tu ne peux pas modifier d\'utilisateur.'); return false;">
Modifier
</a>
<a href="#" class="btn btn-delete btn-locked"
onclick="alert('Action réservée aux administrateurs : tu ne peux pas supprimer d\'utilisateur.'); return false;">
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

</div>

</div>

</div>

</body>
</html>