<?php
/**
 * Internal routes.
 */


$app->router->addInternal("404", function () use ($app) {
    $body =  "<!doctype html>
<meta charset=\"utf-8\">
<title>404</title>
<h1>404 Hitta inte...</h1>
<p>Försök med nått annat :)</p>";

    $app->response->setBody($body)
                  ->send(404);
});
