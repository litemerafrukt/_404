<?php

$app->router->add('errorwithinfofromget', function () use ($app) {
    $onError = function ($errorMsg) use ($app) {
        $app->view->add("layout", ["title" => "Error"], "layout");
        $app->view->add("showgeterror", compact('errorMsg'), "main");

        $app->response->setBody($app->view->renderBuffered("layout"))
            ->send();
    };

    $noError = function () use ($app) {
        $app->redirect("");
    };

    $app->get->either('error')
        ->map('htmlentities')
        ->resolve($onError, $noError);
});
