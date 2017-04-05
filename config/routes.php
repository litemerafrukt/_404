<?php
/**
 * Public routes.
 */
$app->router->add("", function () use ($app) {
    $app->view->add("header", ["title" => "Hem"]);
    $app->view->add("navbar1/navbar");
    $app->view->add("index");
    $app->view->add('footer');

    $app->response->setBody([$app->view, "render"])
        ->send();
});

$app->router->add("reports", function () use ($app) {
    $app->view->add("header", ["title" => "Kursmomentsrapporter"]);
    $app->view->add("navbar1/navbar");
    $app->view->add("reports");
    $app->view->add('footer');

    $app->response->setBody([$app->view, "render"])
        ->send();
});

$app->router->add("about", function () use ($app) {
    $app->view->add("header", ["title" => "About"]);
    $app->view->add("navbar1/navbar");
    $app->view->add("about");

    $app->response->setBody([$app->view, "render"])
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
