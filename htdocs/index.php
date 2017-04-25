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
require_once _404_INSTALL_PATH . '/routes/me-core.php';
require_once _404_INSTALL_PATH . '/routes/calendar.php';
require_once _404_INSTALL_PATH . '/routes/session-test.php';
require_once _404_INSTALL_PATH . '/routes/user.php';
require_once _404_INSTALL_PATH . '/routes/admin.php';
require_once _404_INSTALL_PATH . '/routes/post/admin-handlers.php';
require_once _404_INSTALL_PATH . '/routes/errors.php';

require_once _404_INSTALL_PATH . '/routes/post/login-handlers.php';
require_once _404_INSTALL_PATH . '/routes/post/user-handlers.php';

// Internal routes
require_once _404_INSTALL_PATH . '/routes/internals.php';

$app->router->handle($app->request->getRoute());
