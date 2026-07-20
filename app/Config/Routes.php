<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'OperateurController::index');
$routes->post('login/valider', 'OperateurController::valider');

// Routes pour Client Office
$routes->get('client-office', 'ClientOfficeController::index');
$routes->get('client-office/solde', 'ClientOfficeController::solde');
$routes->post('client-office/numero/activer', 'ClientOfficeController::activerNumero');
$routes->get('client-office/depot', 'ClientOfficeController::depot');
$routes->post('client-office/depot/traiter', 'ClientOfficeController::traiterDepot');
$routes->get('client-office/retrait', 'ClientOfficeController::retrait');
$routes->post('client-office/retrait/traiter', 'ClientOfficeController::traiterRetrait');
$routes->get('client-office/transfert', 'ClientOfficeController::transfert');
$routes->post('client-office/transfert/traiter', 'ClientOfficeController::traiterTransfert');
$routes->get('client-office/historique', 'ClientOfficeController::historique');
$routes->get('client-office/logout', 'ClientOfficeController::logout');

// Routes pour Opérateur Office
$routes->match(['get', 'post'], 'operateur-office', 'OperateurOfficeController::index');
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
