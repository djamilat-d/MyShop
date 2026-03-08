<?php
session_start();

require_once "../models/connexion.php";
require_once "../models/User.php";

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: signin.php");
    exit();
}

$userObj = new User($pdo);
$message = "";

if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    if ($id != $_SESSION['user_id']) {
        $userObj->deleteUser($id);
        $message = "User deleted successfully.";
    } else {
        $message = "You cannot delete yourself.";
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_user'])) {
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

<link rel="stylesheet" href="../style.css">

</head>

<body>

<div class="admin-layout">

<div class="sidebar">

<h2>MYSHOP</h2>

<a href="admin.php">Dashboard</a>
<a href="admin_users.php" class="active">Users</a>
<a href="admin_products.php">Products</a>
<a href="add_categorie.php">Categories</a>
<a href="logout.php">Logout</a>

</div>


<div class="main-content">

<div class="header">

<h1>Users Management</h1>

<p>Welcome <?= htmlspecialchars($_SESSION['username']) ?></p>

</div>


<div class="container">

<?php if ($message): ?>
<p class="alert"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>


<?php if ($editUser): ?>

<div class="form-box">

<h3>Edit User</h3>

<form method="POST">

<input type="hidden" name="id" value="<?= $editUser['id'] ?>">

<input type="text"
name="username"
value="<?= htmlspecialchars($editUser['username']) ?>"
required>

<input type="email"
name="email"
value="<?= htmlspecialchars($editUser['email']) ?>"
required>

<label>

<input type="checkbox"
name="admin"
<?= $editUser['admin'] ? "checked" : "" ?>>

Administrator

</label>

<button type="submit" name="update_user">
Update User
</button>

</form>

</div>

<?php endif; ?>


<table>

<thead>

<tr>

<th>ID</th>
<th>Username</th>
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
<td><?= $user['admin'] ? "Yes" : "No" ?></td>

<td>

<a class="btn-edit"
href="?edit=<?= $user['id'] ?>">Edit</a>

<?php if ($user['id'] != $_SESSION['user_id']): ?>

<a class="btn-delete"
href="?delete=<?= $user['id'] ?>"
onclick="return confirm('Delete this user?')">

Delete

</a>

<?php endif; ?>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</div>

</body>
</html>