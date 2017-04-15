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

/**
 * Calendar
 */

$app->router->add('calendar', function () use ($app) {
    $year = date("Y");
    $month = date("n");

    $url = $app->url->create("calendar/$year/$month");
    $app->response->redirect($url);
});

$app->router->add('calendar/{year:digit}/{month:digit}', function ($year, $month) use ($app) {
    $year = $year < 1 ? 1 : $year;
    $month = $month < 1 ? 1 : ($month > 12 ? 12 : $month);

    $calendar = new \_404\Calendar\WallCalendar($year, $month);
    $calendar->setApp($app);
    $calendar->configure("calendar.php");

    $calendarOutput = $calendar->fullCalendar();

    $app->view->add("layout", ["title" => "Kalender"], "layout");
    $app->view->add("calendar", ['calendar' => $calendarOutput], "main");

    $app->response->setBody($app->view->renderBuffered("layout"))
        ->send();
});

/**
 * Test session stuff
 */

$app->router->add('session', function () use ($app) {
    $app->session->start();
    $counter = $app->session->get("counter", 0);
    $app->view->add("layout", ["title" => "Sessionstest"], "layout");
    $app->view->add("session/session", compact("counter"), "main");

    $app->response->setBody($app->view->renderBuffered("layout"))
        ->send();
});

$app->router->add('session/increment', function () use ($app) {
    $app->session->start();
    $app->session->set(
        'counter',
        $app->session->get('counter', 0) + 1
    );

    $app->response->redirect($app->url->create('session'));
});

$app->router->add('session/decrement', function () use ($app) {
    $app->session->start();
    $app->session->set(
        'counter',
        $app->session->get('counter', 0) - 1
    );

    $app->response->redirect($app->url->create('session'));
});

$app->router->add('session/status', function () use ($app) {
    $app->session->start();
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
    $app->session->start();
    $sessionDump = $app->session->dump();
    $app->view->add("layout", ["title" => "Sessionsdump"], "layout");
    $app->view->add("session/dump", ["dump" => $sessionDump], "main");

    $app->response->setBody($app->view->renderBuffered("layout"))
        ->send();
});

$app->router->add('session/destroy', function () use ($app) {
    $app->session->start();
    $app->session->destroy();

    $app->response->redirect($app->url->create('session/dump'));
});
