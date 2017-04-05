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
$app->url      = new \Anax\Url\Url();
$app->router   = new \Anax\Route\RouterInjectable();
$app->view     = new \Anax\View\ViewContainer();

// Url init
$app->url->setSiteUrl($app->request->getSiteUrl());
$app->url->setBaseUrl($app->request->getBaseUrl());
$app->url->setStaticSiteUrl($app->request->getSiteUrl());
$app->url->setStaticBaseUrl($app->request->getBaseUrl());
$app->url->setScriptName($app->request->getScriptName());

// Fetch from config
$app->url->configure("url.php");
$app->url->setDefaultsFromConfiguration();

// View setup
$app->view->setApp($app);
$app->view->configure("view.php");

// Markdown kmom-reports setup
$app->reports = new \_404\Articles\Articles(_404_APP_PATH . "/content/reports");
