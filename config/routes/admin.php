<?php

$app->router->add('admin/users', function () use ($app) {
    $userDb = new _404\Database\Users($app->dbconnection);

    $showUserAdmin = function () use ($app) {
        $app->view->add("layout", ["title" => "Administrera användare"], "layout");
        $app->view->add("admin/users", [], "main");

        $app->response->setBody($app->view->renderBuffered("layout"))
            ->send();
    };

    $notOk = function ($error) use ($app) {
        $errQuery = urlencode($error);
        $app->redirect("errorwithinfofromget?login=show&error=$errQuery");
    };

    // User level admin?
    $app->session->either('user')
        ->filter(function ($user) use ($userDb) {
            return $userDb->exists($user);
        }, 'No valid user')
        ->filter(function ($user) use ($userDb) {
            return _404_APP_ADMIN_LEVEL == $userDb->getLevel($user);
        }, 'Not admin')
        ->resolve($showUserAdmin, $notOk);
});
