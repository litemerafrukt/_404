<?php

//$adminGuard = function () use ($app) {
//    if ($app->user->isAdmin()) {
//        return $app->router->handleInternal($app->request->getRoute());
//    }
//
//    return $app->setRedirect("");
//};
//
//$app->router->add('admin', $adminGuard);
//$app->router->add('admin/{}', $adminGuard);
//$app->router->add('admin/{}/{}', $adminGuard);
//$app->router->add('admin/{}/{}/{}', $adminGuard);
if (! $app->user->isAdmin()) {
    $error = $tlz->partial([$app, 'stdErr'], "Du är inte admin.");
    $app->router->add('admin', $error);
    $app->router->add('admin/{}', $error);
    $app->router->add('admin/{}/{}', $error);
    $app->router->add('admin/{}/{}/{}', $error);
};

if ($app->user->isAdmin()) {
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

            return $app->response->setBody($app->view->renderBuffered("layout"));
        };

        return $showUsers();
        //    return $app->user->eitherAdminOr('Du har inte adminstatus.')
        //        ->resolve($showUsers, [$app, 'stdErr']);
    });

    $app->router->addInternal('admin/passwordchange', function () use ($app) {
        $showPasswordChange = function ($username) use ($app) {
            $app->view->add("layout", ["title" => "Ändra lösenord"], "layout");
            $app->view->add("admin/passwordchange", ['username' => $username], "main");

            return $app->response->setBody($app->view->renderBuffered("layout"));
        };

        $userMaybe = $app->get->maybe('user');

        return $app->user->eitherAdminOr('Du har inte adminstatus.')
            ->filter([$userMaybe, 'isJust'], 'Fel användarnamn.')
            ->resolve(function () use ($app, $userMaybe, $showPasswordChange) {
                $username = $userMaybe->withDefault($app->user->name());
                return $showPasswordChange($username);
            }, [$app, 'stdErr']);
    });

    $app->router->addInternal('admin/passwordchangesuccess', function () use ($app) {
        $showSuccess = function () use ($app) {
            $app->view->add("layout", ["title" => "Lösenordet ändrat"], "layout");
            $app->view->add("admin/passwordchangesuccess", [], "main");

            return $app->response->setBody($app->view->renderBuffered("layout"));
        };

        return $app->user->eitherAdminOr('Du är inte admin.')
            ->resolve($showSuccess, [$app, 'stdErr']);
    });
}
