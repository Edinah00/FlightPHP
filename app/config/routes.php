<?php

use app\controllers\DeliveryController;
use app\controllers\BenefitController;
use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;

/**
 * @var Router $router
 * @var Engine $app
 */

$router->group('', function (Router $router) use ($app) {

    // Page d'accueil
    $router->get('/', function () use ($app) {
        $app->render('welcome', [
            'message' => 'Welcome to Delivery System'
        ]);
    });

    // =======================
    // LIVRAISONS
    // =======================

    $router->get('/deliveries', [DeliveryController::class, 'index']);
    $router->get('/deliveries/create', [DeliveryController::class, 'create']);
    $router->post('/deliveries/store', [DeliveryController::class, 'store']);
    $router->get('/deliveries/@id:[0-9]+', [DeliveryController::class, 'show']);
    $router->post(
        '/deliveries/@id:[0-9]+/update-status',
        [DeliveryController::class, 'updateStatus']
    );

    // =======================
    // BENEFICES
    // =======================

    $router->get('/benefits', [BenefitController::class, 'index']);
    $router->get('/benefits/period/@type', [BenefitController::class, 'byPeriod']);

}, [SecurityHeadersMiddleware::class]);
