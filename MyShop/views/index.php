<?php
session_start();

include_once '../models/product.php';
include_once '../models/categorie.php';

$productModel = new Products();
$produits = $productModel->getAll();

// Pour la barre de recherche : on propose les vraies catégories existantes
// plutôt qu'un champ texte libre, où le client devait deviner l'orthographe
// exacte d'une catégorie pour que la recherche fonctionne.
$categoryModel = new Category();
$categoriesForSearch = $categoryModel->getAll();

$message ="";
$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
if($username){
    $message = " Hello, ". htmlspecialchars($username) . "!";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php // "?v=..." (date de dernière modification du fichier) force le
          // navigateur (et le cache de l'hébergeur) à retélécharger le CSS
          // dès qu'on le modifie, au lieu de resservir une version en cache
          // avec le même nom de fichier : c'est ce qui faisait qu'un
          // correctif fonctionnait en local mais pas une fois hébergé. ?>
    <link rel="stylesheet" href="styless.css?v=<?= @filemtime(__DIR__ . '/styless.css') ?: time() ?>">
    <title>Document</title>

</head>

<body>
    <nav class="navbar">
        <div class="logo"> <span class="styleM">M</span>y<span class="style">~</span><span class="styleS">s</span>hop
        </div>
        <?php // Sur mobile/tablette, le menu burger doit pouvoir afficher tout
              // ce qu'il y a ici (liens de navigation + connexion/déconnexion) :
              // on regroupe donc les deux dans un seul conteneur, que le burger
              // peut ouvrir/fermer en lui ajoutant/enlevant la classe "open"
              // (voir le script tout en bas, et les règles .nav-links dans
              // styless.css). Avant, ces liens disparaissaient simplement en
              // dessous de 1240px sans aucun moyen de les rouvrir. ?>
        <div class="nav-links" id="navLinks">
            <ul class="liste1">
                <li class="active">Home</li>
                <li> <a href="#produit" class="btndcon">Produits</a></li>
                <li><a href="" class="btndcon">Solde</a></li>
                <li><a href="#contact" class="btndcon">Contact</a></li>

            </ul>
            <div class="nav-auth">
                <?php
               if(isset($_SESSION["user_id"])):
               ?>
                <?php // Tout le monde peut accéder au dashboard une fois
                      // connecté (admin ou non) : seules certaines actions à
                      // l'intérieur sont restreintes (voir admin_products.php,
                      // admin_users.php, add_categorie.php). ?>
                <a href="admin.php" class="btndcon">Dashboard</a>

                <?php // $message est du texte brut (pas un lien), donc le "gap"
                      // du flex sur ce <div> ne l'espace pas correctement des
                      // liens à côté : on l'enveloppe dans un span dédié pour
                      // pouvoir lui donner sa propre marge. ?>
                <?php if ($message): ?>
                <span class="welcome-msg"><?php echo $message; ?></span>
                <?php endif; ?>
                <a href="logout.php" class="btndcon">Se deconnecter</a>

                <?php
              else:

               ?>
                <a href="signup.php" class="btndcon">S'inscrire</a>
                <a href="signin.php" class="btndcon">Se connecter</a>
                <?php endif;?>
            </div>
        </div>
        <div>
            <img src="image/menu.png" alt="Menu" class="menu" id="menuBtn">
        </div>
    </nav>

    <script>
        // Ouvre/ferme le menu mobile au clic sur le burger. On utilise
        // toggle plutôt que deux handlers séparés (open/close) : plus simple
        // et ça évite les états incohérents si on clique vite plusieurs fois.
        document.getElementById('menuBtn').addEventListener('click', function () {
            document.getElementById('navLinks').classList.toggle('open');
        });
    </script>
    <header >
                <img src="../assets/luxury2.jpg" alt="" class="heard" >

               

    </header>
    <div class="search-container">

        <form action="search.php" method="GET" class="search-bar">

            <input type="text" name="name" placeholder="Product name">

            <select name="category">
                <option value="">Toutes les catégories</option>
                <?php foreach($categoriesForSearch as $cat): ?>
                    <option value="<?= $cat->id ?>"><?= htmlspecialchars($cat->name) ?></option>
                <?php endforeach; ?>
                <option value="0">Autre</option>
            </select>

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

                <a href="product_detail.php?id=<?= $produit['id'] ?>" class="Coombes" style="display:block; text-decoration:none; color:inherit;">
                    <img src="image/<?= $produit['picture'] ?> " alt="Image produit <?= $produit['id'] ?>  " class="img">

                    <div class="info">
                        <h2><?= $produit['name'] ?> </h2>
                        <span class="price"><?= $produit['price'] ?> FCFA</span>
                    </div>

                    <?php if (!empty($produit['category_name'])): ?>
                    <h3><?= htmlspecialchars($produit['category_name']) ?></h3>
                    <?php endif; ?>
                    <div class="description"><?= $produit['description'] ?> </div>
                </a>
                <button class="achat">Acheter</button>
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
                        <p>penthouses, grands appartements en dernier étage.</p>
                    </div>
                </div>

            </label>

            <input type="radio" name="slide" id="c2" checked class="input">
            <label for="c2" class="cards">
                <div class="row">
                    <div class="icon">2</div>
                    <div class="descriptions">
                        <h4>Le penthouse</h4>
                        <p>penthouses, grands appartements en dernier étage.</p>
                    </div>
                </div>

            </label>

            <input type="radio" name="slide" id="c3" checked class="input">
            <label for="c3" class="cards">
                <div class="row">
                    <div class="icon">3</div>
                    <div class="descriptions">
                        <h4>Le penthouse</h4>
                        <p>penthouses, grands appartements en dernier étage.</p>
                    </div>
                </div>

            </label>

            <input type="radio" name="slide" id="c4" checked class="input">
            <label for="c4" class="cards">
                <div class="row">
                    <div class="icon">4</div>
                    <div class="descriptions">
                        <h4>Le penthouse</h4>
                        <p>penthouses, grands appartements en dernier étage.</p>
                    </div>
                </div>

            </label>

            <input type="radio" name="slide" id="c5" checked class="input">
            <label for="c5" class="cards">
                <div class="row">
                    <div class="icon">5</div>
                    <div class="descriptions">
                        <h4>Le penthouse</h4>
                        <p>penthouses, grands appartements en dernier étage.</p>
                    </div>
                </div>

            </label>

            <input type="radio" name="slide" id="c6" checked class="input">
            <label for="c6" class="cards">
                <div class="row">
                    <div class="icon">6</div>
                    <div class="descriptions">
                        <h4>Le penthouse</h4>
                        <p>penthouses, grands appartements en dernier étage.</p>
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
                    <a href="#galery">Galerie</a>
                    <a href="#">Contact</a>
                </div>

                <div class="footer_contact">
                    <p>Email: contact@e-commerce.com</p>
                    <p>Téléphone: +225 00 00 00 00</p>
                    <p>Adresse: Cocody, Abidjan, Côte d'Ivoire</p>
                </div>

                <div class="footer_names">
                    <span>D. Djamilat</span>
                    <span>K. Laurince</span>
                    <span>T. Kady</span>
                </div>
            </div>
        </div>
        <div class="footer_bottom">
            <p>&copy; Tous droits réservés.</p>
        </div>
    </footer>
</body>

</html>