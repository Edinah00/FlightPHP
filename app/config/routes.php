<?php

use app\controllers\DeliveryController;
use app\controllers\BenefitController;
use app\controllers\ZoneController;
use app\controllers\AdminController;
use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;

/**
 * @var Router $router
 * @var Engine $app
 */

$router->group('', function (Router $router) use ($app) {

    $router->get('/', function () use ($app) {
        $app->render('welcome', [
            'message' => 'Welcome to Delivery System'
        ]);
    });

 
    $router->get('/deliveries', [DeliveryController::class, 'index']);
    $router->get('/deliveries/create', [DeliveryController::class, 'create']);
    $router->post('/deliveries/store', [DeliveryController::class, 'store']);
    $router->get('/deliveries/@id:[0-9]+', [DeliveryController::class, 'show']);
    $router->put('/deliveries/@id:[0-9]+/update-status', [DeliveryController::class, 'updateStatus']);

  
    $router->get('/benefits', [BenefitController::class, 'index']);
    $router->get('/benefits/period/@type', [BenefitController::class, 'byPeriod']);

    $router->get('/zones', [ZoneController::class, 'index']);
    $router->get('/zones/create', [ZoneController::class, 'create']);
    $router->post('/zones/store', [ZoneController::class, 'store']);
    $router->get('/zones/@id:[0-9]+/edit', [ZoneController::class, 'edit']);
    $router->put('/zones/@id:[0-9]+', [ZoneController::class, 'update']);
    $router->delete('/zones/@id:[0-9]+', [ZoneController::class, 'delete']);


    $router->get('/admin/stats', [AdminController::class, 'stats']);
    $router->get('/admin/danger-zone', [AdminController::class, 'dangerZone']);
    $router->delete('/admin/deliveries', [AdminController::class, 'deleteAllDeliveries']);

 
    $router->post('/api/calculate-delivery', function () use ($app) {
        $req = $app->request()->data;

        $poids = floatval($req->poids_kg ?? 0);
        $prixParKg = floatval($req->prix_par_kg ?? 0);
        $idZone = intval($req->id_zone ?? 0);
        $idLivreur = intval($req->id_livreur ?? 0);
        $coutVehicule = floatval($req->cout_vehicule ?? 0);

        if ($poids > 0 && $prixParKg > 0 && $idZone > 0 && $idLivreur > 0) {
            $model = new \app\models\Delivery();
            $result = $model->calculateDeliveryFinancials($poids, $prixParKg, $idZone, $idLivreur, $coutVehicule);
            $app->json($result);
        } else {
            $app->json(['error' => 'Paramètres manquants ou invalides'], 400);
        }
    });

}, [SecurityHeadersMiddleware::class]);
