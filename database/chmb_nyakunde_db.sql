DROP DATABASE IF EXISTS chmb_nyakunde_db;
CREATE DATABASE IF NOT EXISTS chmb_nyakunde_db;
USE chmb_nyakunde_db;

-- Table Examen
CREATE TABLE Examen (
    IdExamen INT AUTO_INCREMENT PRIMARY KEY,
    NomExamen VARCHAR(100) NOT NULL,
    Description TEXT,
    Cout DECIMAL(10,2) DEFAULT 0
);

-- Table Medecin
CREATE TABLE Medecin (
    IdMedecin INT AUTO_INCREMENT PRIMARY KEY,
    Nom VARCHAR(100) NOT NULL,
    PostNom VARCHAR(100) NOT NULL,
    Prenom VARCHAR(100) NOT NULL,
    Specialite VARCHAR(100),
    Telephone VARCHAR(20),
    Email VARCHAR(100),
    Adresse VARCHAR(255),
    NumLicence VARCHAR(50)
);

-- Table Laborantin
CREATE TABLE Laborantin (
    IdLaborantin INT AUTO_INCREMENT PRIMARY KEY,
    Nom VARCHAR(100) NOT NULL,
    PostNom VARCHAR(100) NOT NULL,
    Prenom VARCHAR(100) NOT NULL,
    Telephone VARCHAR(20),
    Email VARCHAR(100),
    Adresse VARCHAR(255)
);

-- Table Patient
CREATE TABLE Patient (
    IdPatient INT AUTO_INCREMENT PRIMARY KEY,
    Nom VARCHAR(100) NOT NULL,
    PostNom VARCHAR(100) NOT NULL,
    Prenom VARCHAR(100) NOT NULL,
    DateNaissance DATE,
    Sexe ENUM('M','F'),
    Adresse VARCHAR(255),
    Telephone VARCHAR(20),
    Email VARCHAR(100),
    NumAssurance VARCHAR(50),
    GroupeSanguin VARCHAR(5)
);

-- Table Medicament
CREATE TABLE Medicament (
    IdMedicament INT AUTO_INCREMENT PRIMARY KEY,
    NomMedicament VARCHAR(100) NOT NULL,
    Description TEXT,
    DosageStandard VARCHAR(100),
    EffetsSecondaires TEXT,
    Prix DECIMAL(10,2) DEFAULT 0
);

-- Table Infirmier
CREATE TABLE Infirmier (
    IdInfirmier INT AUTO_INCREMENT PRIMARY KEY,
    Nom VARCHAR(100) NOT NULL,
    PostNom VARCHAR(100) NOT NULL,
    Prenom VARCHAR(100) NOT NULL,
    Telephone VARCHAR(20),
    Email VARCHAR(100),
    Adresse VARCHAR(255)
);

-- Table Chambre
CREATE TABLE Chambre (
    IdChambre INT AUTO_INCREMENT PRIMARY KEY,
    Numero VARCHAR(50) NOT NULL,
    Type ENUM('simple','double','suite') DEFAULT 'simple',
    Etat ENUM('disponible','occupee','maintenance') DEFAULT 'disponible',
    PrixParJour DECIMAL(10,2) DEFAULT 0
);

-- Table Paiement
CREATE TABLE Paiement (
    IdPaiement INT AUTO_INCREMENT PRIMARY KEY,
    Montant DECIMAL(10,2) NOT NULL,
    DatePaiement DATE NOT NULL,
    ModePaiement ENUM('especes','carte','assurance') NOT NULL,
    IdPatient INT NOT NULL,
    Description TEXT,
    FOREIGN KEY (IdPatient) REFERENCES Patient(IdPatient)
);

-- Table Traitement
CREATE TABLE Traitement (
    IdTraitement INT AUTO_INCREMENT PRIMARY KEY,
    Description TEXT,
    DateDebut DATE,
    DateFin DATE,
    IdPatient INT NOT NULL,
    IdMedecin INT NOT NULL,
    FOREIGN KEY (IdPatient) REFERENCES Patient(IdPatient),
    FOREIGN KEY (IdMedecin) REFERENCES Medecin(IdMedecin)
);

-- Table Categorie
CREATE TABLE Categorie (
    IdCategorie INT AUTO_INCREMENT PRIMARY KEY,
    NomCategorie VARCHAR(100) NOT NULL,
    Description TEXT
);

-- Table RendezVous
CREATE TABLE RendezVous (
    IdRendezVous INT AUTO_INCREMENT PRIMARY KEY,
    DateHeure DATETIME NOT NULL,
    IdPatient INT NOT NULL,
    IdMedecin INT NOT NULL,
    Objet VARCHAR(255),
    Statut ENUM('confirme','annule','en_attente') DEFAULT 'en_attente',
    FOREIGN KEY (IdPatient) REFERENCES Patient(IdPatient),
    FOREIGN KEY (IdMedecin) REFERENCES Medecin(IdMedecin)
);

