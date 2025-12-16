<?php

namespace app\controllers;

use Flight;
use app\models\Benefit;

class BenefitController
{
    // GET /benefits
    public static function index()
    {
        $model = new Benefit();

        // Paramètre optionnel ?type=day|week|month
        $type = Flight::request()->query['type'] ?? 'day';

        $benefits = $model->getByPeriod($type);

        Flight::render('benefits/index', [
            'benefits' => $benefits,
            'type'     => $type,
            'title'    => 'Bénéfices par période'
        ], 'content');

        Flight::render('layouts/main');
    }

    // GET /benefits/period/@type
    public static function byPeriod($type)
    {
        if (!in_array($type, ['day', 'week', 'month'])) {
            Flight::redirect('/benefits?type=day');
            return;
        }

        $model = new Benefit();
        $benefits = $model->getByPeriod($type);

        Flight::render('benefits/index', [
            'benefits' => $benefits,
            'type'     => $type,
            'title'    => 'Bénéfices par ' . $type
        ], 'content');

        Flight::render('layouts/main');
    }
}
