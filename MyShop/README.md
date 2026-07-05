# MyShop

Projet **C-DEV-114 - Web fundamentals** (Coding Academy) : un site e-commerce complet développé en PHP orienté objet, avec une interface d'administration et une boutique publique.

## Pourquoi ce projet ?

C'est notre premier vrai projet web complet, du formulaire d'inscription jusqu'à la boutique en ligne. L'idée n'était pas de créer un site "parfait" ou prêt pour de vrais clients, mais de nous mettre dans la peau de développeurs qui doivent faire tenir ensemble toutes les briques d'un site : une base de données, un back-end qui la sécurise, et des pages que n'importe qui peut utiliser sans rien comprendre au code derrière.

Ce projet nous a permis de pratiquer concrètement :

- **La programmation orientée objet en PHP** : chaque entité (utilisateur, produit, catégorie) a sa propre classe, avec ses propres méthodes, plutôt que du code PHP dispersé et répété partout.
- **L'accès à une base de données avec PDO** : requêtes préparées, gestion des erreurs, relations entre tables (catégories imbriquées, produits liés à une catégorie).
- **La sécurité web de base** : hachage des mots de passe (jamais en clair), gestion de sessions, protection des pages d'administration contre les utilisateurs non autorisés.
- **Le CRUD** (Create/Read/Update/Delete) appliqué à plusieurs entités différentes, avec la gestion des erreurs qui va avec.
- **La construction d'une interface utilisable** : un formulaire de recherche avec tri, une boutique responsive, une administration séparée du site public.

En résumé, l'objectif pédagogique était moins "faire un site qui a l'air joli" que "comprendre pourquoi chaque brique existe et ce qui se passe si on l'enlève".

## Fonctionnalités

- **Authentification** : inscription (`signup.php`) et connexion (`signin.php`) avec mots de passe hashés (bcrypt via `password_hash`), déconnexion sécurisée (destruction de session).
- **Interface d'administration** (`admin.php`), accessible à tout utilisateur connecté, qui voit l'intégralité des sections (Produits, Utilisateurs, Catégories) — seules les actions à l'intérieur changent selon le rôle :
  - **N'importe quel utilisateur connecté** peut ajouter des produits, et ne peut modifier/supprimer que les produits qu'il a lui-même ajoutés. Il peut aussi consulter la liste des utilisateurs et des catégories, mais pas les modifier.
  - **Seuls les administrateurs** peuvent gérer les utilisateurs (éditer, supprimer, promouvoir en administrateur), gérer les catégories (créer, modifier, supprimer) et modifier/supprimer n'importe quel produit, y compris ceux ajoutés par d'autres.
  - Si un utilisateur normal tente une action réservée aux admins (modifier un utilisateur, supprimer une catégorie, toucher au produit d'un autre), une alerte s'affiche pour expliquer pourquoi c'est refusé, plutôt que de le laisser deviner ou de simplement cacher les boutons. Cette vérification est aussi faite côté serveur, pas seulement dans l'alerte, pour éviter qu'elle soit contournée.
- **Boutique publique** (`index.php`) : affichage des produits, page de détail par produit.
- **Recherche** (`search.php`) : recherche par nom, catégorie, prix maximum, avec tri (alphabétique, prix croissant/décroissant).

## Stack technique

- PHP (orienté objet, PDO pour l'accès base de données)
- MySQL / MariaDB
- HTML / CSS (pas de framework front-end)

## Structure du projet

```
MyShop/
├── controllers/        # Actions (ex: suppression de produit)
├── models/              # Classes métier (BD, User, Products, Category)
├── views/               # Pages affichées (signup, signin, admin, boutique...)
│   ├── main.css          # Feuille de style unique (boutique + admin)
│   └── image/            # Images des produits uploadées
└── assets/              # Images statiques du site
```

Toutes les pages chargent la même feuille de style, `views/main.css`. Les règles propres à l'admin y sont préfixées par `.admin-layout` pour ne jamais interférer avec le style de la boutique publique.

## Base de données

Nom de la base : `my_shop`. Tables principales :

- **users** : `id`, `username`, `email`, `password` (haché), `admin` (0/1), `created_at`
- **products** : `id`, `name`, `description`, `price`, `picture`, `category_id`, `created_by` (référence vers `users.id`, retient qui a ajouté le produit)
- **categories** : `id`, `name`, `parent_id` (référence vers `categories.id`, permet des catégories imbriquées)

Un export complet (structure + données de démo) est fourni dans `database/my_shop.sql`, pour que n'importe qui récupérant ce projet reparte avec exactement la même base que celle utilisée pendant le développement.

## Installation en local (XAMPP)

1. Installer [XAMPP](https://www.apachefriends.org/).
2. Copier le dossier du projet à l'intérieur de `htdocs` (ou configurer un virtual host pointant vers ce dossier).
3. Démarrer **Apache** et **MySQL** depuis le panneau de contrôle XAMPP.
4. Dans phpMyAdmin (`http://localhost/phpmyadmin`), créer une base nommée `my_shop`, aller dans l'onglet **Importer**, choisir le fichier `database/my_shop.sql` et valider. Toutes les tables et les données de démo sont créées d'un coup.
5. Vérifier les identifiants de connexion dans `models/connexion.php` (par défaut : hôte `127.0.0.1`, utilisateur `root`, mot de passe vide).
6. Ouvrir `http://localhost/<chemin-du-projet>/views/index.php` dans le navigateur.

## Sécurité

- Mots de passe stockés uniquement sous forme hachée (bcrypt).
- L'accès à l'espace admin nécessite d'être connecté ; la gestion des utilisateurs reste en plus strictement réservée aux comptes administrateurs (`$_SESSION['is_admin']`).
- Un utilisateur normal ne peut modifier/supprimer que les produits qu'il a lui-même créés (vérifié via `products.created_by`) ; un administrateur peut agir sur tous les produits.
- Requêtes SQL préparées (PDO) pour éviter les injections SQL.

## Auteurs

- Kady Traore
- Djamilat Diarrassouba
- Laurince Kouakou
