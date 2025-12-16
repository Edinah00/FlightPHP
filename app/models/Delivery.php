<?php
class Delivery {
    private $conn;
    
    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }
    
    public function getAll() {
        $query = "SELECT 
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
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getById($id) {
        $query = "SELECT 
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
                  WHERE l.id_livraison = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function create($data) {
        $this->conn->beginTransaction();
        
        try {
            // Créer le colis
            $queryColis = "INSERT INTO colis_liv (reference, poids_kg, prix_par_kg, description) 
                          VALUES (:reference, :poids, :prix, :description)";
            $stmt = $this->conn->prepare($queryColis);
            $stmt->execute([
                ':reference' => $data['reference'],
                ':poids' => $data['poids_kg'],
                ':prix' => $data['prix_par_kg'],
                ':description' => $data['description']
            ]);
            $id_colis = $this->conn->lastInsertId();
            
            // Créer la livraison
            $queryLivraison = "INSERT INTO livraisons_liv 
                              (id_colis, id_affectation, id_entrepot, id_zone, adresse_destination, statut) 
                              VALUES (:id_colis, :id_affectation, :id_entrepot, :id_zone, :adresse, :statut)";
            $stmt = $this->conn->prepare($queryLivraison);
            $stmt->execute([
                ':id_colis' => $id_colis,
                ':id_affectation' => $data['id_affectation'],
                ':id_entrepot' => $data['id_entrepot'],
                ':id_zone' => $data['id_zone'],
                ':adresse' => $data['adresse_destination'],
                ':statut' => 'en_attente'
            ]);
            
            $this->conn->commit();
            return true;
        } catch(Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }
    
    public function updateStatus($id, $status) {
        $query = "UPDATE livraisons_liv SET statut = :statut";
        
        if ($status === 'livre') {
            $query .= ", date_livraison = NOW()";
        }
        
        $query .= " WHERE id_livraison = :id";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':statut' => $status,
            ':id' => $id
        ]);
    }
    
    public function getAffectations() {
        $query = "SELECT 
                    a.id_affectation,
                    CONCAT(l.nom, ' ', l.prenom, ' - ', v.immatriculation, ' (', a.date_affectation, ')') AS label
                  FROM affectations_liv a
                  JOIN livreurs_liv l ON a.id_livreur = l.id_livreur
                  JOIN vehicules_liv v ON a.id_vehicule = v.id_vehicule
                  ORDER BY a.date_affectation DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getZones() {
        $query = "SELECT * FROM zones_livraison_liv ORDER BY nom_zone";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getEntrepots() {
        $query = "SELECT * FROM entrepots_liv ORDER BY nom";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
