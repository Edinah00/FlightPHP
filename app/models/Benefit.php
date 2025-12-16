<?php
class Benefit {
    private $conn;
    
    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }
    
    public function getByPeriod($type = 'day', $start = null, $end = null) {
        $groupBy = '';
        $selectDate = '';
        
        switch($type) {
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
        }
        
        $query = "SELECT 
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
                  WHERE l.statut != 'annule'";
        
        if ($start) {
            $query .= " AND l.date_creation >= :start";
        }
        if ($end) {
            $query .= " AND l.date_creation <= :end";
        }
        
        $query .= " GROUP BY $groupBy ORDER BY periode DESC";
        
        $stmt = $this->conn->prepare($query);
        
        if ($start) $stmt->bindParam(':start', $start);
        if ($end) $stmt->bindParam(':end', $end);
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getDetails() {
        $query = "SELECT * FROM vue_benefices_livraisons_liv ORDER BY date_creation DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
