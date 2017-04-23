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
require_once _404_INSTALL_PATH . '/config/routes/user.php';
require_once _404_INSTALL_PATH . '/config/routes/admin.php';
require_once _404_INSTALL_PATH . '/config/routes/post/admin-handlers.php';
require_once _404_INSTALL_PATH . '/config/routes/errors.php';

require_once _404_INSTALL_PATH . '/config/routes/post/login-handlers.php';
require_once _404_INSTALL_PATH . '/config/routes/post/user-handlers.php';

// Internal routes
require_once _404_INSTALL_PATH . '/config/routes/internals.php';

$app->router->handle($app->request->getRoute());
