<?php
class DeliveryController {
    public function index() {
        $model = new Delivery();
        $deliveries = $model->getAll();
        
        Flight::render('deliveries/index', [
            'deliveries' => $deliveries,
            'title' => 'Liste des livraisons'
        ], 'content');
        
        Flight::render('layouts/main');
    }
    
    public function create() {
        $model = new Delivery();
        $affectations = $model->getAffectations();
        $zones = $model->getZones();
        $entrepots = $model->getEntrepots();
        
        Flight::render('deliveries/create', [
            'affectations' => $affectations,
            'zones' => $zones,
            'entrepots' => $entrepots,
            'title' => 'Nouvelle livraison'
        ], 'content');
        
        Flight::render('layouts/main');
    }
    
    public function store() {
        $model = new Delivery();
        
        try {
            $data = [
                'reference' => $_POST['reference'],
                'poids_kg' => $_POST['poids_kg'],
                'prix_par_kg' => $_POST['prix_par_kg'],
                'description' => $_POST['description'],
                'id_affectation' => $_POST['id_affectation'],
                'id_entrepot' => $_POST['id_entrepot'],
                'id_zone' => $_POST['id_zone'],
                'adresse_destination' => $_POST['adresse_destination']
            ];
            
            $model->create($data);
            Flight::redirect('/deliveries?success=1');
        } catch(Exception $e) {
            Flight::redirect('/deliveries/create?error=' . urlencode($e->getMessage()));
        }
    }
    
    public function show($id) {
        $model = new Delivery();
        $delivery = $model->getById($id);
        
        if (!$delivery) {
            Flight::redirect('/deliveries');
            return;
        }
        
        Flight::render('deliveries/view', [
            'delivery' => $delivery,
            'title' => 'Détails de la livraison #' . $id
        ], 'content');
        
        Flight::render('layouts/main');
    }
    
    public function updateStatus($id) {
        $model = new Delivery();
        $status = $_POST['status'] ?? 'en_attente';
        
        $model->updateStatus($id, $status);
        Flight::redirect('/deliveries/' . $id . '?updated=1');
    }
}
