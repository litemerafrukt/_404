<?php

/**
 * Me-page core routes.
 */

$app->router->add("", function () use ($app) {
    $app->view->add("layout", ["title" => "Hem"], "layout");
    $app->view->add("index", [], "main");

    return $app->response->setBody($app->view->renderBuffered("layout"));
});

/* Would it be nice to be able to write as this? */
/* $app->router->add("", function () use ($app) { */
/*     $app->view->add("layout") */
/*         ->data(["title" => "Hem"]) */
/*         ->name("layout"); */

/*     $app->view->add("index")->name("main"); */

/*     return $app->response->setBody($app->view->layout->renderBuffered()); */
/* }); */

$app->router->add("reports", function () use ($app) {
    $app->view->add("layout", ["title" => "Kursmomentsrapporter"], "layout");
    $app->view->add("reports", [], "main");

    return $app->response->setBody($app->view->renderBuffered("layout"));
});

$app->router->add("about", function () use ($app) {
    $app->view->add("layout", ["title" => "Om"], "layout");
    $app->view->add("about", [], "main");

    return $app->response->setBody($app->view->renderBuffered("layout"));
});

$app->router->add('api/sysinfo', function () use ($app) {
    $data = [
        "server" => php_uname(),
        "php_version" => phpversion(),
        "includes" => count(get_included_files()),
        "memory_peak_usage" => memory_get_peak_usage(true),
        "execution_time" => microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'],
    ];

    return $app->response->setJson($data);
});

$app->router->add('phpinfo', function () {
    return phpinfo();
});
