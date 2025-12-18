<?php
namespace app\models;

use Flight;

class Delivery
{
    private $conn;

    public function __construct()
    {
        $this->conn = Flight::get('db');
    }

    public function getAll(): array
    {
        $sql = "SELECT 
                    l.id_livraison,
                    l.statut,
                    l.date_creation,
                    l.date_livraison,
                    l.adresse_destination,
                    l.cout_vehicule,
                    c.reference,
                    c.poids_kg,
                    CONCAT(liv.nom, ' ', liv.prenom) AS livreur,
                    v.immatriculation,
                    z.nom_zone
                FROM livraisons_liv l
                JOIN colis_liv c ON l.id_colis = c.id_colis
                JOIN livreurs_liv liv ON l.id_livreur = liv.id_livreur
                JOIN vehicules_liv v ON l.id_vehicule = v.id_vehicule
                JOIN zones_livraison_liv z ON l.id_zone = z.id_zone
                ORDER BY l.date_creation DESC";

        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getById(int $id): ?array
    {
        $sql = "SELECT 
                    l.*,
                    c.reference,
                    c.poids_kg,
                    c.prix_par_kg,
                    c.description,
                    (c.poids_kg * c.prix_par_kg) AS chiffre_affaire,
                    CONCAT(liv.nom, ' ', liv.prenom) AS livreur,
                    liv.salaire_par_livraison,
                    v.immatriculation,
                    v.marque,
                    v.modele,
                    z.nom_zone,
                    e.nom AS entrepot,
                    (liv.salaire_par_livraison + l.cout_vehicule) AS cout_revient_total,
                    ((c.poids_kg * c.prix_par_kg) - (liv.salaire_par_livraison + l.cout_vehicule)) AS benefice
                FROM livraisons_liv l
                JOIN colis_liv c ON l.id_colis = c.id_colis
                JOIN livreurs_liv liv ON l.id_livreur = liv.id_livreur
                JOIN vehicules_liv v ON l.id_vehicule = v.id_vehicule
                JOIN zones_livraison_liv z ON l.id_zone = z.id_zone
                JOIN entrepot_liv e ON l.id_entrepot = e.id_entrepot
                WHERE l.id_livraison = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc() ?: null;
    }

    public function create(array $data): bool
    {
        $this->conn->begin_transaction();

        try {
            // Créer le colis
            $sqlColis = "INSERT INTO colis_liv (reference, poids_kg, prix_par_kg, description)
                         VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sqlColis);
            $stmt->bind_param(
                'sdds',
                $data['reference'],
                $data['poids_kg'],
                $data['prix_par_kg'],
                $data['description']
            );
            $stmt->execute();
            $id_colis = $this->conn->insert_id;

            // Créer la livraison avec le coût véhicule saisi
            $sqlLivraison = "INSERT INTO livraisons_liv 
                             (id_colis, id_livreur, id_vehicule, id_entrepot, id_zone, 
                              adresse_destination, cout_vehicule, statut)
                             VALUES (?, ?, ?, 1, ?, ?, ?, 'en_attente')";
            $stmt = $this->conn->prepare($sqlLivraison);
            $stmt->bind_param(
                'iiisd',
                $id_colis,
                $data['id_livreur'],
                $data['id_vehicule'],
                $data['id_zone'],
                $data['adresse_destination'],
                $data['cout_vehicule']
            );
            $stmt->execute();

            $this->conn->commit();
            return true;
        } catch (\Exception $e) {
            $this->conn->rollback();
            throw $e;
        }
    }

    public function updateStatus(int $id, string $status): bool
    {
        $sql = "UPDATE livraisons_liv SET statut = ?";
        if ($status === 'livre') {
            $sql .= ", date_livraison = NOW()";
        }
        $sql .= " WHERE id_livraison = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('si', $status, $id);
        return $stmt->execute();
    }

    public function getLivreurs(): array
    {
        $sql = "SELECT 
                    id_livreur,
                    CONCAT(nom, ' ', prenom, ' (', salaire_par_livraison, ' Ar/livraison)') AS label,
                    salaire_par_livraison
                FROM livreurs_liv
                WHERE statut = 'actif'
                ORDER BY nom, prenom";

        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getVehicules(): array
    {
        $sql = "SELECT 
                    id_vehicule,
                    CONCAT(immatriculation, ' - ', marque, ' ', modele) AS label
                FROM vehicules_liv
                WHERE statut IN ('disponible', 'en_service')
                ORDER BY immatriculation";

        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getZones(): array
    {
        $sql = "SELECT * FROM zones_livraison_liv ORDER BY nom_zone";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
