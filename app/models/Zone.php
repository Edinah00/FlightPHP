<?php
// ============================================
// FILE: app/models/Zone.php
// ============================================
namespace app\models;

use Flight;

class Zone
{
    private $conn;

    public function __construct()
    {
        $this->conn = Flight::get('db');
    }

    /**
     * Récupérer toutes les zones
     */
    public function getAll(): array
    {
        $sql = "SELECT 
                    z.*,
                    COUNT(l.id_livraison) AS nombre_livraisons
                FROM zones_livraison_liv z
                LEFT JOIN livraisons_liv l ON z.id_zone = l.id_zone
                GROUP BY z.id_zone
                ORDER BY z.nom_zone ASC";

        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Récupérer une zone par ID
     */
    public function getById(int $id): ?array
    {
        $sql = "SELECT * FROM zones_livraison_liv WHERE id_zone = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc() ?: null;
    }

    /**
     * Créer une nouvelle zone
     */
    public function create(array $data): bool
    {
        $sql = "INSERT INTO zones_livraison_liv 
                (nom_zone, pourcentage_supplement, description)
                VALUES (?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            'sds',
            $data['nom_zone'],
            $data['pourcentage_supplement'],
            $data['description']
        );

        return $stmt->execute();
    }

    /**
     * Mettre à jour une zone
     */
    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE zones_livraison_liv 
                SET nom_zone = ?,
                    pourcentage_supplement = ?,
                    description = ?
                WHERE id_zone = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            'sdsi',
            $data['nom_zone'],
            $data['pourcentage_supplement'],
            $data['description'],
            $id
        );

        return $stmt->execute();
    }

    /**
     * Supprimer une zone
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM zones_livraison_liv WHERE id_zone = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    /**
     * Vérifier si une zone est utilisée dans des livraisons
     */
    public function hasDeliveries(int $id): bool
    {
        $sql = "SELECT COUNT(*) as count 
                FROM livraisons_liv 
                WHERE id_zone = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['count'] > 0;
    }

    /**
     * Calculer le supplément pour un montant donné et une zone
     */
    public function calculateSupplement(int $zoneId, float $montant): float
    {
        $zone = $this->getById($zoneId);
        
        if (!$zone) {
            return 0;
        }

        return $montant * ($zone['pourcentage_supplement'] / 100);
    }
}