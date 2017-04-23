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

    $notAdmin = function ($error) use ($app) {
        $errQuery = urlencode($error);
        $app->redirect("errorwithinfofromget?login=show&error=$errQuery");
    };

    // User level admin?
    $app->session->either('user')
        ->filter(function ($user) use ($userDb) {
            return $userDb->exists($user);
        }, 'Not a valid user.')
        ->filter(function ($user) use ($userDb) {
            return $userDb->isAdmin($user);
        }, 'You have no admin status.')
        ->resolve($showUsers, $notAdmin);
});

$app->router->add('admin/passwordchange', function () use ($app) {
    $userDb = new _404\Database\Users($app->dbconnection);

    $showPasswordChange = function ($username) use ($app) {
        $app->view->add("layout", ["title" => "Ändra lösenord"], "layout");
        $app->view->add("admin/passwordchange", ['username' => $username], "main");

        $app->response->setBody($app->view->renderBuffered("layout"))
            ->send();
    };

    $someError = function ($error) use ($app) {
        $errQuery = urlencode($error);
        $app->redirect("errorwithinfofromget?error=$errQuery");
    };

    $app->session->either('user')
        ->filter(function ($user) use ($userDb) {
            return $userDb->isAdmin($user);
        }, 'You have no admin status.')
        ->resolve(
            function () use ($app, $showPasswordChange, $someError) {
                $app->get->either('user')
                    ->resolve($showPasswordChange, $someError);
            },
            $someError
        );
});