-- Association PrescrireExamen (Médecin, Examen, Patient)
CREATE TABLE PrescrireExamen (
    IdPrescrireExamen INT AUTO_INCREMENT PRIMARY KEY,
    IdMedecin INT NOT NULL,
    IdExamen INT NOT NULL,
    IdPatient INT NOT NULL,
    DatePrescription DATE NOT NULL,
    Commentaires TEXT,
    FOREIGN KEY (IdMedecin) REFERENCES Medecin(IdMedecin),
    FOREIGN KEY (IdExamen) REFERENCES Examen(IdExamen),
    FOREIGN KEY (IdPatient) REFERENCES Patient(IdPatient)
);

-- Association Consulter (Médecin, Patient)
CREATE TABLE Consulter (
    IdConsulter INT AUTO_INCREMENT PRIMARY KEY,
    IdMedecin INT NOT NULL,
    IdPatient INT NOT NULL,
    DateConsultation DATE NOT NULL,
    SignesVitaux TEXT,
    Diagnostic TEXT,
    FOREIGN KEY (IdMedecin) REFERENCES Medecin(IdMedecin),
    FOREIGN KEY (IdPatient) REFERENCES Patient(IdPatient)
);

-- Association PrescrireMedicament (Médecin, Medicament, Patient)
CREATE TABLE PrescrireMedicament (
    IdPrescrireMedicament INT AUTO_INCREMENT PRIMARY KEY,
    IdMedecin INT NOT NULL,
    IdMedicament INT NOT NULL,
    IdPatient INT NOT NULL,
    DatePrescription DATE NOT NULL,
    Dosage VARCHAR(100),
    Duree VARCHAR(100),
    FOREIGN KEY (IdMedecin) REFERENCES Medecin(IdMedecin),
    FOREIGN KEY (IdMedicament) REFERENCES Medicament(IdMedicament),
    FOREIGN KEY (IdPatient) REFERENCES Patient(IdPatient)
);

-- Association Examiner (Laborantin, Examen, Patient)
CREATE TABLE Examiner (
    IdExaminer INT AUTO_INCREMENT PRIMARY KEY,
    IdLaborantin INT NOT NULL,
    IdExamen INT NOT NULL,
    IdPatient INT NOT NULL,
    DateExamen DATE NOT NULL,
    Resultat TEXT,
    Remarques TEXT,
    FOREIGN KEY (IdLaborantin) REFERENCES Laborantin(IdLaborantin),
    FOREIGN KEY (IdExamen) REFERENCES Examen(IdExamen),
    FOREIGN KEY (IdPatient) REFERENCES Patient(IdPatient)
);

-- Association Preconsulter (Infirmier, Patient)
CREATE TABLE Preconsulter (
    IdPreconsulter INT AUTO_INCREMENT PRIMARY KEY,
    IdInfirmier INT NOT NULL,
    IdPatient INT NOT NULL,
    Date DATE NOT NULL,
    Observations TEXT,
    FOREIGN KEY (IdInfirmier) REFERENCES Infirmier(IdInfirmier),
    FOREIGN KEY (IdPatient) REFERENCES Patient(IdPatient)
);

-- Association Hospitaliser (Patient, Chambre)
CREATE TABLE Hospitaliser (
    IdHospitaliser INT AUTO_INCREMENT PRIMARY KEY,
    IdPatient INT NOT NULL,
    IdChambre INT NOT NULL,
    DateEntree DATE NOT NULL,
    DateSortie DATE,
    MotifHospitalisation TEXT,
    FOREIGN KEY (IdPatient) REFERENCES Patient(IdPatient),
    FOREIGN KEY (IdChambre) REFERENCES Chambre(IdChambre)
);

-- Association Effectuer (Paiement, Patient)
CREATE TABLE Effectuer (
    IdEffectuer INT AUTO_INCREMENT PRIMARY KEY,
    IdPaiement INT NOT NULL,
    IdPatient INT NOT NULL,
    DateEffectuation DATE NOT NULL,
    Montant DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (IdPaiement) REFERENCES Paiement(IdPaiement),
    FOREIGN KEY (IdPatient) REFERENCES Patient(IdPatient)
);

-- Association Administrer (Infirmier, Traitement)
CREATE TABLE Administrer (
    IdAdministrer INT AUTO_INCREMENT PRIMARY KEY,
    IdInfirmier INT NOT NULL,
    IdTraitement INT NOT NULL,
    DateAdministration DATE NOT NULL,
    Observations TEXT,
    FOREIGN KEY (IdInfirmier) REFERENCES Infirmier(IdInfirmier),
    FOREIGN KEY (IdTraitement) REFERENCES Traitement(IdTraitement)
);

-- Association Concerner (Categorie, Medicament)
CREATE TABLE Concerner (
    IdConcerner INT AUTO_INCREMENT PRIMARY KEY,
    IdCategorie INT NOT NULL,
    IdMedicament INT NOT NULL,
    FOREIGN KEY (IdCategorie) REFERENCES Categorie(IdCategorie),
    FOREIGN KEY (IdMedicament) REFERENCES Medicament(IdMedicament)
);
