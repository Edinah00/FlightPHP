<?php

namespace app\models;

use Flight;

class Delivery
{
    private $conn;

    public function __construct()
    {
        // Récupération de la connexion MySQLi depuis Flight
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
                    c.reference,
                    c.poids_kg,
                    CONCAT(liv.nom, ' ', liv.prenom) AS livreur,
                    v.immatriculation,
                    z.nom_zone
                FROM livraisons_liv l
                JOIN colis_liv c ON l.id_colis = c.id_colis
                JOIN affectations_liv a ON l.id_affectation = a.id_affectation
                JOIN livreurs_liv liv ON a.id_livreur = liv.id_livreur
                JOIN vehicules_liv v ON a.id_vehicule = v.id_vehicule
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
                    CONCAT(liv.nom, ' ', liv.prenom) AS livreur,
                    liv.salaire_journalier,
                    v.immatriculation,
                    v.marque,
                    v.modele,
                    v.cout_journalier,
                    z.nom_zone,
                    e.nom AS entrepot
                FROM livraisons_liv l
                JOIN colis_liv c ON l.id_colis = c.id_colis
                JOIN affectations_liv a ON l.id_affectation = a.id_affectation
                JOIN livreurs_liv liv ON a.id_livreur = liv.id_livreur
                JOIN vehicules_liv v ON a.id_vehicule = v.id_vehicule
                JOIN zones_livraison_liv z ON l.id_zone = z.id_zone
                JOIN entrepots_liv e ON l.id_entrepot = e.id_entrepot
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

            // Créer la livraison
            $sqlLivraison = "INSERT INTO livraisons_liv 
                             (id_colis, id_affectation, id_entrepot, id_zone, adresse_destination, statut)
                             VALUES (?, ?, ?, ?, ?, 'en_attente')";
            $stmt = $this->conn->prepare($sqlLivraison);
            $stmt->bind_param(
                'iiiss',
                $id_colis,
                $data['id_affectation'],
                $data['id_entrepot'],
                $data['id_zone'],
                $data['adresse_destination']
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

    public function getAffectations(): array
    {
        $sql = "SELECT 
                    a.id_affectation,
                    CONCAT(l.nom, ' ', l.prenom, ' - ', v.immatriculation, ' (', a.date_affectation, ')') AS label
                FROM affectations_liv a
                JOIN livreurs_liv l ON a.id_livreur = l.id_livreur
                JOIN vehicules_liv v ON a.id_vehicule = v.id_vehicule
                ORDER BY a.date_affectation DESC";

        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getZones(): array
    {
        $sql = "SELECT * FROM zones_livraison_liv ORDER BY nom_zone";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getEntrepots(): array
    {
        $sql = "SELECT * FROM entrepots_liv ORDER BY nom";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
