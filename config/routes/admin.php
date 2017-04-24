<?php

$app->router->add('admin/users', function () use ($app) {
    $userDb = new _404\Database\Users($app->dbconnection);

    $showUsers = function () use ($app, $userDb) {
        $dbFilter = $app->get->maybe('filter')
            ->map('trim')
            ->filter(function ($filter) {
                return $filter != '';
            })
            ->withDefault('%');

        $columns = ["id", "username", "email", "userlevel"];
        $orders = ["asc", "desc"];



        $orderBy = $app->get->maybe('orderby')
            ->filter(function ($orderBy) use ($columns) {
                return in_array($orderBy, $columns);
            })
            ->withDefault('id');

        $order = $app->get->maybe('order')
            ->filter(function ($order) use ($orders) {
                return in_array($order, $orders);
            })
            ->withDefault('asc');

        $limit = $app->get->maybe('hits')
            ->filter('is_numeric')
            ->map(function ($limit) {
                return $limit < 0 ? 1 : $limit;
            })
            ->withDefault(1000);

        $offset = $app->get->maybe('page')
            ->filter('is_numeric')
            ->map(function ($page) use ($limit) {
                return ($page - 1) * $limit;
            })
            ->withDefault(0);

        $users = $userDb->getUsers($dbFilter, $orderBy, $order, $limit, $offset);
        $nrOfPages = ceil($userDb->countWithFilter($dbFilter) / $limit);

        $viewData = compact('users', 'dbFilter', 'nrOfPages');

        $app->view->add("layout", ["title" => "Administrera användare"], "layout");
        $app->view->add("admin/users", $viewData, "main");

        $app->response->setBody($app->view->renderBuffered("layout"))
            ->send();
    };

    $app->user->eitherAdminOr('Du har inte adminstatus.')
        ->resolve($showUsers, [$app, 'stdErr']);
});

$app->router->add('admin/passwordchange', function () use ($app) {
    $showPasswordChange = function ($username) use ($app) {
        $app->view->add("layout", ["title" => "Ändra lösenord"], "layout");
        $app->view->add("admin/passwordchange", ['username' => $username], "main");

        $app->response->setBody($app->view->renderBuffered("layout"))
            ->send();
    };

    $userMaybe = $app->get->maybe('user');

    $app->user->eitherAdminOr('Du har inte adminstatus.')
        ->filter([$userMaybe, 'isJust'], 'Fel användarnamn.')
        ->resolve(function () use ($app, $userMaybe, $showPasswordChange) {
            $username = $userMaybe->withDefault($app->user->name());
            $showPasswordChange($username);
        }, [$app, 'stdErr']);
});

$app->router->add('admin/passwordchangesuccess', function () use ($app) {
    $showSuccess = function () use ($app) {
        $app->view->add("layout", ["title" => "Lösenordet ändrat"], "layout");
        $app->view->add("admin/passwordchangesuccess", [], "main");

        $app->response->setBody($app->view->renderBuffered("layout"))
            ->send();
    };

    $app->user->eitherAdminOr('Du är inte admin.')
        ->resolve($showSuccess, [$app, 'stdErr']);
});
