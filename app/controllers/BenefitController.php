<?php
class BenefitController {
    public function index() {
        $model = new Benefit();
        $type = $_GET['type'] ?? 'day';
        $benefits = $model->getByPeriod($type);
        
        Flight::render('benefits/index', [
            'benefits' => $benefits,
            'type' => $type,
            'title' => 'Bénéfices par période'
        ], 'content');
        
        Flight::render('layouts/main');
    }
}