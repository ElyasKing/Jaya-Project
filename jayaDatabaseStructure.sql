-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : sam. 22 avr. 2023 à 16:03
-- Version du serveur : 10.4.27-MariaDB
-- Version de PHP : 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `jaya`
--

--
-- Structure de la table `decisions`
--

CREATE TABLE `decisions` (
  `ValidationScolarite_Planning` varchar(3) DEFAULT NULL,
  `ValidationResponsableUE_Planning` varchar(3) DEFAULT NULL,
  `Validation_NF` varchar(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `est_apprenti`
--

CREATE TABLE `est_apprenti` (
  `ID_Utilisateur` int(11) NOT NULL,
  `ID_Invite` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `est_compose`
--

CREATE TABLE `est_compose` (
  `ID_NO` int(11) NOT NULL,
  `ID_Parametre` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `etudiant_tuteur`
--

CREATE TABLE `etudiant_tuteur` (
  `ID_etudiant` int(11) NOT NULL,
  `ID_tuteur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `habilitations`
--

CREATE TABLE `habilitations` (
  `ID_Habilitations` int(4) NOT NULL,
  `Admin_Habilitations` varchar(3) DEFAULT NULL,
  `ResponsableUE_Habilitations` varchar(3) DEFAULT NULL,
  `Scolarite_Habilitations` varchar(3) DEFAULT NULL,
  `TuteurUniversitaire_Habilitations` varchar(3) DEFAULT NULL,
  `Etudiant_Habilitations` varchar(3) DEFAULT NULL,
  `ID_Utilisateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `invite`
--

CREATE TABLE `invite` (
  `ID_Invite` int(11) NOT NULL,
  `Nom_Invite` varchar(100) DEFAULT NULL,
  `Mail_Invite` varchar(100) DEFAULT NULL,
  `Entreprise_Invite` varchar(100) DEFAULT NULL,
  `Ville_Invite` varchar(100) DEFAULT NULL,
  `Telephone_Invite` varchar(10) DEFAULT NULL,
  `EstEnseignant_Invite` varchar(3) DEFAULT NULL,
  `EstProfessionel_Invite` varchar(3) DEFAULT NULL,
  `QRCode_Invite` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `notes_soutenance`
--

CREATE TABLE `notes_soutenance` (
  `ID_NS` int(11) NOT NULL,
  `NoteFinale_NS` float DEFAULT NULL,
  `Commentaire_NS` varchar(500) DEFAULT NULL,
  `ID_UtilisateurEvalue` int(11) NOT NULL,
  `ID_InviteEvaluateur` int(11) DEFAULT NULL,
  `ID_UtilisateurEvaluateur` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `notes_suivi`
--

CREATE TABLE `notes_suivi` (
  `ID_NF` int(11) NOT NULL,
  `NoteFinaleTuteur_NF` float DEFAULT NULL,
  `noteFinaleUE_NF` float DEFAULT NULL,
  `Poster_NF` varchar(3) DEFAULT NULL,
  `Remarque_NF` varchar(800) DEFAULT NULL,
  `Rapport_NF` varchar(3) DEFAULT NULL,
  `Appreciation_NF` varchar(800) DEFAULT NULL,
  `Orthographe_NF` float DEFAULT 0,
  `ID_Utilisateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `parametres`
--

CREATE TABLE `parametres` (
  `ID_param` int(11) NOT NULL,
  `Nom_param` varchar(80) DEFAULT NULL,
  `Description_param` varchar(255) DEFAULT NULL,
  `NbPoint_param` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `planning`
--

CREATE TABLE `planning` (
  `ID_Planning` int(11) NOT NULL,
  `Nom_Planning` varchar(100) DEFAULT NULL,
  `DateSession_Planning` date DEFAULT NULL,
  `HeureDebutSession_Planning` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `ID_Utilisateur` int(11) NOT NULL,
  `Nom_Utilisateur` varchar(150) DEFAULT NULL,
  `Mail_Utilisateur` varchar(150) DEFAULT NULL,
  `MDP_Utilisateur` varchar(100) DEFAULT NULL,
  `Promo_Utilisateur` varchar(50) DEFAULT NULL,
  `Annee_Utilisateur` varchar(10) DEFAULT NULL,
  `HuisClos_Utilisateur` varchar(3) DEFAULT NULL,
  `ID_Planning` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


--
-- Index pour les tables déchargées
--

--
-- Index pour la table `est_apprenti`
--
ALTER TABLE `est_apprenti`
  ADD KEY `ID_Utilisateur` (`ID_Utilisateur`) USING BTREE,
  ADD KEY `ID_Invite` (`ID_Invite`) USING BTREE;

--
-- Index pour la table `est_compose`
--
ALTER TABLE `est_compose`
  ADD KEY `ID_NO` (`ID_NO`) USING BTREE,
  ADD KEY `ID_Parametre` (`ID_Parametre`) USING BTREE;

--
-- Index pour la table `etudiant_tuteur`
--
ALTER TABLE `etudiant_tuteur`
  ADD KEY `ID_etudiant` (`ID_etudiant`) USING BTREE,
  ADD KEY `ID_tuteur` (`ID_tuteur`) USING BTREE;

--
-- Index pour la table `habilitations`
--
ALTER TABLE `habilitations`
  ADD PRIMARY KEY (`ID_Habilitations`),
  ADD UNIQUE KEY `ID_Habilitations` (`ID_Habilitations`),
  ADD KEY `ID_Etudiant` (`ID_Utilisateur`);

--
-- Index pour la table `invite`
--
ALTER TABLE `invite`
  ADD PRIMARY KEY (`ID_Invite`),
  ADD UNIQUE KEY `ID_Invite` (`ID_Invite`);

--
-- Index pour la table `notes_soutenance`
--
ALTER TABLE `notes_soutenance`
  ADD PRIMARY KEY (`ID_NS`),
  ADD KEY `ID_Utilisateur` (`ID_UtilisateurEvalue`),
  ADD KEY `ID_Invite` (`ID_InviteEvaluateur`),
  ADD KEY `ID_NS` (`ID_NS`) USING BTREE,
  ADD KEY `ID_UtilisateurEvaluateur_3` (`ID_UtilisateurEvaluateur`) USING BTREE,
  ADD KEY `ID_UtilisateurEvaluateur_2` (`ID_UtilisateurEvaluateur`) USING BTREE,
  ADD KEY `ID_UtilisateurEvaluateur` (`ID_UtilisateurEvaluateur`) USING BTREE;

--
-- Index pour la table `notes_suivi`
--
ALTER TABLE `notes_suivi`
  ADD PRIMARY KEY (`ID_NF`),
  ADD KEY `ID_Utilisateur` (`ID_Utilisateur`);

--
-- Index pour la table `parametres`
--
ALTER TABLE `parametres`
  ADD PRIMARY KEY (`ID_param`),
  ADD UNIQUE KEY `ID_param` (`ID_param`);

--
-- Index pour la table `planning`
--
ALTER TABLE `planning`
  ADD PRIMARY KEY (`ID_Planning`),
  ADD UNIQUE KEY `ID_Planning` (`ID_Planning`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`ID_Utilisateur`),
  ADD KEY `ID_Planning` (`ID_Planning`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `habilitations`
--
ALTER TABLE `habilitations`
  MODIFY `ID_Habilitations` int(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `invite`
--
ALTER TABLE `invite`
  MODIFY `ID_Invite` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `notes_soutenance`
--
ALTER TABLE `notes_soutenance`
  MODIFY `ID_NS` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `notes_suivi`
--
ALTER TABLE `notes_suivi`
  MODIFY `ID_NF` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `parametres`
--
ALTER TABLE `parametres`
  MODIFY `ID_param` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `planning`
--
ALTER TABLE `planning`
  MODIFY `ID_Planning` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `ID_Utilisateur` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `est_apprenti`
--
ALTER TABLE `est_apprenti`
  ADD CONSTRAINT `est_apprenti_ibfk_1` FOREIGN KEY (`ID_Utilisateur`) REFERENCES `utilisateur` (`ID_Utilisateur`) ON DELETE CASCADE,
  ADD CONSTRAINT `est_apprenti_ibfk_2` FOREIGN KEY (`ID_Invite`) REFERENCES `invite` (`ID_Invite`) ON DELETE CASCADE;

--
-- Contraintes pour la table `est_compose`
--
ALTER TABLE `est_compose`
  ADD CONSTRAINT `est_compose_ibfk_1` FOREIGN KEY (`ID_NO`) REFERENCES `notes_soutenance` (`ID_NS`) ON DELETE CASCADE,
  ADD CONSTRAINT `est_compose_ibfk_2` FOREIGN KEY (`ID_Parametre`) REFERENCES `parametres` (`ID_param`) ON DELETE CASCADE;

--
-- Contraintes pour la table `etudiant_tuteur`
--
ALTER TABLE `etudiant_tuteur`
  ADD CONSTRAINT `etudiant_tuteur_ibfk_1` FOREIGN KEY (`ID_etudiant`) REFERENCES `utilisateur` (`ID_Utilisateur`) ON DELETE CASCADE,
  ADD CONSTRAINT `etudiant_tuteur_ibfk_2` FOREIGN KEY (`ID_tuteur`) REFERENCES `utilisateur` (`ID_Utilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `habilitations`
--
ALTER TABLE `habilitations`
  ADD CONSTRAINT `habilitations_ibfk_1` FOREIGN KEY (`ID_Utilisateur`) REFERENCES `utilisateur` (`ID_Utilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `notes_soutenance`
--
ALTER TABLE `notes_soutenance`
  ADD CONSTRAINT `notes_soutenance_ibfk_1` FOREIGN KEY (`ID_InviteEvaluateur`) REFERENCES `invite` (`ID_Invite`),
  ADD CONSTRAINT `notes_soutenance_ibfk_2` FOREIGN KEY (`ID_UtilisateurEvalue`) REFERENCES `utilisateur` (`ID_Utilisateur`) ON DELETE CASCADE,
  ADD CONSTRAINT `notes_soutenance_ibfk_3` FOREIGN KEY (`ID_UtilisateurEvaluateur`) REFERENCES `utilisateur` (`ID_Utilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `notes_suivi`
--
ALTER TABLE `notes_suivi`
  ADD CONSTRAINT `notes_suivi_ibfk_1` FOREIGN KEY (`ID_Utilisateur`) REFERENCES `utilisateur` (`ID_Utilisateur`) ON DELETE CASCADE;

-- jeu de données de base
INSERT INTO `parametres` (`Nom_param`, `Description_param`, `NbPoint_param`) VALUES
('Date de début des sessions de soutenances', '', NULL),
('Date de fin des sessions de soutenances', '', NULL),
('Durée d\'une session de soutenance', '02:00', NULL),
('Nombre d\'étudiants par session de soutenances', '13', NULL),
('Nombre minimum de note(s) \"professionel\"', '1', NULL),
('Nombre minimum de note(s) \"enseignant\"', '2', NULL),
('Coefficient - note \"professionel\"', '1', NULL),
('Coefficient - note \"enseignant\"', '2', NULL),
('Coefficient - note d\'orale', '2', NULL),
('Coefficient - note de suivi', '1', NULL),
('Temps supplémentaire accordé aux évaluateurs lors des sessions de soutenance', '00:00', NULL);

ALTER TABLE utilisateur ADD SoutenanceSupp_Utilisateur VARCHAR(3) NULL AFTER ID_Planning;

INSERT INTO `decisions` (`ValidationScolarite_Planning`, `ValidationResponsableUE_Planning`, `Validation_NF`) VALUES ('non', 'non', 'non');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


