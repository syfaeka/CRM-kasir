<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Public Routes
$routes->get('/', 'Home::landing');
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::attemptLogin');
$routes->get('logout', 'AuthController::logout');

// Protected Routes (require authentication)
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    // Dashboard
    $routes->get('dashboard', 'DashboardController::index');

    // POS (Cashier)
    $routes->get('pos', 'PosController::index');
    $routes->get('pos/receipt/(:num)', 'PosController::receipt/$1');

    // Admin Routes
    $routes->group('admin', static function ($routes) {
        // Products CRUD
        $routes->get('products', 'ProductController::index');
        $routes->get('products/create', 'ProductController::create');
        $routes->post('products', 'ProductController::store');
        $routes->get('products/(:num)/edit', 'ProductController::edit/$1');
        $routes->post('products/(:num)', 'ProductController::update/$1');
        $routes->post('products/(:num)/delete', 'ProductController::delete/$1');

        // Customers CRUD
        $routes->get('customers', 'CustomerController::index');
        $routes->get('customers/create', 'CustomerController::create');
        $routes->post('customers', 'CustomerController::store');
        $routes->get('customers/(:num)', 'CustomerController::show/$1');
        $routes->get('customers/(:num)/edit', 'CustomerController::edit/$1');
        $routes->post('customers/(:num)', 'CustomerController::update/$1');
        $routes->post('customers/(:num)/delete', 'CustomerController::delete/$1');

        // Transaction History
        $routes->get('transactions', 'Admin\\TransactionController::index');
        $routes->get('transactions/(:num)', 'Admin\\TransactionController::show/$1');

        // Stock Management
        $routes->post('products/(:num)/add-stock', 'ProductController::addStock/$1');
    });
});

// API Routes (for AJAX calls from POS)
$routes->group('api', ['namespace' => 'App\Controllers'], static function ($routes) {
    // POS Routes
    $routes->group('pos', static function ($routes) {
        // Product endpoints
        $routes->get('products', 'PosController::searchProduct');
        $routes->get('products/(:num)', 'PosController::getProduct/$1');

        // Checkout endpoint
        $routes->post('checkout', 'PosController::checkout');

        // Transaction endpoints
        $routes->get('transactions/(:segment)', 'PosController::getTransaction/$1');
    });
});
