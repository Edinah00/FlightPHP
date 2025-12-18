<?php
namespace app\controllers;

use Flight;
use app\models\Delivery;
use Exception;

class DeliveryController
{
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

    public static function create()
    {
        $model = new Delivery();

        Flight::render('deliveries/create', [
            'livreurs'  => $model->getLivreurs(),
            'vehicules' => $model->getVehicules(),
            'zones'     => $model->getZones(),
            'title'     => 'Nouvelle livraison'
        ], 'content');

        Flight::render('layouts/main');
    }

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
                'id_livreur'           => $req->id_livreur,
                'id_vehicule'          => $req->id_vehicule,
                'id_zone'              => $req->id_zone,
                'adresse_destination'  => $req->adresse_destination,
                'cout_vehicule'        => $req->cout_vehicule
            ];

            $model->create($data);
            Flight::redirect('/deliveries?success=1');

        } catch (Exception $e) {
            Flight::redirect('/deliveries/create?error=' . urlencode($e->getMessage()));
        }
    }

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

    public static function updateStatus($id)
    {
        $status = Flight::request()->data->status ?? 'en_attente';

        $model = new Delivery();
        $model->updateStatus($id, $status);

        Flight::redirect('/deliveries/' . $id . '?updated=1');
    }
}