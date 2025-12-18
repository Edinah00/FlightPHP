<?php
namespace app\models;

use Flight;

class Benefit
{
    private $conn;

    public function __construct()
    {
        $this->conn = Flight::get('db');
    }

    public function getByPeriod(string $type = 'day', $start = null, $end = null): array
    {
        $selectDate = '';
        $groupBy = '';

        switch ($type) {
            case 'day':
                $selectDate = "DATE(l.date_creation) AS periode";
                $groupBy = "DATE(l.date_creation)";
                break;

            case 'month':
                $selectDate = "DATE_FORMAT(l.date_creation, '%Y-%m') AS periode";
                $groupBy = "DATE_FORMAT(l.date_creation, '%Y-%m')";
                break;

            case 'year':
                $selectDate = "YEAR(l.date_creation) AS periode";
                $groupBy = "YEAR(l.date_creation)";
                break;

            default:
                $selectDate = "DATE(l.date_creation) AS periode";
                $groupBy = "DATE(l.date_creation)";
        }

        $sql = "
            SELECT 
                $selectDate,
                COUNT(*) AS nombre_livraisons,
                SUM(c.poids_kg * c.prix_par_kg) AS revenu_total,
                SUM(liv.salaire_par_livraison + l.cout_vehicule) AS cout_total,
                SUM((c.poids_kg * c.prix_par_kg) - (liv.salaire_par_livraison + l.cout_vehicule)) AS benefice_total
            FROM livraisons_liv l
            JOIN colis_liv c ON l.id_colis = c.id_colis
            JOIN livreurs_liv liv ON l.id_livreur = liv.id_livreur
            JOIN vehicules_liv v ON l.id_vehicule = v.id_vehicule
            WHERE l.statut != 'annule'
        ";

        $params = [];
        $types = '';

        if ($start) {
            $sql .= " AND l.date_creation >= ?";
            $types .= 's';
            $params[] = $start;
        }

        if ($end) {
            $sql .= " AND l.date_creation <= ?";
            $types .= 's';
            $params[] = $end;
        }

        $sql .= " GROUP BY $groupBy ORDER BY periode DESC";

        $stmt = $this->conn->prepare($sql);

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getDetails(): array
    {
        $sql = "SELECT * FROM vue_benefices_livraisons_liv ORDER BY date_creation DESC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getBeneficesByVehicule(): array
    {
        $sql = "SELECT 
                    v.immatriculation,
                    v.marque,
                    v.modele,
                    COUNT(*) AS nombre_livraisons,
                    SUM(vb.chiffre_affaire) AS ca_total,
                    SUM(vb.cout_revient_total) AS cout_total,
                    SUM(vb.benefice) AS benefice_total
                FROM vue_benefices_livraisons_liv vb
                JOIN vehicules_liv v ON vb.immatriculation = v.immatriculation
                GROUP BY v.id_vehicule
                ORDER BY benefice_total DESC";

        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}