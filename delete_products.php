<?php 
session_start(); 

if(!isset($_SESSION['user_id']) || 
   (!isset($_SESSION['admin']) && !isset($_SESSION['is_admin']))){
    header('Location: signin.php');
    exit();
}

include_once 'product.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $productModel = new Products();
    $productModel->delete($id);
}

header("Location: admin_products.php?success=1");
exit();
?>
