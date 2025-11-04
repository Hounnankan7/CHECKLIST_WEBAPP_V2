-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307
-- Generation Time: Aug 11, 2025 at 09:52 AM
-- Server version: 5.7.24
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `checklist_dbv2`
--

-- --------------------------------------------------------

--
-- Table structure for table `commentaires`
--

CREATE TABLE `commentaires` (
  `id` int(11) NOT NULL,
  `profil_id` int(11) NOT NULL,
  `utilisateur` varchar(255) NOT NULL,
  `commentaire` text NOT NULL,
  `date_creation` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `parent_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fiche_poste_table`
--

CREATE TABLE `fiche_poste_table` (
  `id` int(11) NOT NULL,
  `categorie` varchar(100) NOT NULL,
  `intitule_poste` varchar(100) NOT NULL,
  `fiche_rnqsa` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `new_employee_table`
--

CREATE TABLE `new_employee_table` (
  `id_employe` int(11) NOT NULL,
  `user_lastname` varchar(50) NOT NULL,
  `user_firstname` varchar(20) NOT NULL,
  `nom_jeune_fille` varchar(50) NOT NULL,
  `site_embauche` varchar(100) NOT NULL,
  `intitule_poste` varchar(150) NOT NULL,
  `services` varchar(100) NOT NULL,
  `date_embauche` date NOT NULL,
  `date_naissance` date NOT NULL,
  `besoin_mail` varchar(11) NOT NULL,
  `remplace_un_ancien` varchar(11) NOT NULL,
  `nom_ancien` varchar(60) NOT NULL,
  `besoin_transfert_mail` varchar(40) NOT NULL,
  `besoin_rrf_dmd` varchar(40) NOT NULL,
  `besoin_r_learning` varchar(40) NOT NULL,
  `besoin_r_campus` varchar(40) NOT NULL,
  `besoin_dcs_net` varchar(40) NOT NULL,
  `besoin_tocken` varchar(40) NOT NULL,
  `besoin_portable_pro` varchar(40) NOT NULL,
  `besoin_new_ligne` varchar(40) NOT NULL,
  `numéro_repris` varchar(10) NOT NULL,
  `besoin_pc` varchar(40) NOT NULL,
  `type_pc` varchar(10) NOT NULL,
  `nom_pc_recupere` varchar(20) NOT NULL,
  `besoin_malette_pc` varchar(40) NOT NULL,
  `besoin_imprimante` varchar(40) NOT NULL,
  `besoin_vehicule` varchar(40) NOT NULL,
  `besoin_badge_alarme` varchar(40) NOT NULL,
  `besoin_cle_batiments` varchar(40) NOT NULL,
  `besoin_vetement` varchar(40) NOT NULL,
  `besoin_chaussure` varchar(40) NOT NULL,
  `taille_haut` varchar(5) NOT NULL,
  `commentaire_vetement` text NOT NULL,
  `taille_bas` varchar(5) NOT NULL,
  `pointure` varchar(5) NOT NULL,
  `commentaire` text NOT NULL,
  `signature_chef_service` varchar(100) NOT NULL,
  `signature_direction` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `services_table`
--

CREATE TABLE `services_table` (
  `id` int(11) NOT NULL,
  `intitule_service` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `site_embauche_table`
--

CREATE TABLE `site_embauche_table` (
  `id` int(11) NOT NULL,
  `intitule_site` varchar(100) NOT NULL,
  `pswd_default` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sorties_table`
--

CREATE TABLE `sorties_table` (
  `id_sortie` int(11) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `date_sortie` date DEFAULT NULL,
  `societe` varchar(200) NOT NULL,
  `services` varchar(100) NOT NULL,
  `contrat` varchar(100) NOT NULL,
  `fonction` varchar(200) NOT NULL,
  `type_sortie` varchar(100) NOT NULL,
  `out_mail` varchar(100) NOT NULL DEFAULT 'A verifier',
  `out_tocken` varchar(100) NOT NULL DEFAULT 'A verifier',
  `out_dcsnet` varchar(100) NOT NULL DEFAULT 'A verifier',
  `out_telephone` varchar(100) NOT NULL DEFAULT 'A verifier',
  `out_pc` varchar(100) NOT NULL DEFAULT 'A verifier',
  `out_malette` varchar(100) NOT NULL DEFAULT 'A verifier',
  `out_badge` varchar(100) NOT NULL DEFAULT 'A verifier',
  `out_cle` varchar(100) NOT NULL DEFAULT 'A verifier',
  `out_imprimante` varchar(100) NOT NULL DEFAULT 'A verifier',
  `out_vehicule` varchar(100) NOT NULL DEFAULT 'A verifier',
  `state_equipement` int(11) NOT NULL DEFAULT '0',
  `state_achat` int(11) NOT NULL DEFAULT '0',
  `state_batiment` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tasks_table`
--

CREATE TABLE `tasks_table` (
  `id_task` int(11) NOT NULL,
  `id_employe` int(11) DEFAULT NULL,
  `creation_mail` varchar(20) NOT NULL,
  `redirection_mail` varchar(20) NOT NULL,
  `creation_rrf` varchar(20) NOT NULL,
  `creation_rlearning` varchar(20) NOT NULL,
  `creation_rcampus` varchar(20) NOT NULL,
  `creation_dcsnet` varchar(20) NOT NULL,
  `creation_tocken` varchar(20) NOT NULL,
  `attribution_telephone` varchar(20) NOT NULL,
  `preaparation_pc` varchar(20) NOT NULL,
  `preparation_malette` varchar(20) NOT NULL,
  `preparation_imprimante` varchar(20) NOT NULL,
  `attribution_vehicule` varchar(20) NOT NULL,
  `attribution_badge` varchar(20) NOT NULL,
  `attribution_cles` varchar(20) NOT NULL,
  `attribution_vetements` varchar(20) NOT NULL,
  `attribution_chaussures` varchar(20) NOT NULL,
  `admin_progression` double NOT NULL,
  `rh_progression` double NOT NULL,
  `achat_progression` double NOT NULL DEFAULT '0',
  `batiment_progression` double NOT NULL DEFAULT '0',
  `progression` double NOT NULL,
  `attribue_a` varchar(100) NOT NULL,
  `nom_pc` varchar(100) NOT NULL,
  `caracteristique` text NOT NULL,
  `date_envoi` date NOT NULL,
  `date_cloture` date NOT NULL DEFAULT '2025-04-01',
  `etat_rh` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users_table`
--

CREATE TABLE `users_table` (
  `id` int(11) NOT NULL,
  `user_firstname` varchar(50) NOT NULL,
  `user_lastname` varchar(50) NOT NULL,
  `user_fullname` varchar(150) NOT NULL,
  `user_role` varchar(50) NOT NULL,
  `user_email` varchar(40) NOT NULL,
  `user_pswd` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `commentaires`
--
ALTER TABLE `commentaires`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK` (`profil_id`);

--
-- Indexes for table `fiche_poste_table`
--
ALTER TABLE `fiche_poste_table`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `fiche_rnqsa` (`fiche_rnqsa`);

--
-- Indexes for table `new_employee_table`
--
ALTER TABLE `new_employee_table`
  ADD PRIMARY KEY (`id_employe`);

--
-- Indexes for table `services_table`
--
ALTER TABLE `services_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_embauche_table`
--
ALTER TABLE `site_embauche_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sorties_table`
--
ALTER TABLE `sorties_table`
  ADD PRIMARY KEY (`id_sortie`);

--
-- Indexes for table `tasks_table`
--
ALTER TABLE `tasks_table`
  ADD PRIMARY KEY (`id_task`),
  ADD KEY `id_employe` (`id_employe`);

--
-- Indexes for table `users_table`
--
ALTER TABLE `users_table`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_role` (`user_role`,`user_email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `commentaires`
--
ALTER TABLE `commentaires`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fiche_poste_table`
--
ALTER TABLE `fiche_poste_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `new_employee_table`
--
ALTER TABLE `new_employee_table`
  MODIFY `id_employe` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services_table`
--
ALTER TABLE `services_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `site_embauche_table`
--
ALTER TABLE `site_embauche_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sorties_table`
--
ALTER TABLE `sorties_table`
  MODIFY `id_sortie` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tasks_table`
--
ALTER TABLE `tasks_table`
  MODIFY `id_task` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users_table`
--
ALTER TABLE `users_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `commentaires`
--
ALTER TABLE `commentaires`
  ADD CONSTRAINT `FK` FOREIGN KEY (`profil_id`) REFERENCES `tasks_table` (`id_task`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
