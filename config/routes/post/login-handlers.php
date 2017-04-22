<?php

/**
 * Login/logout handlers
 */

$app->router->add('handle/login', function () use ($app) {
    $userDb = new _404\Database\Users($app->dbconnection);

    $onLoginAttempt = function () use ($app, $userDb) {
        $notEmpty = function ($value) {
            return ! empty($value);
        };

        $password = $app->post->maybe('password')
            ->map('trim')
            ->filter('htmlentities')
            ->withDefault("");

        $login = $app->post->either('user')
            ->map('trim')
            ->map('htmlentities')
            ->filter($notEmpty, "Username Empty.")
            ->filter([$userDb, 'exists'], "Username not found")
            ->filter(function ($username) use ($password, $userDb) {
                $dbHash = $userDb->getHash($username);
                return password_verify($password, $dbHash);
            }, "Password mismatch");


        $login->resolve(
            function ($username) use ($app) {
                $app->session->set('user', $username);
                $app->redirectBack();
            },
            function ($error) use ($app) {
                $errQuery = urlencode($error);
                $app->redirect("errorwithinfofromget?login=show&error=$errQuery");
            }
        );
    };

    $otherwise = function () use ($app) {
        $app->redirectBack();
    };

    $app->post->either('login')
        ->filter(function ($button) {
            return $button == 'attempt';
        }, '')
        ->resolve($onLoginAttempt, $otherwise);
});

$app->router->add('handle/logout', function () use ($app) {
    $onLogout = function () use ($app) {
        $loggedIn =  $app->session->maybe('user');
        if ($loggedIn->isJust()) {
            $app->session->destroy();
        }
        $app->redirect('');
    };

    $onCancel = function () use ($app) {
        $app->redirectBack();
    };

    $app->post->either('logout')
        ->filter(function ($button) {
            return $button == "logout";
        }, '')
        ->resolve($onLogout, $onCancel);
});
