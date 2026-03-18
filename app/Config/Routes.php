<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('users', 'UserController::index');
$routes->get('users/(:num)', 'UserController::show/$1');
$routes->post('users', 'UserController::create');
$routes->put('users/(:num)', 'UserController::update/$1');
$routes->delete('users/(:num)', 'UserController::delete/$1');

