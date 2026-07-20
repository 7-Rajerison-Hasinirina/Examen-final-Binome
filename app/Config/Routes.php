<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'OperateurController::index');
$routes->post('login/valider', 'OperateurController::valider');

// Routes pour Client Office
$routes->get('client-office', 'ClientOfficeController::index');
$routes->get('client-office/logout', 'ClientOfficeController::logout');

// Routes pour Opérateur Office
$routes->get('operateur-office', 'OperateurOfficeController::index');
$routes->get('operateur-office/logout', 'OperateurOfficeController::logout');

// $routes->post('login/authentifier', 'GestionUser::authentifier');
// $routes->get('logout', 'GestionUser::deconnexion');
// $routes->get('inscription', 'GestionUser::inscription');
// $routes->post('inscription/user/add', 'GestionUser::ajouterUser');
// $routes->get('inscription/user/info', 'GestionUser::information');
// $routes->post('inscription/user/info/add', 'GestionUser::ajouterInformation');


// $routes->group('', ['operateur' => 'auth'], function($routes) {
//     $routes->post('regime/objectif/add', 'GestionRegime::sauvegarderObjectif');
//     $routes->get('regime/calculer', 'GestionRegime::calculerRegime');
// });
