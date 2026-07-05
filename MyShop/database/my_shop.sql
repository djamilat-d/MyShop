-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : dim. 05 juil. 2026 à 02:00
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `my_shop`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `name`, `parent_id`) VALUES
(1, 'Electronique', NULL),
(2, 'Telephone', 1);

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '0',
  `price` int(11) NOT NULL DEFAULT 0,
  `category_id` int(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `picture` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `category_id`, `description`, `picture`, `created_by`) VALUES
(4, 'valises', 26000, 0, 'valises de marques', '1772999416_Valise-De-Voyage-3-PCS-Suitcase-Bags-Trolley-Travel-ABS-Luggage.webp.avif', NULL),
(5, 'telephone', 800000, 1, 'iPhone 17 pro max', '1772999375_iphone__1_.png', NULL),
(9, 'sallon', 250000000, 0, 'haute performance', '1772999128_Fotoaibe.jpg', NULL),
(10, 'chaise ', 10000, 0, 'luxueuse', '1772999057_chair.jpg', NULL),
(11, 'Fauteuil', 500000, 0, 'doux', '1772999313_lounge.jpg', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `admin` tinyint(4) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `admin`, `created_at`) VALUES
(13, 'djamilat D', '$2y$10$KKAiLYmiFOEWmjapHAuN2Ox1OiKqRhxnz6IzSPvWiBJyuHhqgWdBK', 'djamilat@gmail.com', 1, '2026-03-03 00:55:31'),
(14, 'mila', '$2y$10$boaWSBEgT.xUTTRZYJ7fzuUj/sKWcUrZGUENCXXCz/BXZGTFVIc8i', 'mila@gmail.com', 0, '2026-03-03 11:13:47'),
(16, 'kady T', '$2y$10$KtERGezrMFhArbVQp4FvEe6VEnUe61JM06psG6iFvf4Zl/jt2vFiG', 'kaydt@gmail.com', 1, '2026-03-06 15:00:49'),
(18, 'inaya', '$2y$10$kVs3zab7WvCLaeIquOxvGOM/DZ1rUuTtHd5PntmN.vj6bIfJ9l4Ne', 'inaya@gmail.com', 1, NULL),
(21, 'qwerty', '$2y$10$kRZXPtlg75a7N4gcf9N.0.9qy87yVz7KJ347dJOrtTY6jnA.A/AKK', 'qwerty@gmail.com', 0, NULL),
(22, 'madame_d', '$2y$12$wiTfqaPGpjHsdPqT.TdlUevoATCZPG5hOZWYV9w0TID8iQ17HpYIy', 'madamed@gmail.com', 1, NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_products_created_by` (`created_by`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
