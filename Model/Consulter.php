<?php
/**
 * Modèle pour la gestion des consultations.
 * Gère les interactions avec la table Consulter et les tables associées.
 */

class Consulter {
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
     * Récupère toutes les consultations avec les noms du médecin et du patient.
     *
     * @return array|false Un tableau de consultations ou false en cas d'erreur.
     */
    public function getAll(): array|false {
        $query = "SELECT 
                    c.IdConsulter,
                    c.IdMedecin,
                    c.IdPatient,
                    c.DateConsultation,
                    c.SignesVitaux,
                    c.Diagnostic,
                    m.Nom AS NomMedecin,
                    p.Nom AS NomPatient
                  FROM Consulter c
                  JOIN Medecin m ON c.IdMedecin = m.IdMedecin
                  JOIN Patient p ON c.IdPatient = p.IdPatient";

        $result = $this->db->query($query);
        if ($result) {
            $consultations = $result->fetch_all(MYSQLI_ASSOC);
            $result->free_result();
            return $consultations;
        } else {
            return false;
        }
    }

    /**
     * Crée une nouvelle consultation.
     *
     * @param array $data Un tableau associatif des données de la consultation.
     * @return bool Vrai si la création a réussi, faux sinon.
     */
    public function create(array $data): bool {
        $query = "INSERT INTO Consulter (IdMedecin, IdPatient, DateConsultation, SignesVitaux, Diagnostic)
                  VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($query);
        if ($stmt) {
            $stmt->bind_param(
                "iisss",
                $data['IdMedecin'],
                $data['IdPatient'],
                $data['DateConsultation'],
                $data['SignesVitaux'],
                $data['Diagnostic']
            );
            $success = $stmt->execute();
            $stmt->close();
            return $success;
        }
        return false;
    }

    /**
     * Récupère une consultation par son identifiant.
     *
     * @param int $id L'ID de la consultation.
     * @return array|false Un tableau associatif de la consultation ou false si non trouvée.
     */
    public function getOne(int $id): array|false {
        $query = "SELECT * FROM Consulter WHERE IdConsulter = ?";
        $stmt = $this->db->prepare($query);
        if ($stmt) {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $consultation = $result->fetch_assoc();
            $stmt->close();
            return $consultation;
        }
        return false;
    }

    /**
     * Met à jour une consultation existante.
     *
     * @param int $id L'ID de la consultation.
     * @param array $data Les nouvelles données de la consultation.
     * @return bool Vrai si la mise à jour a réussi, faux sinon.
     */
    public function update(int $id, array $data): bool {
        $query = "UPDATE Consulter 
                  SET IdMedecin = ?, IdPatient = ?, DateConsultation = ?, SignesVitaux = ?, Diagnostic = ?
                  WHERE IdConsulter = ?";
        
        $stmt = $this->db->prepare($query);
        if ($stmt) {
            $stmt->bind_param(
                "iisssi",
                $data['IdMedecin'],
                $data['IdPatient'],
                $data['DateConsultation'],
                $data['SignesVitaux'],
                $data['Diagnostic'],
                $id
            );
            $success = $stmt->execute();
            $stmt->close();
            return $success;
        }
        return false;
    }

    /**
     * Supprime une consultation.
     *
     * @param int $id L'ID de la consultation à supprimer.
     * @return bool Vrai si la suppression a réussi, faux sinon.
     */
    public function delete(int $id): bool {
        $query = "DELETE FROM Consulter WHERE IdConsulter = ?";
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
     * Récupère la liste de tous les médecins.
     *
     * @return array Un tableau de médecins.
     */
    public function getAllMedecins(): array {
        $query = "SELECT IdMedecin, Nom FROM Medecin";
        $result = $this->db->query($query);
        $medecins = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $medecins[] = $row;
            }
            $result->free();
        }
        return $medecins;
    }

    /**
     * Récupère la liste de tous les patients.
     *
     * @return array Un tableau de patients.
     */
    public function getAllPatients(): array {
        $query = "SELECT IdPatient, Nom FROM Patient";
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

    
}
