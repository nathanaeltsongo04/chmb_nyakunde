-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 25 août 2025 à 10:24
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `chmb_nyakunde_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `administrer`
--

CREATE TABLE `administrer` (
  `IdAdministrer` int(11) NOT NULL,
  `IdInfirmier` int(11) NOT NULL,
  `IdTraitement` int(11) NOT NULL,
  `DateAdministration` date NOT NULL,
  `Observations` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE `categorie` (
  `IdCategorie` int(11) NOT NULL,
  `NomCategorie` varchar(100) NOT NULL,
  `Description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `chambre`
--

CREATE TABLE `chambre` (
  `IdChambre` int(11) NOT NULL,
  `Numero` varchar(50) NOT NULL,
  `Type` enum('simple','double','suite') DEFAULT 'simple',
  `Etat` enum('disponible','occupee','maintenance') DEFAULT 'disponible',
  `PrixParJour` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `chambre`
--

INSERT INTO `chambre` (`IdChambre`, `Numero`, `Type`, `Etat`, `PrixParJour`) VALUES
(1, 'A01', 'simple', 'disponible', 10.00);

-- --------------------------------------------------------

--
-- Structure de la table `concerner`
--

CREATE TABLE `concerner` (
  `IdConcerner` int(11) NOT NULL,
  `IdCategorie` int(11) NOT NULL,
  `IdMedicament` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `consulter`
--

CREATE TABLE `consulter` (
  `IdConsulter` int(11) NOT NULL,
  `IdMedecin` int(11) NOT NULL,
  `IdPatient` int(11) NOT NULL,
  `DateConsultation` date NOT NULL,
  `SignesVitaux` text DEFAULT NULL,
  `Diagnostic` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `consulter`
--

INSERT INTO `consulter` (`IdConsulter`, `IdMedecin`, `IdPatient`, `DateConsultation`, `SignesVitaux`, `Diagnostic`) VALUES
(1, 1, 1, '2025-08-24', 'ok', 'vu');

-- --------------------------------------------------------

--
-- Structure de la table `effectuer`
--

CREATE TABLE `effectuer` (
  `IdEffectuer` int(11) NOT NULL,
  `IdPaiement` int(11) NOT NULL,
  `IdPatient` int(11) NOT NULL,
  `DateEffectuation` date NOT NULL,
  `Montant` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `examen`
--

CREATE TABLE `examen` (
  `IdExamen` int(11) NOT NULL,
  `NomExamen` varchar(100) NOT NULL,
  `Description` text DEFAULT NULL,
  `Cout` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `examen`
--

INSERT INTO `examen` (`IdExamen`, `NomExamen`, `Description`, `Cout`) VALUES
(1, 'Glycemie', 'Taux de sucre', 10.00),
(2, 'VIH/SIDA', 'Teste de VIH/SIDA', 2.00);

-- --------------------------------------------------------

--
-- Structure de la table `examiner`
--

CREATE TABLE `examiner` (
  `IdExaminer` int(11) NOT NULL,
  `IdLaborantin` int(11) NOT NULL,
  `IdExamen` int(11) NOT NULL,
  `IdPatient` int(11) NOT NULL,
  `DateExamen` date NOT NULL,
  `Resultat` text DEFAULT NULL,
  `Remarques` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `hospitaliser`
--

CREATE TABLE `hospitaliser` (
  `IdHospitaliser` int(11) NOT NULL,
  `IdPatient` int(11) NOT NULL,
  `IdChambre` int(11) NOT NULL,
  `DateEntree` date NOT NULL,
  `DateSortie` date DEFAULT NULL,
  `MotifHospitalisation` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `hospitaliser`
--

INSERT INTO `hospitaliser` (`IdHospitaliser`, `IdPatient`, `IdChambre`, `DateEntree`, `DateSortie`, `MotifHospitalisation`) VALUES
(1, 1, 1, '2025-08-25', '2025-08-31', 'Malaria');

-- --------------------------------------------------------

--
-- Structure de la table `infirmier`
--

CREATE TABLE `infirmier` (
  `IdInfirmier` int(11) NOT NULL,
  `Matricule` varchar(50) NOT NULL,
  `Nom` varchar(100) NOT NULL,
  `PostNom` varchar(100) NOT NULL,
  `Prenom` varchar(100) NOT NULL,
  `Telephone` varchar(20) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Adresse` varchar(255) DEFAULT NULL,
  `Specialite` varchar(255) DEFAULT NULL,
  `NumLicence` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `infirmier`
--

INSERT INTO `infirmier` (`IdInfirmier`, `Matricule`, `Nom`, `PostNom`, `Prenom`, `Telephone`, `Email`, `Adresse`, `Specialite`, `NumLicence`) VALUES
(1, '', 'Kahindo', 'Tsongo', 'Laurence', '0995247814', 'laure@gmail.com', 'Kalinda', 'Anestesiste', '012'),
(2, 'INF68ab99126eca4', 'Musavuli', 'Tsongo', 'Ronald', '0995247814', 'musa@gmail.com', 'kalinda', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `laborantin`
--

CREATE TABLE `laborantin` (
  `IdLaborantin` int(11) NOT NULL,
  `Matricule` varchar(50) NOT NULL,
  `Nom` varchar(100) NOT NULL,
  `PostNom` varchar(100) NOT NULL,
  `Prenom` varchar(100) NOT NULL,
  `Telephone` varchar(20) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Adresse` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `laborantin`
--

INSERT INTO `laborantin` (`IdLaborantin`, `Matricule`, `Nom`, `PostNom`, `Prenom`, `Telephone`, `Email`, `Adresse`) VALUES
(1, 'LAB1755987417', 'Kahindo', 'Tsongo', 'Laurence', '0995247814', 'laure@gmail.com', 'kalinda');

-- --------------------------------------------------------

--
-- Structure de la table `medecin`
--

CREATE TABLE `medecin` (
  `IdMedecin` int(11) NOT NULL,
  `Matricule` varchar(50) NOT NULL,
  `Nom` varchar(100) NOT NULL,
  `PostNom` varchar(100) NOT NULL,
  `Prenom` varchar(100) NOT NULL,
  `Specialite` varchar(100) DEFAULT NULL,
  `Telephone` varchar(20) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Adresse` varchar(255) DEFAULT NULL,
  `NumLicence` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `medecin`
--

INSERT INTO `medecin` (`IdMedecin`, `Matricule`, `Nom`, `PostNom`, `Prenom`, `Specialite`, `Telephone`, `Email`, `Adresse`, `NumLicence`) VALUES
(1, 'MED1755866848', 'Mumbere', 'Tsongo', 'Nathanael', 'Pédiatrie', NULL, NULL, NULL, NULL),
(2, 'MED1755979818', 'Kambale', 'Tsongo', 'Ronald', 'Anestesiste', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `medicament`
--

CREATE TABLE `medicament` (
  `IdMedicament` int(11) NOT NULL,
  `NomMedicament` varchar(100) NOT NULL,
  `Description` text DEFAULT NULL,
  `DosageStandard` varchar(100) DEFAULT NULL,
  `EffetsSecondaires` text DEFAULT NULL,
  `Prix` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `paiement`
--

CREATE TABLE `paiement` (
  `IdPaiement` int(11) NOT NULL,
  `Montant` decimal(10,2) NOT NULL,
  `DatePaiement` date NOT NULL,
  `ModePaiement` enum('especes','carte','assurance') NOT NULL,
  `IdPatient` int(11) NOT NULL,
  `Description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `paiement`
--

INSERT INTO `paiement` (`IdPaiement`, `Montant`, `DatePaiement`, `ModePaiement`, `IdPatient`, `Description`) VALUES
(1, 20.00, '2025-08-25', 'especes', 1, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `patient`
--

CREATE TABLE `patient` (
  `IdPatient` int(11) NOT NULL,
  `Nom` varchar(100) NOT NULL,
  `PostNom` varchar(100) NOT NULL,
  `Prenom` varchar(100) NOT NULL,
  `DateNaissance` date DEFAULT NULL,
  `Sexe` enum('M','F') DEFAULT NULL,
  `Adresse` varchar(255) DEFAULT NULL,
  `Telephone` varchar(20) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `NumAssurance` varchar(50) DEFAULT NULL,
  `GroupeSanguin` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `patient`
--

INSERT INTO `patient` (`IdPatient`, `Nom`, `PostNom`, `Prenom`, `DateNaissance`, `Sexe`, `Adresse`, `Telephone`, `Email`, `NumAssurance`, `GroupeSanguin`) VALUES
(1, 'Mumbere', 'Tsongo', 'Nathanael', '2000-10-04', 'M', 'Kalinda', '0995247814', 'nathan@gmail.com', '02', 'o+');

-- --------------------------------------------------------

--
-- Structure de la table `preconsulter`
--

CREATE TABLE `preconsulter` (
  `IdPreconsulter` int(11) NOT NULL,
  `IdInfirmier` int(11) NOT NULL,
  `IdPatient` int(11) NOT NULL,
  `Date` date NOT NULL,
  `Observations` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `prescrireexamen`
--

CREATE TABLE `prescrireexamen` (
  `IdPrescrireExamen` int(11) NOT NULL,
  `IdMedecin` int(11) NOT NULL,
  `IdExamen` int(11) NOT NULL,
  `IdPatient` int(11) NOT NULL,
  `DatePrescription` date NOT NULL,
  `Commentaires` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `prescrireexamen`
--

INSERT INTO `prescrireexamen` (`IdPrescrireExamen`, `IdMedecin`, `IdExamen`, `IdPatient`, `DatePrescription`, `Commentaires`) VALUES
(1, 1, 1, 1, '2025-08-25', 'g'),
(2, 1, 1, 1, '2025-08-30', 'S');

-- --------------------------------------------------------

--
-- Structure de la table `prescriremedicament`
--

CREATE TABLE `prescriremedicament` (
  `IdPrescrireMedicament` int(11) NOT NULL,
  `IdMedecin` int(11) NOT NULL,
  `IdMedicament` int(11) NOT NULL,
  `IdPatient` int(11) NOT NULL,
  `DatePrescription` date NOT NULL,
  `Dosage` varchar(100) DEFAULT NULL,
  `Duree` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rendezvous`
--

CREATE TABLE `rendezvous` (
  `IdRendezVous` int(11) NOT NULL,
  `DateHeure` datetime NOT NULL,
  `IdPatient` int(11) NOT NULL,
  `IdMedecin` int(11) NOT NULL,
  `Objet` varchar(255) DEFAULT NULL,
  `Statut` enum('confirme','annule','en_attente') DEFAULT 'en_attente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `traitement`
--

CREATE TABLE `traitement` (
  `IdTraitement` int(11) NOT NULL,
  `Description` text DEFAULT NULL,
  `DateDebut` date DEFAULT NULL,
  `DateFin` date DEFAULT NULL,
  `IdPatient` int(11) NOT NULL,
  `IdMedecin` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `IdUser` int(11) NOT NULL,
  `Matricule` varchar(50) NOT NULL,
  `Username` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Role` enum('super_admin','admin','medecin','infirmier','laborantin','caissier') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`IdUser`, `Matricule`, `Username`, `Password`, `Role`) VALUES
(1, 'MED1755866848', 'Nathan', '$2y$10$aoa84B6aaXX.Dj2p45F8LO.JXGFrJQnP4BK570l7MtSxUDb4SIU26', 'medecin'),
(2, 'LAB1755987417', 'laurence', '$2y$10$YLTr7qsdRu8EzsfjagSV7un07v2EmnXfUZvqEsaX1wVKTBNyeuUCO', 'laborantin');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `administrer`
--
ALTER TABLE `administrer`
  ADD PRIMARY KEY (`IdAdministrer`),
  ADD KEY `IdInfirmier` (`IdInfirmier`),
  ADD KEY `IdTraitement` (`IdTraitement`);

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`IdCategorie`);

--
-- Index pour la table `chambre`
--
ALTER TABLE `chambre`
  ADD PRIMARY KEY (`IdChambre`);

--
-- Index pour la table `concerner`
--
ALTER TABLE `concerner`
  ADD PRIMARY KEY (`IdConcerner`),
  ADD KEY `IdCategorie` (`IdCategorie`),
  ADD KEY `IdMedicament` (`IdMedicament`);

--
-- Index pour la table `consulter`
--
ALTER TABLE `consulter`
  ADD PRIMARY KEY (`IdConsulter`),
  ADD KEY `IdMedecin` (`IdMedecin`),
  ADD KEY `IdPatient` (`IdPatient`);

--
-- Index pour la table `effectuer`
--
ALTER TABLE `effectuer`
  ADD PRIMARY KEY (`IdEffectuer`),
  ADD KEY `IdPaiement` (`IdPaiement`),
  ADD KEY `IdPatient` (`IdPatient`);

--
-- Index pour la table `examen`
--
ALTER TABLE `examen`
  ADD PRIMARY KEY (`IdExamen`);

--
-- Index pour la table `examiner`
--
ALTER TABLE `examiner`
  ADD PRIMARY KEY (`IdExaminer`),
  ADD KEY `IdLaborantin` (`IdLaborantin`),
  ADD KEY `IdExamen` (`IdExamen`),
  ADD KEY `IdPatient` (`IdPatient`);

--
-- Index pour la table `hospitaliser`
--
ALTER TABLE `hospitaliser`
  ADD PRIMARY KEY (`IdHospitaliser`),
  ADD KEY `IdPatient` (`IdPatient`),
  ADD KEY `IdChambre` (`IdChambre`);

--
-- Index pour la table `infirmier`
--
ALTER TABLE `infirmier`
  ADD PRIMARY KEY (`IdInfirmier`),
  ADD UNIQUE KEY `Matricule` (`Matricule`);

--
-- Index pour la table `laborantin`
--
ALTER TABLE `laborantin`
  ADD PRIMARY KEY (`IdLaborantin`),
  ADD UNIQUE KEY `Matricule` (`Matricule`);

--
-- Index pour la table `medecin`
--
ALTER TABLE `medecin`
  ADD PRIMARY KEY (`IdMedecin`),
  ADD UNIQUE KEY `Matricule` (`Matricule`);

--
-- Index pour la table `medicament`
--
ALTER TABLE `medicament`
  ADD PRIMARY KEY (`IdMedicament`);

--
-- Index pour la table `paiement`
--
ALTER TABLE `paiement`
  ADD PRIMARY KEY (`IdPaiement`),
  ADD KEY `IdPatient` (`IdPatient`);

--
-- Index pour la table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`IdPatient`);

--
-- Index pour la table `preconsulter`
--
ALTER TABLE `preconsulter`
  ADD PRIMARY KEY (`IdPreconsulter`),
  ADD KEY `IdInfirmier` (`IdInfirmier`),
  ADD KEY `IdPatient` (`IdPatient`);

--
-- Index pour la table `prescrireexamen`
--
ALTER TABLE `prescrireexamen`
  ADD PRIMARY KEY (`IdPrescrireExamen`),
  ADD KEY `IdMedecin` (`IdMedecin`),
  ADD KEY `IdExamen` (`IdExamen`),
  ADD KEY `IdPatient` (`IdPatient`);

--
-- Index pour la table `prescriremedicament`
--
ALTER TABLE `prescriremedicament`
  ADD PRIMARY KEY (`IdPrescrireMedicament`),
  ADD KEY `IdMedecin` (`IdMedecin`),
  ADD KEY `IdMedicament` (`IdMedicament`),
  ADD KEY `IdPatient` (`IdPatient`);

--
-- Index pour la table `rendezvous`
--
ALTER TABLE `rendezvous`
  ADD PRIMARY KEY (`IdRendezVous`),
  ADD KEY `IdPatient` (`IdPatient`),
  ADD KEY `IdMedecin` (`IdMedecin`);

--
-- Index pour la table `traitement`
--
ALTER TABLE `traitement`
  ADD PRIMARY KEY (`IdTraitement`),
  ADD KEY `IdPatient` (`IdPatient`),
  ADD KEY `IdMedecin` (`IdMedecin`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`IdUser`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `administrer`
--
ALTER TABLE `administrer`
  MODIFY `IdAdministrer` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `IdCategorie` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `chambre`
--
ALTER TABLE `chambre`
  MODIFY `IdChambre` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `concerner`
--
ALTER TABLE `concerner`
  MODIFY `IdConcerner` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `consulter`
--
ALTER TABLE `consulter`
  MODIFY `IdConsulter` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `effectuer`
--
ALTER TABLE `effectuer`
  MODIFY `IdEffectuer` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `examen`
--
ALTER TABLE `examen`
  MODIFY `IdExamen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `examiner`
--
ALTER TABLE `examiner`
  MODIFY `IdExaminer` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `hospitaliser`
--
ALTER TABLE `hospitaliser`
  MODIFY `IdHospitaliser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `infirmier`
--
ALTER TABLE `infirmier`
  MODIFY `IdInfirmier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `laborantin`
--
ALTER TABLE `laborantin`
  MODIFY `IdLaborantin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `medecin`
--
ALTER TABLE `medecin`
  MODIFY `IdMedecin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `medicament`
--
ALTER TABLE `medicament`
  MODIFY `IdMedicament` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `paiement`
--
ALTER TABLE `paiement`
  MODIFY `IdPaiement` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `patient`
--
ALTER TABLE `patient`
  MODIFY `IdPatient` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `preconsulter`
--
ALTER TABLE `preconsulter`
  MODIFY `IdPreconsulter` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `prescrireexamen`
--
ALTER TABLE `prescrireexamen`
  MODIFY `IdPrescrireExamen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `prescriremedicament`
--
ALTER TABLE `prescriremedicament`
  MODIFY `IdPrescrireMedicament` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `rendezvous`
--
ALTER TABLE `rendezvous`
  MODIFY `IdRendezVous` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `traitement`
--
ALTER TABLE `traitement`
  MODIFY `IdTraitement` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `IdUser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `administrer`
--
ALTER TABLE `administrer`
  ADD CONSTRAINT `administrer_ibfk_1` FOREIGN KEY (`IdInfirmier`) REFERENCES `infirmier` (`IdInfirmier`),
  ADD CONSTRAINT `administrer_ibfk_2` FOREIGN KEY (`IdTraitement`) REFERENCES `traitement` (`IdTraitement`);

--
-- Contraintes pour la table `concerner`
--
ALTER TABLE `concerner`
  ADD CONSTRAINT `concerner_ibfk_1` FOREIGN KEY (`IdCategorie`) REFERENCES `categorie` (`IdCategorie`),
  ADD CONSTRAINT `concerner_ibfk_2` FOREIGN KEY (`IdMedicament`) REFERENCES `medicament` (`IdMedicament`);

--
-- Contraintes pour la table `consulter`
--
ALTER TABLE `consulter`
  ADD CONSTRAINT `consulter_ibfk_1` FOREIGN KEY (`IdMedecin`) REFERENCES `medecin` (`IdMedecin`),
  ADD CONSTRAINT `consulter_ibfk_2` FOREIGN KEY (`IdPatient`) REFERENCES `patient` (`IdPatient`);

--
-- Contraintes pour la table `effectuer`
--
ALTER TABLE `effectuer`
  ADD CONSTRAINT `effectuer_ibfk_1` FOREIGN KEY (`IdPaiement`) REFERENCES `paiement` (`IdPaiement`),
  ADD CONSTRAINT `effectuer_ibfk_2` FOREIGN KEY (`IdPatient`) REFERENCES `patient` (`IdPatient`);

--
-- Contraintes pour la table `examiner`
--
ALTER TABLE `examiner`
  ADD CONSTRAINT `examiner_ibfk_1` FOREIGN KEY (`IdLaborantin`) REFERENCES `laborantin` (`IdLaborantin`),
  ADD CONSTRAINT `examiner_ibfk_2` FOREIGN KEY (`IdExamen`) REFERENCES `examen` (`IdExamen`),
  ADD CONSTRAINT `examiner_ibfk_3` FOREIGN KEY (`IdPatient`) REFERENCES `patient` (`IdPatient`);

--
-- Contraintes pour la table `hospitaliser`
--
ALTER TABLE `hospitaliser`
  ADD CONSTRAINT `hospitaliser_ibfk_1` FOREIGN KEY (`IdPatient`) REFERENCES `patient` (`IdPatient`),
  ADD CONSTRAINT `hospitaliser_ibfk_2` FOREIGN KEY (`IdChambre`) REFERENCES `chambre` (`IdChambre`);

--
-- Contraintes pour la table `paiement`
--
ALTER TABLE `paiement`
  ADD CONSTRAINT `paiement_ibfk_1` FOREIGN KEY (`IdPatient`) REFERENCES `patient` (`IdPatient`);

--
-- Contraintes pour la table `preconsulter`
--
ALTER TABLE `preconsulter`
  ADD CONSTRAINT `preconsulter_ibfk_1` FOREIGN KEY (`IdInfirmier`) REFERENCES `infirmier` (`IdInfirmier`),
  ADD CONSTRAINT `preconsulter_ibfk_2` FOREIGN KEY (`IdPatient`) REFERENCES `patient` (`IdPatient`);

--
-- Contraintes pour la table `prescrireexamen`
--
ALTER TABLE `prescrireexamen`
  ADD CONSTRAINT `prescrireexamen_ibfk_1` FOREIGN KEY (`IdMedecin`) REFERENCES `medecin` (`IdMedecin`),
  ADD CONSTRAINT `prescrireexamen_ibfk_2` FOREIGN KEY (`IdExamen`) REFERENCES `examen` (`IdExamen`),
  ADD CONSTRAINT `prescrireexamen_ibfk_3` FOREIGN KEY (`IdPatient`) REFERENCES `patient` (`IdPatient`);

--
-- Contraintes pour la table `prescriremedicament`
--
ALTER TABLE `prescriremedicament`
  ADD CONSTRAINT `prescriremedicament_ibfk_1` FOREIGN KEY (`IdMedecin`) REFERENCES `medecin` (`IdMedecin`),
  ADD CONSTRAINT `prescriremedicament_ibfk_2` FOREIGN KEY (`IdMedicament`) REFERENCES `medicament` (`IdMedicament`),
  ADD CONSTRAINT `prescriremedicament_ibfk_3` FOREIGN KEY (`IdPatient`) REFERENCES `patient` (`IdPatient`);

--
-- Contraintes pour la table `rendezvous`
--
ALTER TABLE `rendezvous`
  ADD CONSTRAINT `rendezvous_ibfk_1` FOREIGN KEY (`IdPatient`) REFERENCES `patient` (`IdPatient`),
  ADD CONSTRAINT `rendezvous_ibfk_2` FOREIGN KEY (`IdMedecin`) REFERENCES `medecin` (`IdMedecin`);

--
-- Contraintes pour la table `traitement`
--
ALTER TABLE `traitement`
  ADD CONSTRAINT `traitement_ibfk_1` FOREIGN KEY (`IdPatient`) REFERENCES `patient` (`IdPatient`),
  ADD CONSTRAINT `traitement_ibfk_2` FOREIGN KEY (`IdMedecin`) REFERENCES `medecin` (`IdMedecin`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
