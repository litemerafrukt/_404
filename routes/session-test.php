<?php

/**
 * Test session routes
 */

$app->router->add('session', function () use ($app) {
    $counter = $app->session->get("counter", 0);
    $app->view->add("layout", ["title" => "Sessionstest"], "layout");
    $app->view->add("session/session", compact("counter"), "main");

    $app->response->setBody($app->view->renderBuffered("layout"))
        ->send();
});

$app->router->add('session/increment', function () use ($app) {
    $app->session->set(
        'counter',
        $app->session->get('counter', 0) + 1
    );

    $app->response->redirect($app->url->create('session'));
});

$app->router->add('session/decrement', function () use ($app) {
    $app->session->set(
        'counter',
        $app->session->get('counter', 0) - 1
    );

    $app->response->redirect($app->url->create('session'));
});

$app->router->add('session/status', function () use ($app) {
    $data = [
        "session_cache_expire" => session_cache_expire(),
        "session_name" => session_name(),
        "session_save_path" => session_save_path(),
        "session_id" => session_id(),
        "session_module_name" => session_module_name(),
    ];

    $app->response->sendJson($data);
});

$app->router->add('session/dump', function () use ($app) {
    $sessionDump = $app->session->dump();
    $app->view->add("layout", ["title" => "Sessionsdump"], "layout");
    $app->view->add("session/dump", ["dump" => $sessionDump], "main");

    $app->response->setBody($app->view->renderBuffered("layout"))
        ->send();
});

$app->router->add('session/destroy', function () use ($app) {
    $app->session->destroy();

    $app->response->redirect($app->url->create('session/dump'));
});
