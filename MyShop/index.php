<?php
// Ce fichier n'existe que pour que "my-shop.freedev.app/" (sans rien après)
// affiche directement la boutique. La vraie page d'accueil vit dans
// views/index.php (organisation en modèles/vues/contrôleurs du projet),
// donc on redirige simplement vers elle.
header("Location: views/index.php");
exit();
