<?php
session_start();

// Supprimer un produit est ouvert à tout utilisateur connecté, mais
// seulement pour SES PROPRES produits (ou n'importe lequel pour un admin,
// vérifié plus bas une fois le produit chargé). On garde uniquement la
// vérification de connexion ici.
if(!isset($_SESSION['user_id'])){
    header('Location: ../views/signin.php');
    exit();
}

include_once '../models/product.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $productModel = new Products();
    $produit = $productModel->getId($id);

    // On vérifie la propriété du produit avant de supprimer quoi que ce
    // soit : sans ça, n'importe quel utilisateur connecté pourrait
    // supprimer les produits ajoutés par les autres juste en changeant
    // l'id dans l'URL.
    $isAdmin = !empty($_SESSION['is_admin']);
    $isOwner = $produit && $produit['created_by'] == $_SESSION['user_id'];

    if ($produit && ($isAdmin || $isOwner)) {
        $productModel->delete($id);
    }
}

// On retourne vers la page de gestion des produits, pas vers models/product.php
// (une classe PHP, pas une page) : c'était le cas avant et ça envoyait
// l'utilisateur sur une page blanche après chaque suppression.
header("Location: ../views/admin_products.php?success=1");
exit();
?>
