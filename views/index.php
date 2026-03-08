<?php
session_start();

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
    <link rel="stylesheet" href="../styless.css">
    <title>Document</title>

</head>

<body>
    <nav class="navbar">
        <div class="logo"> <span class="styleM">M</span>y<span class="style">~</span><span class="styleS">s</span>hop
        </div>
        <ul class="liste1">
            <li class="active">Home</li>
            <li> <a href="#produit" class="btndcon">Produits</a></li>
            <li><a href="" class="btndcon">Solde</a></li>
            <li><a href="#contact" class="btndcon">Contact</a></li>

        </ul>
        <div>
            <?php 
           if(isset($_SESSION["user_id"])):
           ?>
            <a href="admin.php" class="btndcon">Administrateur</a>


            <?php
           echo $message;
           ?>
            <a href="logout.php" class="btndcon">Se deconnecter</a>

            <?php 
          else:

           ?>
            <a href="admin.php" class="btndcon">Administrateur</a>

            <a href="inscription.php" class="btndcon">S'inscrire</a>
            <a href="login.php" class="btndcon">Se connecter</a>
            <?php endif;?>
        </div>
        <p>

        </p>
        <div><img src="/image/menu.png" alt="" class="menu"></div>
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
                    <img src="/assets/<?= $produit->picture ?> " alt="Image produit <?= $produit->id ?>  " class="img">

                    <div class="info">
                        <h2><?= $produit->name ?> </h2>
                        <span class="price"><?= $produit->price ?> FCFA</span>
                    </div>

                    <h3><?= $category->name ?></h3>
                    <div class="description"><?= $produit->description ?> </div>
                    <button class="achat">Acheter</button>
                </div>
            </div>


            <?php endforeach  ?>

        </div>
    </section>

    <section class="wapper">

        <h2 class="produit" id="galery">NOTRE GALERY</h2>

        <div class="container">
            <input type="radio" name="slide" id="c1" checked class="input">
            <label for="c1" class="cards">
                <div class="row">
                    <div class="icon">1</div>
                    <div class="descriptions">
                        <h4>Le penthouse</h4>
                        <p>enthouses, grands appartements en dernier étage.</p>
                    </div>
                </div>

            </label>

            <input type="radio" name="slide" id="c2" checked class="input">
            <label for="c2" class="cards">
                <div class="row">
                    <div class="icon">2</div>
                    <div class="descriptions">
                        <h4>Le penthouse</h4>
                        <p>enthouses, grands appartements en dernier étage.</p>
                    </div>
                </div>

            </label>

            <input type="radio" name="slide" id="c3" checked class="input">
            <label for="c3" class="cards">
                <div class="row">
                    <div class="icon">3</div>
                    <div class="descriptions">
                        <h4>Le penthouse</h4>
                        <p>enthouses, grands appartements en dernier étage.</p>
                    </div>
                </div>

            </label>

            <input type="radio" name="slide" id="c4" checked class="input">
            <label for="c4" class="cards">
                <div class="row">
                    <div class="icon">4</div>
                    <div class="descriptions">
                        <h4>Le penthouse</h4>
                        <p>enthouses, grands appartements en dernier étage.</p>
                    </div>
                </div>

            </label>

            <input type="radio" name="slide" id="c5" checked class="input">
            <label for="c5" class="cards">
                <div class="row">
                    <div class="icon">5</div>
                    <div class="descriptions">
                        <h4>Le penthouse</h4>
                        <p>enthouses, grands appartements en dernier étage.</p>
                    </div>
                </div>

            </label>

            <input type="radio" name="slide" id="c6" checked class="input">
            <label for="c6" class="cards">
                <div class="row">
                    <div class="icon">6</div>
                    <div class="descriptions">
                        <h4>Le penthouse</h4>
                        <p>enthouses, grands appartements en dernier étage.</p>
                    </div>
                </div>

            </label>
        </div>
        <br><br>
    </section>

    <footer id="contact">
        <div class="footer_img">
            <div class="footer_content">
                <div class="footer_social">
                    <a href="#"><img src="https://cdn-icons-png.flaticon.com/512/733/733547.png" alt="Facebook"></a>
                    <a href="#"><img src="https://cdn-icons-png.flaticon.com/512/733/733558.png" alt="Twitter"></a>
                    <a href="#"><img src="https://cdn-icons-png.flaticon.com/512/2111/2111463.png" alt="Instagram"></a>
                </div>



                <div class="footer_nav">
                    <a href="#">Accueil</a>
                    <a href="#">Produits</a>
                    <a href="#">Galerie</a>
                    <a href="#">Contact</a>
                </div>

                <div class="footer_contact">
                    <p>Email: contact@e-commerce.com</p>
                    <p>Téléphone: +225 00 00 00 00</p>
                    <p>Adresse: Cocody, Abidjan, Côte d'Ivoire</p>
                </div>

                <div class="footer_names">
                    <span>T. Kady</span>
                    <span>D. Djamilat</span>
                    <span>S. Laurince</span>
                </div>
            </div>
        </div>
        <div class="footer_bottom">
            <p>&copy; Tous droits réservés.</p>
        </div>
    </footer>
</body>

</html>