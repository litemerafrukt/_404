<?php


$app->router->add('user/register', function () use ($app) {
    $app->view->add("layout", ["title" => "Ny användare"], "layout");
    $app->view->add("user/register", [], "main");

    $app->response->setBody($app->view->renderBuffered("layout"))
        ->send();
});

$app->router->add('user/profile', function () use ($app) {
    // Happy function
    $showProfile = function ($username) use ($app) {
        $userDb = new _404\Database\Users($app->dbconnection);

        $viewData = $userDb->getDetails($username);

        $viewData['edit'] = $app->get->maybe('edit')
            ->filter(function ($value) {
                return $value === 'true';
            })
            ->withDefault(false);

        $viewData['isAdmin'] = $app->user->isAdmin();

        $app->view->add("layout", ["title" => "Användarprofil"], "layout");
        $app->view->add("user/profile", $viewData, "main");

        $app->response->setBody($app->view->renderBuffered("layout"))
            ->send();
    };

    // Decide
    $app->user->eitherAdminNameOr('Du behöver admin-behörighet')
        ->map(function ($adminName) use ($app) {
            return $app->get->maybe('user')->withDefault($adminName);
        })
        ->orElse($app->user->eitherUserNameOr('Du är inte inloggad'))
        ->resolve($showProfile, [$app, 'stdErr']);
});

$app->router->add('user/passwordchange', function () use ($app) {
    $showPasswordChange = function ($username) use ($app) {
        $app->view->add("layout", ["title" => "Ändra lösenord"], "layout");
        $app->view->add("user/passwordchange", ['username' => $username], "main");

        $app->response->setBody($app->view->renderBuffered("layout"))
            ->send();
    };

    $app->user->eitherUserNameOr('Du är inte inloggad')
        ->resolve($showPasswordChange, [$app, 'stdErr']);
});

$app->router->add('user/passwordchangesuccess', function () use ($app) {
    $showSuccess = function () use ($app) {
        $app->view->add("layout", ["title" => "Lösenordet ändrat"], "layout");
        $app->view->add("user/passwordchangesuccess", [], "main");

        $app->response->setBody($app->view->renderBuffered("layout"))
            ->send();
    };

    $app->user->eitherUserOr('Du är inte inloggad.')
        ->resolve($showSuccess, [$app, 'stdErr']);
});
