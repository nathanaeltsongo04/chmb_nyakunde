<?php
/**
 * Fichier du modèle pour la classe Preconsulter.
 * Gère les interactions avec la table 'Preconsulter' dans la base de données.
 */
class Preconsulter
{
    // Connexion à la base de données
    private $conn;
    private $table_name = "preconsulter";

    // Propriétés de la table, ajustées pour correspondre à votre CREATE TABLE
    public $IdPreconsulter;
    public $IdInfirmier;
    public $IdPatient;
    public $Date;
    public $Observations;

    /**
     * Constructeur avec injection de la connexion à la base de données.
     *
     * @param object $db La connexion à la base de données (mysqli).
     */
    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Lit et retourne la liste de toutes les pré-consultations.
     *
     * @return mysqli_result|false Le jeu de résultats de la requête ou false en cas d'échec.
     */
    public function index()
    {
        $query = "SELECT * FROM " . $this->table_name;
        $result = $this->conn->query($query);
        return $result;
    }

    /**
     * Crée une nouvelle pré-consultation en insérant les données correspondantes.
     *
     * @param array $data Les données de la pré-consultation (IdInfirmier, IdPatient, Date, Observations).
     * @return bool Vrai si la création a réussi, faux sinon.
     */
    public function store($data)
    {
        // La requête SQL est mise à jour pour utiliser les nouvelles colonnes
        $query = "INSERT INTO " . $this->table_name . " (IdInfirmier, IdPatient, Date, Observations) VALUES (?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        
        // Liaison des paramètres avec les types de données appropriés
        $stmt->bind_param("iiss", 
            $data['IdInfirmier'], 
            $data['IdPatient'], 
            $data['Date'], 
            $data['Observations']
        );

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Met à jour une pré-consultation existante.
     *
     * @param int $id L'ID de la pré-consultation à mettre à jour.
     * @param array $data Les nouvelles données.
     * @return bool Vrai si la mise à jour a réussi, faux sinon.
     */
    public function update($id, $data)
    {
        // La requête SQL est mise à jour pour utiliser les nouvelles colonnes
        $query = "UPDATE " . $this->table_name . " SET IdInfirmier=?, IdPatient=?, Date=?, Observations=? WHERE IdPreconsulter=?";
        
        $stmt = $this->conn->prepare($query);
        
        // Liaison des paramètres avec les types de données appropriés
        $stmt->bind_param("iissi", 
            $data['IdInfirmier'], 
            $data['IdPatient'], 
            $data['Date'], 
            $data['Observations'],
            $id
        );
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Supprime une pré-consultation par son identifiant.
     *
     * @param int $id L'ID de la pré-consultation à supprimer.
     * @return bool Vrai si la suppression a réussi, faux sinon.
     */
    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE IdPreconsulter = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
