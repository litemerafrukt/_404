<?php

/**
 * Public routes.
 */

$app->router->add("", function () use ($app) {
    $app->view->add("layout", ["title" => "Hem"], "layout");
    $app->view->add("index", [], "main");

    $app->response->setBody($app->view->renderBuffered("layout"))
        ->send();
});

$app->router->add("reports", function () use ($app) {
    $app->view->add("layout", ["title" => "Kursmomentsrapporter"], "layout");
    $app->view->add("reports", [], "main");

    $app->response->setBody($app->view->renderBuffered("layout"))
        ->send();
});

$app->router->add("about", function () use ($app) {
    $app->view->add("layout", ["title" => "Om"], "layout");
    $app->view->add("about", [], "main");

    $app->response->setBody($app->view->renderBuffered("layout"))
        ->send();
});

$app->router->add('api/sysinfo', function () use ($app) {
    $data = [
        "server" => php_uname(),
        "php_version" => phpversion(),
        "includes" => count(get_included_files()),
        "memory_peak_usage" => memory_get_peak_usage(true),
        "execution_time" => microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'],
    ];

    $app->response->sendJson($data);
});
