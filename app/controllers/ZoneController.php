<?php

namespace app\controllers;

use Flight;
use app\models\Zone;
use Exception;

class ZoneController
{
    /**
     * Afficher la liste des zones
     */
    public static function index()
    {
        $model = new Zone();
        $zones = $model->getAll();

        $success = Flight::request()->query['success'] ?? null;
        $error = Flight::request()->query['error'] ?? null;

        Flight::render('zones/index', [
            'zones' => $zones,
            'title' => 'Gestion des zones de livraison',
            'success' => $success,
            'error' => $error
        ], 'content');

        Flight::render('layouts/main');
    }

    /**
     * Afficher le formulaire de création
     */
    public static function create()
    {
        Flight::render('zones/create', [
            'title' => 'Nouvelle zone de livraison'
        ], 'content');

        Flight::render('layouts/main');
    }

    /**
     * Enregistrer une nouvelle zone
     */
    public static function store()
    {
        $model = new Zone();

        try {
            $req = Flight::request()->data;

            $data = [
                'nom_zone' => trim($req->nom_zone),
                'pourcentage_supplement' => floatval($req->pourcentage_supplement),
                'description' => trim($req->description ?? '')
            ];

            // Validation
            if (empty($data['nom_zone'])) {
                throw new Exception("Le nom de la zone est obligatoire");
            }

            if ($data['pourcentage_supplement'] < 0) {
                throw new Exception("Le pourcentage ne peut pas être négatif");
            }

            $model->create($data);
            Flight::redirect('/zones?success=created');

        } catch (Exception $e) {
            Flight::redirect('/zones/create?error=' . urlencode($e->getMessage()));
        }
    }

    /**
     * Afficher le formulaire de modification
     */
    public static function edit($id)
    {
        $model = new Zone();
        $zone = $model->getById($id);

        if (!$zone) {
            Flight::redirect('/zones?error=notfound');
            return;
        }

        Flight::render('zones/edit', [
            'zone' => $zone,
            'title' => 'Modifier la zone'
        ], 'content');

        Flight::render('layouts/main');
    }

    /**
     * Mettre à jour une zone
     */
    public static function update($id)
    {
        $model = new Zone();

        try {
            $req = Flight::request()->data;

            $data = [
                'nom_zone' => trim($req->nom_zone),
                'pourcentage_supplement' => floatval($req->pourcentage_supplement),
                'description' => trim($req->description ?? '')
            ];

            // Validation
            if (empty($data['nom_zone'])) {
                throw new Exception("Le nom de la zone est obligatoire");
            }

            if ($data['pourcentage_supplement'] < 0) {
                throw new Exception("Le pourcentage ne peut pas être négatif");
            }

            $model->update($id, $data);
            Flight::redirect('/zones?success=updated');

        } catch (Exception $e) {
            Flight::redirect('/zones/' . $id . '/edit?error=' . urlencode($e->getMessage()));
        }
    }

    /**
     * Supprimer une zone
     */
    public static function delete($id)
    {
        $model = new Zone();

        try {
            // Vérifier si la zone n'est pas utilisée dans des livraisons
            if ($model->hasDeliveries($id)) {
                throw new Exception("Cette zone ne peut pas être supprimée car elle est utilisée dans des livraisons");
            }

            $model->delete($id);
            Flight::redirect('/zones?success=deleted');

        } catch (Exception $e) {
            Flight::redirect('/zones?error=' . urlencode($e->getMessage()));
        }
    }
}