<?php
/**
 * Modèle pour la gestion des prescriptions d'examens.
 * Gère les interactions avec la table PrescrireExamen et les tables associées.
 */

class PrescrireExamen {
    // Variable pour la connexion à la base de données.
    private mysqli $db;

    /**
     * Constructeur avec injection de la connexion à la base de données.
     *
     * @param mysqli $db La connexion à la base de données de type mysqli.
     */
    public function __construct(mysqli $db) {
        $this->db = $db;
    }

    /**
     * Récupère toutes les prescriptions avec les noms du médecin, de l'examen et du patient.
     *
     * @return array|false Un tableau de prescriptions ou false en cas d'erreur.
     */
    public function getAll(): array|false {
        $query = "SELECT 
                    p.IdPrescrireExamen,
                    p.IdMedecin,
                    p.IdExamen,
                    p.IdPatient,
                    p.DatePrescription,
                    p.Commentaires,
                    m.Nom AS NomMedecin, m.PostNom as PostNomMedecin, m.Prenom as PrenomMedecin,
                    e.NomExamen,
                    pat.Nom AS NomPatient ,pat.PostNom as PostNomPatient, pat.Prenom as PrenomPatient
                  FROM PrescrireExamen p
                  JOIN Medecin m ON p.IdMedecin = m.IdMedecin
                  JOIN Examen e ON p.IdExamen = e.IdExamen
                  JOIN Patient pat ON p.IdPatient = pat.IdPatient";

        $result = $this->db->query($query);
        if ($result) {
            $prescriptions = $result->fetch_all(MYSQLI_ASSOC);
            $result->free_result();
            return $prescriptions;
        } else {
            return false;
        }
    }

    /**
     * Crée une nouvelle prescription.
     *
     * @param array $data Un tableau associatif des données de la prescription.
     * @return bool Vrai si la création a réussi, faux sinon.
     */
    public function create(array $data): bool {
        $query = "INSERT INTO PrescrireExamen (IdMedecin, IdExamen, IdPatient, DatePrescription, Commentaires)
                  VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($query);
        if ($stmt) {
            $stmt->bind_param(
                "iiiss",
                $data['IdMedecin'],
                $data['IdExamen'],
                $data['IdPatient'],
                $data['DatePrescription'],
                $data['Commentaires']
            );
            $success = $stmt->execute();
            $stmt->close();
            return $success;
        }
        return false;
    }

    /**
     * Récupère une prescription par son identifiant.
     *
     * @param int $id L'ID de la prescription.
     * @return array|false Un tableau associatif de la prescription ou false si non trouvée.
     */
    public function getOne(int $id): array|false {
        $query = "SELECT * FROM PrescrireExamen WHERE IdPrescrireExamen = ?";
        $stmt = $this->db->prepare($query);
        if ($stmt) {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $prescription = $result->fetch_assoc();
            $stmt->close();
            return $prescription;
        }
        return false;
    }

    /**
     * Met à jour une prescription existante.
     *
     * @param int $id L'ID de la prescription.
     * @param array $data Les nouvelles données de la prescription.
     * @return bool Vrai si la mise à jour a réussi, faux sinon.
     */
    public function update(int $id, array $data): bool {
        $query = "UPDATE PrescrireExamen 
                  SET IdMedecin = ?, IdExamen = ?, IdPatient = ?, DatePrescription = ?, Commentaires = ?
                  WHERE IdPrescrireExamen = ?";
        
        $stmt = $this->db->prepare($query);
        if ($stmt) {
            $stmt->bind_param(
                "iiissi",
                $data['IdMedecin'],
                $data['IdExamen'],
                $data['IdPatient'],
                $data['DatePrescription'],
                $data['Commentaires'],
                $id
            );
            $success = $stmt->execute();
            $stmt->close();
            return $success;
        }
        return false;
    }

    /**
     * Supprime une prescription.
     *
     * @param int $id L'ID de la prescription à supprimer.
     * @return bool Vrai si la suppression a réussi, faux sinon.
     */
    public function delete(int $id): bool {
        $query = "DELETE FROM PrescrireExamen WHERE IdPrescrireExamen = ?";
        $stmt = $this->db->prepare($query);
        if ($stmt) {
            $stmt->bind_param("i", $id);
            $success = $stmt->execute();
            $stmt->close();
            return $success;
        }
        return false;
    }
    
    /**
     * Récupère la liste de tous les patients.
     *
     * @return array Un tableau de patients.
     */
    public function getAllPatients(): array {
        $query = "SELECT IdPatient, Nom , PostNom, Prenom FROM Patient";
        $result = $this->db->query($query);
        $patients = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $patients[] = $row;
            }
            $result->free();
        }
        return $patients;
    }
    
    /**
     * Récupère la liste de tous les examens.
     *
     * @return array Un tableau d'examens.
     */
    public function getAllExamens(): array {
        $query = "SELECT IdExamen, NomExamen FROM Examen";
        $result = $this->db->query($query);
        $examens = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $examens[] = $row;
            }
            $result->free();
        }
        return $examens;
    }
}
