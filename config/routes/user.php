<?php


$app->router->add('user/register', function () use ($app) {
    $app->view->add("layout", ["title" => "Ny användare"], "layout");
    $app->view->add("user/register", [], "main");

    $app->response->setBody($app->view->renderBuffered("layout"))
        ->send();
});

$app->router->add('user/profile', function () use ($app) {
    // Happy function
    $showProfile = function ($user) use ($app) {
        $userDb = new _404\Database\Users($app->dbconnection);

        $viewData = $userDb->getDetails($user);

        $viewData['edit'] = $app->get->maybe('edit')
            ->filter(function ($value) {
                return $value === 'true';
            })
            ->withDefault(false);


        $app->view->add("layout", ["title" => "Användarprofil"], "layout");
        $app->view->add("user/profile", $viewData, "main");

        $app->response->setBody($app->view->renderBuffered("layout"))
            ->send();
    };

    // No user logged in function
    $redirectOnNotLoggedIn = function ($error) use ($app) {
        $errQuery = urlencode($error);
        $app->redirect("errorwithinfofromget?login=show&error=$errQuery");
//        $app->redirect("");
    };

    $app->session->either('user')
        ->map(function ($user) use ($app) {
            return $app->get->maybe('newuser')->withDefault($user);
        })
        ->resolve($showProfile, $redirectOnNotLoggedIn);
});

$app->router->add('user/passwordchange', function () use ($app) {
    $showPasswordChange = function ($username) use ($app) {
        $app->view->add("layout", ["title" => "Ändra lösenord"], "layout");
        $app->view->add("user/passwordchange", ['username' => $username], "main");

        $app->response->setBody($app->view->renderBuffered("layout"))
            ->send();
    };

    $redirectOnNotLoggedIn = function ($error) use ($app) {
        $errQuery = urlencode($error);
        $app->redirect("errorwithinfofromget?error=$errQuery");
    };

    $app->session->either('user')
        ->resolve($showPasswordChange, $redirectOnNotLoggedIn);
});

$app->router->add('user/passwordchangesuccess', function () use ($app) {
    $showSuccess = function () use ($app) {
        $app->view->add("layout", ["title" => "Lösenordet ändrat"], "layout");
        $app->view->add("user/passwordchangesuccess", [], "main");

        $app->response->setBody($app->view->renderBuffered("layout"))
            ->send();
    };

    $redirectOnNotLoggedIn = function ($error) use ($app) {
        $errQuery = urlencode($error);
        $app->redirect("errorwithinfofromget?error=$errQuery");
    };

    $app->session->either('user')
        ->resolve($showSuccess, $redirectOnNotLoggedIn);
});
