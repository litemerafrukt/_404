<?php

/**
 * Login/logout handlers
 */

use _404\Globals\Post;
use _404\Globals\Server;

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
                $app->redirect("user/loginfail?$errQuery");
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
    $logout = function () use ($app) {
        if ($app->session->has('user')) {
            $app->session->destroy();
        }
    };

    $cancel = function () {
        return;
    };

    $button = $app->post->either('logout')
        ->filter(function ($button) {
            return $button == "logout";
        }, '');

    $button->resolve($logout, $cancel);

    $app->redirectBack();
});
