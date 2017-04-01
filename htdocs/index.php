<?php

/**
 * Thou frontcontroller
 */

// Where are our framework?
define("_404_INSTALL_PATH", realpath(__DIR__ . "/.."));
define("_404_APP_PATH", _404_INSTALL_PATH);

// Bootstrap
require_once _404_INSTALL_PATH . '/config/bootstrap.php';

// Routing
require_once _404_INSTALL_PATH . '/config/routes.php';
require_once _404_INSTALL_PATH . '/config/internal_routes.php';
$app->router->handle($app->request->getRoute());
