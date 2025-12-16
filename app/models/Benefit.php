<?php

namespace app\models;

use Flight;

class Benefit
{
    private $conn;

    public function __construct()
    {
        // Récupérer la connexion MySQLi enregistrée dans Flight
        $this->conn = Flight::get('db');
    }

    /**
     * Récupérer les bénéfices par période
     */
    public function getByPeriod(string $type = 'day', $start = null, $end = null): array
    {
        $selectDate = '';
        $groupBy = '';

        switch ($type) {
            case 'day':
                $selectDate = "DATE(l.date_creation) AS periode";
                $groupBy = "DATE(l.date_creation)";
                break;

            case 'week':
                $selectDate = "YEARWEEK(l.date_creation, 1) AS periode";
                $groupBy = "YEARWEEK(l.date_creation, 1)";
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
                SUM(liv.salaire_journalier + v.cout_journalier) AS cout_total,
                SUM((c.poids_kg * c.prix_par_kg) - (liv.salaire_journalier + v.cout_journalier)) AS benefice_total
            FROM livraisons_liv l
            JOIN colis_liv c ON l.id_colis = c.id_colis
            JOIN affectations_liv a ON l.id_affectation = a.id_affectation
            JOIN livreurs_liv liv ON a.id_livreur = liv.id_livreur
            JOIN vehicules_liv v ON a.id_vehicule = v.id_vehicule
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

    /**
     * Détails complets via la vue SQL
     */
    public function getDetails(): array
    {
        $sql = "SELECT * FROM vue_benefices_livraisons_liv ORDER BY date_creation DESC";
        $result = $this->conn->query($sql);

        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
