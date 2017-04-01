<?php
/**
 * Public routes.
 */
$app->router->add("", function () use ($app) {
    $app->view->add("examples/header", ["title" => "Home"]);
    $app->view->add("examples/navbar");
    $app->view->add("examples/home");

    $app->response->setBody([$app->view, "render"])
        ->send();
});

$app->router->add("about", function () use ($app) {
    $app->view->add("examples/header", ["title" => "About"]);
    $app->view->add("examples/navbar");
    $app->view->add("examples/about");

    $app->response->setBody([$app->view, "render"])
        ->send();
});
