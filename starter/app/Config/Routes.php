<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/access', 'Access::index');
$routes->get('/unit', 'Unit::index');
$routes->get('/employee', 'Employee::index');
$routes->get('/employee/parent', 'Employee::employeeByParent');
// $routes->get('/tracking', 'Tracking::index');
// $routes->get('/tracking/(:any)', 'Tracking::show/$1');
$routes->post('/upload', 'Tracking::importCsvToDb');
$routes->get('/lookup', 'Lookup::index');
$routes->post('/lookup/detail', 'Lookup::show');
// $routes->post('/save', 'Tracking::create');

// $routes->get('/inbox/unitid/(:any)', 'Tracking::show/$1');

$routes->presenter('tracking');
$routes->get('/removefile', 'Tracking::removeUnusedFile');
$routes->presenter('inbox'); 
$routes->post('/inbox/log', 'Inbox::log');
$routes->post('/inbox/show', 'Inbox::showData');
$routes->get('/parent', 'Unknown::unitParent');
$routes->presenter('unknown');
$routes->presenter('outbox');


/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
