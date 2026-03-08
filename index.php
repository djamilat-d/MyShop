<?php
session_start();
require_once "product.php";
$productModel = new Products();
$produits = $productModel->getAll();

$message ="";
$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
if($username){
    $message = "Hello, ". htmlspecialchars($username) . "!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Document</title>
    
</head>
<body>
    <nav class="navbar">
        <div class="logo" > <span class="styleM">M</span>y<span lass="style">~</span><span class="styleS">s</span>hop</div>
        <ul class="liste1">
            <li class="active">Home</li>
            <li> <a href="#produit" class="btndcon">Produits</a></li>
            <li><a href="" class="btndcon">Solde</a></li>
            <li><a href="" class="btndcon">Contact</a></li>

        </ul>
        <div >
           <?php 
           if(isset($_SESSION["user_id"])):
           ?>
           <a href="logout.php" class="btndcon">Se deconnecter</a>
            <a href="admin.php" class="btndcon">Administrateur</a>


           <?php
           echo $message;
           ?>
           <?php 
          else:

           ?>
            <a href="admin.php" class="btndcon">Administrateur</a>

           <a href="signup.php" class="btndcon">S'inscrire</a>
           <a href="signin.php" class="btndcon">Se connecter</a>
            <?php endif;?>
        </div>
        <p>

        </p>
        <div ><img src="../assets/menu.png" alt="" class="menu"></div>
    </nav>
     <div class="search-container">

<form action="search.php" method="GET" class="search-bar">

<input type="text" name="name" placeholder="Product name">

<input type="text" name="category" placeholder="Category">

<input type="number" name="price" placeholder="Max price">

<select name="sort">

<option value="">Sort</option>
<option value="name_asc">A → Z</option>
<option value="name_desc">Z → A</option>
<option value="price_asc">Price ↑</option>
<option value="price_desc">Price ↓</option>

</select>

<button type="submit">Search</button>

</form>

</div>
    <header >
                <img src="../assets/luxury2.jpg" alt="" class="heard" >

               

    </header>
    <!-- </?php function afficherP(){
        require_once("bd.php");
            $database = new BD;
            $db = $database->getConnect();
            $sql = "SELECT * FROM  products";

           $stmt = $db->prepare($sql);
           $stmt->execute();

            $data = $stmt->fetchALL(PDO::FETCH_OBJ);
            $stmt->closeCursor();
            return $data;

       
    }
    ?>
    </?php
    $produits = afficherP();

    ?> -->
    <section>
        <h2 class="produit" id="produit">NOS PRODUIT</h2>

    <div class="grid">
      
    <?php foreach ($produits as $produit):  ?>

        <div class="card">

            <div class="Coombes">
                <img src="../images/<?= $produit['picture'] ?> "alt="" class="img">

                <div class="info">
                    <h2><?= $produit['name'] ?> </h2>
                    <span class="price"><?= $produit['price'] ?> FCFA</span>
                </div>

                <h3>LOUNGE</h3>
                <div class="description"><?= $produit['description'] ?> </div>
                <button class="achat">Acheter</button>
            </div>
        </div>
     
       
        <?php endforeach  ?>

       </div>  
       </section>
</body>
</html>