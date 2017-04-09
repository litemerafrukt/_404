<?php

/**
 * Lets bootstrap this thing!
 */

// This is built upon anax-lite and anax needs these globals..
define("ANAX_INSTALL_PATH", _404_INSTALL_PATH);
define("ANAX_APP_PATH", _404_APP_PATH);


// Includes
require_once _404_INSTALL_PATH . "/config/error_reporting.php";
require_once _404_INSTALL_PATH . "/vendor/autoload.php";

$app = new \_404\App\App();

$app->request  = (new \Anax\Request\Request())->init();
$app->response = new \Anax\Response\Response();
$app->url      = new \_404\Url\Url();
$app->router   = new \Anax\Route\RouterInjectable();
$app->view     = new \Anax\View\ViewContainer();
$app->navbar   = new \_404\Navbar\Navbar();

// Url init
$app->url->setSiteUrl($app->request->getSiteUrl())
         ->setBaseUrl($app->request->getBaseUrl())
         ->setStaticSiteUrl($app->request->getSiteUrl())
         ->setStaticBaseUrl($app->request->getBaseUrl())
         ->setScriptName($app->request->getScriptName());

// Fetch from config
$app->url->configure("url.php");
$app->url->setDefaultsFromConfiguration();

// View setup
$app->view->setApp($app);
$app->view->configure("view.php");

// Navbar setup
$app->navbar->setApp($app);
$app->navbar->configure("navbar.php");


// Markdown kmom-reports setup
$app->reports = new \_404\Articles\Articles(_404_APP_PATH . "/content/reports");
