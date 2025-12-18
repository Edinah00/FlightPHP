<?php
namespace app\controllers;

use Flight;
use app\models\Benefit;

class BenefitController
{
    public static function index()
    {
        $model = new Benefit();
        $type = Flight::request()->query['type'] ?? 'day';
        $benefits = $model->getByPeriod($type);

        Flight::render('benefits/index', [
            'benefits' => $benefits,
            'type'     => $type,
            'title'    => 'Bénéfices par période'
        ], 'content');

        Flight::render('layouts/main');
    }

    public static function byPeriod($type)
    {
        if (!in_array($type, ['day', 'month', 'year'])) {
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