SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

DROP TABLE IF EXISTS `commande`;
DROP TABLE IF EXISTS `contenir`;
DROP TABLE IF EXISTS `produit`;
DROP TABLE IF EXISTS `utilisateur`;

CREATE TABLE `commande` (
  `numCommande` int(11) NOT NULL,
  `numUtilisateur` int(11) NOT NULL,
  `prix` decimal(8,2) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `contenir` (
  `numCommande` int(11) NOT NULL,
  `numProduit` int(11) NOT NULL,
  `quantite` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `produit` (
  `numProduit` int(11) NOT NULL,
  `nomProduit` varchar(128) NOT NULL,
  `prix` decimal(8,2) NOT NULL,
  `description` varchar(512) NOT NULL,
  `stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `produit` (`numProduit`, `nomProduit`, `prix`, `description`, `stock`) VALUES(1, 'iPhone 6', '789.99', 'L\'iPhone 6 offre un écran Retina HD, plus grand que celui du 5S (4,7 pouces contre 4). Le smartphone se décline en plusieurs couleurs et plusieurs capacités de stockage 16, 64 et 128 Go. Il embarque un nouveau processeur (Apple A8) gérant les instructions 64 bits.', 30);
INSERT INTO `produit` (`numProduit`, `nomProduit`, `prix`, `description`, `stock`) VALUES(2, 'iPhone 5', '664.99', 'Equipé d\'une coque assez semblable à celle du 4S, l\'iPhone 5 affiche une diagonale de 4 pouces tout en gagnant en finesse et en légéreté. Plus puissant et enrichi de nouvelles fonctions, ce smartphone est compatible avec les réseaux mobiles très haut débit (42 Mbit/s et future 4G).', 45);
INSERT INTO `produit` (`numProduit`, `nomProduit`, `prix`, `description`, `stock`) VALUES(3, 'iPhone 4', '485.89', 'Nouveau design, nouvelle configuration et nouvel OS pour l\'iPhone 4. Le dernier-né des smartphones d\'Apple embarque la puce maison en son coeur, bénéficie d\'une meilleur écran, d\'une fonction de visioconférence et des améliorations de l\'iOS 4. Il est disponible en 16 et 32 Go en noir ou blanc.', 40);
INSERT INTO `produit` (`numProduit`, `nomProduit`, `prix`, `description`, `stock`) VALUES(4, 'iPhone 3G', '149.00', 'L\'iPhone 2 conserve les qualités de son prédécesseur -un très grand écran tactile, une ergonomie imbattable, un design incomparable, etc.- et améliore ses fonctions de communication en passant à la 3,5G. Il se dote aussi d\'un GPS intégré.', 60);
INSERT INTO `produit` (`numProduit`, `nomProduit`, `prix`, `description`, `stock`) VALUES(5, 'Samsung Galaxy S6', '699.00', 'Le Samsung Galaxy S6 est le nouveau modèle haut de gamme du constructeur coréen. Fonctionnant avec un processeur à huit coeurs, ce smartphone promet une puissance de feu remarquable et un excellent confort d\'utilisation, notamment grâce à son écran 5,1 pouces Quad HD (2560 x 1440 pixels).', 55);
INSERT INTO `produit` (`numProduit`, `nomProduit`, `prix`, `description`, `stock`) VALUES(6, 'Samsung Galaxy S5', '549.90', 'Samsung a officialisé la sortie de son smartphone Galaxy S5 au Mobile World Congress de Barcelone. Ce nouveau fer de lance de la marque se positionne comme un modèle haut de gamme, puissant, design et bardé de technologies : lecteur d’empreinte digitale, capteur vidéo filmant en ultra haute définition ou encore une connectivité ultra rapide aussi bien en Wi-Fi qu’en 4G.', 75);
INSERT INTO `produit` (`numProduit`, `nomProduit`, `prix`, `description`, `stock`) VALUES(7, 'Samsung Galaxy S4', '429.99', 'Doté d\'un écran plus grand et d\'un boîtier plus fin et plus léger que celui du Galaxy S3, le nouveau Galaxy S4 est aussi plus puissant, et offre de nombreuses fonctionnalités supplémentaires...', 40);
INSERT INTO `produit` (`numProduit`, `nomProduit`, `prix`, `description`, `stock`) VALUES(8, 'Samsung Galaxy S3', '329.89', 'Le Galaxy SIII, grand absent de l\'édition 2012 Mobile World Congress, a enfin été dévoilé par Samsung. Equipé d\'un processeur quatre cœurs à 1,4 GHz, il fonctionne avec Ice Cream Sandwich et la surcouche Touchwiz dans une nouvelle version. Doté d\'un grand écran Super Amoled et HD de 4,8 Pouces, le SIII, fin et léger, pourrait être proposé en plusieurs capacités de stockage (16, 32 ou 64 Go) extensibles au travers d\'un lecteur de MicroSD.', 30);


CREATE TABLE `utilisateur` (
  `numUtilisateur` int(11) NOT NULL,
  `nom` varchar(128) NOT NULL,
  `prenom` varchar(128) NOT NULL,
  `mdp` varchar(64) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `email` varchar(128) NOT NULL,
  `nonce` varchar(32) DEFAULT 'empty'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `commande`
  ADD PRIMARY KEY (`numCommande`),
  ADD KEY `numUtilisateur` (`numUtilisateur`);

ALTER TABLE `contenir`
  ADD PRIMARY KEY (`numCommande`,`numProduit`),
  ADD KEY `numProduit` (`numProduit`);

ALTER TABLE `produit`
  ADD PRIMARY KEY (`numProduit`);

ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`numUtilisateur`),
  ADD UNIQUE KEY `email` (`email`);


ALTER TABLE `commande`
  MODIFY `numCommande` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `produit`
  MODIFY `numProduit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
ALTER TABLE `utilisateur`
  MODIFY `numUtilisateur` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `commande`
  ADD CONSTRAINT `commande_ibfk_1` FOREIGN KEY (`numUtilisateur`) REFERENCES `utilisateur` (`numUtilisateur`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `contenir`
  ADD CONSTRAINT `contenir_ibfk_1` FOREIGN KEY (`numCommande`) REFERENCES `commande` (`numCommande`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `contenir_ibfk_2` FOREIGN KEY (`numProduit`) REFERENCES `produit` (`numProduit`) ON DELETE CASCADE ON UPDATE CASCADE;

INSERT INTO `utilisateur` (`numUtilisateur`, `nom`, `prenom`, `mdp`, `admin`, `email`, `nonce`) VALUES (NULL, 'toto', 'admin', '88b80122193ac9995cef0248cc74eaa7f3fd065fb934a05dfd6d7877c738ffb2', '1', 'admin@gmail.com', NULL);
INSERT INTO `utilisateur` (`numUtilisateur`, `nom`, `prenom`, `mdp`, `admin`, `email`, `nonce`) VALUES (NULL, 'toto', 'user', '88b80122193ac9995cef0248cc74eaa7f3fd065fb934a05dfd6d7877c738ffb2', '', 'user@gmail.com', NULL);