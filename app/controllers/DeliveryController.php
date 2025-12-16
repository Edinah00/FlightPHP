<?php

namespace app\controllers;

use Flight;
use app\models\Delivery;
use Exception;

class DeliveryController
{
    // GET /deliveries
    public static function index()
    {
        $model = new Delivery();
        $deliveries = $model->getAll();

        Flight::render('deliveries/index', [
            'deliveries' => $deliveries,
            'title' => 'Liste des livraisons'
        ], 'content');

        Flight::render('layouts/main');
    }

    // GET /deliveries/create
    public static function create()
    {
        $model = new Delivery();

        Flight::render('deliveries/create', [
            'affectations' => $model->getAffectations(),
            'zones'        => $model->getZones(),
            'entrepots'    => $model->getEntrepots(),
            'title'        => 'Nouvelle livraison'
        ], 'content');

        Flight::render('layouts/main');
    }

    // POST /deliveries/store
    public static function store()
    {
        $model = new Delivery();

        try {
            $req = Flight::request()->data;

            $data = [
                'reference'            => $req->reference,
                'poids_kg'             => $req->poids_kg,
                'prix_par_kg'          => $req->prix_par_kg,
                'description'          => $req->description,
                'id_affectation'       => $req->id_affectation,
                'id_entrepot'          => $req->id_entrepot,
                'id_zone'              => $req->id_zone,
                'adresse_destination'  => $req->adresse_destination
            ];

            $model->create($data);

            Flight::redirect('/deliveries?success=1');

        } catch (Exception $e) {
            Flight::redirect('/deliveries/create?error=' . urlencode($e->getMessage()));
        }
    }

    // GET /deliveries/@id
    public static function show($id)
    {
        $model = new Delivery();
        $delivery = $model->getById($id);

        if (!$delivery) {
            Flight::redirect('/deliveries');
            return;
        }

        Flight::render('deliveries/view', [
            'delivery' => $delivery,
            'title'    => 'Détails de la livraison #' . $id
        ], 'content');

        Flight::render('layouts/main');
    }

    // POST /deliveries/@id/update-status
    public static function updateStatus($id)
    {
        $status = Flight::request()->data->status ?? 'en_attente';

        $model = new Delivery();
        $model->updateStatus($id, $status);

        Flight::redirect('/deliveries/' . $id . '?updated=1');
    }
}
