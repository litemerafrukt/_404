<?php

/**
 * Lets bootstrap this thing!
 */

// This is built upon anax-lite and anax needs these globals..
define("ANAX_INSTALL_PATH", _404_INSTALL_PATH);
define("ANAX_APP_PATH", _404_APP_PATH);

// Some constants
define("_404_APP_ADMIN_LEVEL", 1);
define("_404_APP_USER_LEVEL", 2);
define("_404_APP_GUEST_LEVEL", 3);

// Includes
require_once _404_INSTALL_PATH . "/config/error_reporting.php";
require_once _404_INSTALL_PATH . "/vendor/autoload.php";

// Set default timezone
date_default_timezone_set('Europe/Stockholm');

$app = new \_404\App\App();

// Framework
$app->request       = (new Anax\Request\Request())->init();
$app->response      = new _404\Response\Response();
$app->url           = new _404\Url\Url();
$app->router        = new Anax\Route\RouterInjectable();
$app->view          = new Anax\View\ViewContainer();
// Database
$app->dbconnection  = new _404\Database\DatabaseConnection();
// Globals
$app->session       = new _404\Globals\Session('spensnogsnibbihop');
$app->post          = new _404\Globals\Post();
$app->get           = new _404\Globals\Get();
$app->server        = new _404\Globals\Server();
$app->cookie        = new _404\Globals\Cookie();
// Components
$app->navbar        = new _404\Components\Navbar\Navbar();
$app->loginbutton   = new _404\Components\LoginButton\LoginButton();
$app->reports       = new _404\Components\Articles\Articles(_404_APP_PATH . "/content/reports");


// Url setup
$app->url->setSiteUrl($app->request->getSiteUrl())
         ->setBaseUrl($app->request->getBaseUrl())
         ->setStaticSiteUrl($app->request->getSiteUrl())
         ->setStaticBaseUrl($app->request->getBaseUrl())
         ->setScriptName($app->request->getScriptName());
$app->url->configure("url.php");
$app->url->setDefaultsFromConfiguration();

// Database connection setup
$app->dbconnection->configure("database.php");
$app->dbconnection->connect();

// View setup
$app->view->setApp($app);
$app->view->configure("view.php");

// Session setup
$app->session->start();

// User
$app->user = $app->session->maybe('user')
    ->filter(function ($user) {
        return is_a($user, '_404\User\User');
    })
    ->withDefault(new _404\User\User('Gäst'));

// Loginbutton setup, uses user
$app->loginbutton->setApp($app);
$app->loginbutton->configure("loginbutton.php");


// Navbar setup, uses loginbutton
$app->navbar->setApp($app);
$app->navbar->configure("navbar.php");

// Toolz
$tlz = new _404\Toolz\Toolz();
