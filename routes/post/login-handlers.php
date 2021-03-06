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
            ->filter($notEmpty, "Inget användarnamn.")
            ->filter([$userDb, 'exists'], "Användaren hittades inte.")
            ->filter(function ($username) use ($password, $userDb) {
                $dbHash = $userDb->getHash($username);
                return password_verify($password, $dbHash);
            }, "Felaktigt lösenord.");


        return $login->resolve(
            function ($username) use ($app, $userDb) {
                $userDetails = $userDb->getDetails($username);
                $user = new _404\User\User(
                    $userDetails['username'],
                    $userDetails['email'],
                    $userDetails['userlevel']
                );
                $app->session->set('user', $user);
                return $app->setRedirectBack();
            },
            function ($error) use ($app) {
                $errQuery = urlencode($error);
                return $app->setRedirect("errorwithinfofromget?login=show&error=$errQuery");
            }
        );
    };

    $onCancel = function () use ($app) {
        return $app->setRedirectBack();
    };

    return $app->post->either('login')
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
        return $app->setRedirect('');
    };

    $onCancel = function () use ($app) {
        return $app->setRedirectBack();
    };

    return $app->post->either('logout')
        ->filter(function ($button) {
            return $button == "logout";
        }, '')
        ->resolve($onLogout, $onCancel);
});
