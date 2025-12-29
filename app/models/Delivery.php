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
                    c.prix_par_kg,
                    (c.poids_kg * c.prix_par_kg) AS montant_base,
                    z.pourcentage_supplement,
                    ((c.poids_kg * c.prix_par_kg) * z.pourcentage_supplement / 100) AS montant_supplement,
                    ((c.poids_kg * c.prix_par_kg) * (1 + z.pourcentage_supplement / 100)) AS chiffre_affaire,
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
                    (c.poids_kg * c.prix_par_kg) AS montant_base,
                    z.pourcentage_supplement,
                    ((c.poids_kg * c.prix_par_kg) * z.pourcentage_supplement / 100) AS montant_supplement,
                    ((c.poids_kg * c.prix_par_kg) * (1 + z.pourcentage_supplement / 100)) AS chiffre_affaire,
                    CONCAT(liv.nom, ' ', liv.prenom) AS livreur,
                    liv.salaire_par_livraison,
                    v.immatriculation,
                    v.marque,
                    v.modele,
                    z.nom_zone,
                    e.nom AS entrepot,
                    (liv.salaire_par_livraison + l.cout_vehicule) AS cout_revient_total,
                    (((c.poids_kg * c.prix_par_kg) * (1 + z.pourcentage_supplement / 100)) - (liv.salaire_par_livraison + l.cout_vehicule)) AS benefice
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
                'iiiisd',
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
                    CONCAT(nom, ' ', prenom, ' (', FORMAT(salaire_par_livraison, 0), ' Ar/livraison)') AS label,
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
        $sql = "SELECT 
                    id_zone,
                    nom_zone,
                    pourcentage_supplement,
                    CONCAT(nom_zone, ' (', 
                        CASE 
                            WHEN pourcentage_supplement > 0 
                            THEN CONCAT('+', pourcentage_supplement, '% supplément')
                            ELSE 'Pas de supplément'
                        END,
                    ')') AS label
                FROM zones_livraison_liv 
                ORDER BY nom_zone";
        
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Calculer le détail financier d'une livraison potentielle
     * Utile pour l'affichage dynamique lors de la création
     */
    public function calculateDeliveryFinancials(float $poids, float $prixParKg, int $idZone, int $idLivreur, float $coutVehicule): array
    {
        // Montant de base (poids * prix/kg)
        $montantBase = $poids * $prixParKg;

        // Récupérer le pourcentage de supplément de la zone
        $sqlZone = "SELECT pourcentage_supplement FROM zones_livraison_liv WHERE id_zone = ?";
        $stmt = $this->conn->prepare($sqlZone);
        $stmt->bind_param('i', $idZone);
        $stmt->execute();
        $zone = $stmt->get_result()->fetch_assoc();
        $pourcentageSupplement = $zone ? $zone['pourcentage_supplement'] : 0;

        // Calculer le supplément
        $montantSupplement = $montantBase * ($pourcentageSupplement / 100);

        // Chiffre d'affaire total (base + supplément)
        $chiffreAffaire = $montantBase + $montantSupplement;

        // Récupérer le salaire du livreur
        $sqlLivreur = "SELECT salaire_par_livraison FROM livreurs_liv WHERE id_livreur = ?";
        $stmt = $this->conn->prepare($sqlLivreur);
        $stmt->bind_param('i', $idLivreur);
        $stmt->execute();
        $livreur = $stmt->get_result()->fetch_assoc();
        $salaireLivreur = $livreur ? $livreur['salaire_par_livraison'] : 0;

        // Coût de revient total (salaire + coût véhicule)
        $coutRevient = $salaireLivreur + $coutVehicule;

        // Bénéfice net
        $benefice = $chiffreAffaire - $coutRevient;

        return [
            'montant_base' => $montantBase,
            'pourcentage_supplement' => $pourcentageSupplement,
            'montant_supplement' => $montantSupplement,
            'chiffre_affaire' => $chiffreAffaire,
            'salaire_livreur' => $salaireLivreur,
            'cout_vehicule' => $coutVehicule,
            'cout_revient_total' => $coutRevient,
            'benefice' => $benefice,
            'marge_pourcentage' => $chiffreAffaire > 0 ? ($benefice / $chiffreAffaire * 100) : 0
        ];
    }
}