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
// Public routes
require_once _404_INSTALL_PATH . '/config/routes/me-core.php';
require_once _404_INSTALL_PATH . '/config/routes/calendar.php';
require_once _404_INSTALL_PATH . '/config/routes/session-test.php';
require_once _404_INSTALL_PATH . '/config/routes/post-handlers/login.php';
require_once _404_INSTALL_PATH . '/config/routes/user.php';

// Internal routes
require_once _404_INSTALL_PATH . '/config/routes/internal_routes.php';

$app->router->handle($app->request->getRoute());
