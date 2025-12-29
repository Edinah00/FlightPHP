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

// Middleware pour gérer les méthodes PUT/DELETE via _method
$app->before('start', function() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_method'])) {
        $_SERVER['REQUEST_METHOD'] = strtoupper($_POST['_method']);
    }
});

$router->group('', function (Router $router) use ($app) {

    $router->get('/', function () use ($app) {
        $app->render('welcome', [
            'message' => 'Welcome to Delivery System'
        ]);
    });

    // Routes Deliveries
    $router->get('/deliveries', [DeliveryController::class, 'index']);
    $router->get('/deliveries/create', [DeliveryController::class, 'create']);
    $router->post('/deliveries', [DeliveryController::class, 'store']);
    $router->get('/deliveries/@id:[0-9]+', [DeliveryController::class, 'show']);
    $router->post('/deliveries/@id:[0-9]+/update-status', [DeliveryController::class, 'updateStatus']);

    // Routes Benefits
    $router->get('/benefits', [BenefitController::class, 'index']);
    $router->get('/benefits/period/@type', [BenefitController::class, 'byPeriod']);

    // Routes Zones
    $router->get('/zones', [ZoneController::class, 'index']);
    $router->get('/zones/create', [ZoneController::class, 'create']);
    $router->post('/zones', [ZoneController::class, 'store']);
    $router->get('/zones/@id:[0-9]+/edit', [ZoneController::class, 'edit']);
    $router->post('/zones/@id:[0-9]+', [ZoneController::class, 'update']);
    $router->post('/zones/@id:[0-9]+/delete', [ZoneController::class, 'delete']);

    // Routes Admin
    $router->get('/admin/stats', [AdminController::class, 'stats']);
    $router->get('/admin/danger-zone', [AdminController::class, 'dangerZone']);
    $router->post('/admin/delete-all-deliveries', [AdminController::class, 'deleteAllDeliveries']);

    // API route
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