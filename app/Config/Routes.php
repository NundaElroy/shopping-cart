<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');

//auth routes
$routes->post('/api/auth/register', 'AuthController::register');

// service('auth')->routes($routes);

//login/auth 
// app/Config/Routes.php
$routes->post('/api/auth/login', 'AuthController::login');

//product routes
$routes->post('/api/products', 'ProductController::create');
$routes->get('/api/products', 'ProductController::getAllProducts');
$routes->get('/api/products/(:num)', 'ProductController::getProductById/$1');

$routes->group('api/cart', ['filter' => 'jwt'], static function ($routes) {
    $routes->get('', 'CartController::getCart'); // GET /api/cart
    $routes->post('item', 'CartController::addItemToCart'); // POST /api/cart/item
    $routes->put('item/(:num)', 'CartController::editItem/$1'); // PUT /api/cart/item/{:num}
    $routes->delete('items/(:num)', 'CartController::removeItem/$1'); // DELETE /api/cart/items/{:num}
});




