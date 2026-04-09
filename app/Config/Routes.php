<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
//Route untuk API User (Wajib pake token)
$routes->group('api', ['filter' => 'authfilter'], function($routes) {
    $routes->get('users', 'UserController::index');
    $routes->get('users/(:num)', 'UserController::show/$1');
    $routes->post('users', 'UserController::create');
    $routes->put('users/(:num)', 'UserController::update/$1');
    $routes->delete('users/(:num)', 'UserController::delete/$1');
});
//Route untuk Auth (Wajib pake token)
$routes->post('register', 'AuthController::register');
$routes->post('login', 'AuthController::login');
