<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');

//auth routes
$routes->post('/register', 'AuthController::register');

// service('auth')->routes($routes);

//login/auth 
// app/Config/Routes.php
$routes->post('/auth/jwt', 'AuthController::login');

//product routes
$routes->post('/products', 'ProductController::create');
$routes->get('/products', 'ProductController::getAllProducts');
$routes->get('products/(:num)', 'ProductController::getProductById/$1');

