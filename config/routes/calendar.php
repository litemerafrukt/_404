<?php

/**
 * Calendar routes.
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

    $calendar = new \_404\Components\Calendar\WallCalendar($year, $month);
    $calendar->setApp($app);
    $calendar->configure("calendar.php");

    $calendarOutput = $calendar->fullCalendar();

    $app->view->add("layout", ["title" => "Kalender"], "layout");
    $app->view->add("calendar", ['calendar' => $calendarOutput], "main");

    $app->response->setBody($app->view->renderBuffered("layout"))
        ->send();
});
