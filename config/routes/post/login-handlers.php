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
            ->withDefault("");

        $login = $app->post->either('user')
            ->map('trim')
            ->filter($notEmpty, "Username Empty.")
            ->filter([$userDb, 'exists'], "Username not found")
            ->filter(function ($username) use ($password, $userDb) {
                $dbHash = $userDb->getHash($username);
                return password_verify($password, $dbHash);
            }, "Password mismatch");


        $login->resolve(
            function ($username) use ($app, $userDb) {
                $userDetails = $userDb->getDetails($username);
                $user = new _404\User\User(
                    $userDetails['username'],
                    $userDetails['email'],
                    $userDetails['userlevel']
                );
                $app->session->set('user', $user);
                $app->redirectBack();
            },
            function ($error) use ($app) {
                $errQuery = urlencode($error);
                $app->redirect("errorwithinfofromget?login=show&error=$errQuery");
            }
        );
    };

    $onCancel = function () use ($app) {
        $app->redirectBack();
    };

    $app->post->either('login')
        ->filter(function ($button) {
            return $button == 'attempt';
        }, '')
        ->resolve($onLoginAttempt, $onCancel);
});

$app->router->add('handle/logout', function () use ($app) {
    $onLogout = function () use ($app) {
        if ($app->user->isUser()) {
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
