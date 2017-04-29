<?php

$app->router->add('errorwithinfofromget', function () use ($app) {
    $onError = function ($errorMsg) use ($app) {
        $app->view->add("layout", ["title" => "Error"], "layout");
        $app->view->add("errors/showgeterror", compact('errorMsg'), "main");

        return $app->response->setBody($app->view->renderBuffered("layout"));
    };

    $noError = function () use ($app) {
        return $app->setRedirect("");
    };

    return $app->get->either('error')
        ->map('htmlentities')
        ->resolve($onError, $noError);
});

