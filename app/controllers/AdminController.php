<?php

namespace app\controllers;

use Flight;
use Exception;

class AdminController
{
    /**
     * Page de danger - Effacer toutes les livraisons
     */
    public static function dangerZone()
    {
        $error = Flight::request()->query['error'] ?? null;
        $success = Flight::request()->query['success'] ?? null;

        Flight::render('admin/danger_zone', [
            'title' => 'Zone Dangereuse - Administration',
            'error' => $error,
            'success' => $success
        ], 'content');

        Flight::render('layouts/main');
    }

    /**
     * Effacer toutes les livraisons (avec confirmation code 9999)
     */
    public static function deleteAllDeliveries()
    {
        try {
            $req = Flight::request()->data;
            $code = $req->confirmation_code ?? '';

            // Vérifier le code de confirmation
            if ($code !== '9999') {
                throw new Exception("Code de confirmation incorrect. Veuillez saisir 9999 pour confirmer.");
            }

            $conn = Flight::get('db');
            $conn->begin_transaction();

            try {
                // Récupérer tous les IDs de colis liés aux livraisons
                $result = $conn->query("SELECT id_colis FROM livraisons_liv");
                $colisIds = [];
                while ($row = $result->fetch_assoc()) {
                    $colisIds[] = $row['id_colis'];
                }

                // Supprimer toutes les livraisons
                $conn->query("DELETE FROM livraisons_liv");

                // Supprimer tous les colis associés
                if (!empty($colisIds)) {
                    $ids = implode(',', $colisIds);
                    $conn->query("DELETE FROM colis_liv WHERE id_colis IN ($ids)");
                }

                $conn->commit();

                Flight::redirect('/admin/danger-zone?success=all_deleted');

            } catch (Exception $e) {
                $conn->rollback();
                throw $e;
            }

        } catch (Exception $e) {
            Flight::redirect('/admin/danger-zone?error=' . urlencode($e->getMessage()));
        }
    }

    /**
     * Statistiques générales
     */
    public static function stats()
    {
        $conn = Flight::get('db');

        // Compter les éléments
        $stats = [
            'total_livraisons' => 0,
            'total_colis' => 0,
            'total_livreurs' => 0,
            'total_vehicules' => 0,
            'total_zones' => 0,
            'livraisons_en_attente' => 0,
            'livraisons_en_cours' => 0,
            'livraisons_livrees' => 0,
            'livraisons_annulees' => 0
        ];

        // Total livraisons
        $result = $conn->query("SELECT COUNT(*) as total FROM livraisons_liv");
        $stats['total_livraisons'] = $result->fetch_assoc()['total'];

        // Par statut
        $result = $conn->query("SELECT statut, COUNT(*) as total FROM livraisons_liv GROUP BY statut");
        while ($row = $result->fetch_assoc()) {
            $stats['livraisons_' . $row['statut']] = $row['total'];
        }

        // Total colis
        $result = $conn->query("SELECT COUNT(*) as total FROM colis_liv");
        $stats['total_colis'] = $result->fetch_assoc()['total'];

        // Total livreurs
        $result = $conn->query("SELECT COUNT(*) as total FROM livreurs_liv WHERE statut = 'actif'");
        $stats['total_livreurs'] = $result->fetch_assoc()['total'];

        // Total véhicules
        $result = $conn->query("SELECT COUNT(*) as total FROM vehicules_liv WHERE statut IN ('disponible', 'en_service')");
        $stats['total_vehicules'] = $result->fetch_assoc()['total'];

        // Total zones
        $result = $conn->query("SELECT COUNT(*) as total FROM zones_livraison_liv");
        $stats['total_zones'] = $result->fetch_assoc()['total'];

        Flight::render('admin/stats', [
            'title' => 'Statistiques',
            'stats' => $stats
        ], 'content');

        Flight::render('layouts/main');
    }
}