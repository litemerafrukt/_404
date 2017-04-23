<?php

$app->router->add('handle/admin/deleteuser', function () use ($app) {
    $userDb = new _404\Database\Users($app->dbconnection);

    // Happy path
    $deleteUser = function ($userId) use ($app, $userDb) {
        $userDb->delete($userId);
        $app->redirectBack();
    };

    // Nope, cant do happy path
    $someError = function ($error) use ($app) {
        $errQuery = urlencode($error);
        $app->redirect("errorwithinfofromget?login=show&error=$errQuery");
    };

    // Check admin and resolve
    $app->session->either('user')
        ->filter(function ($username) use ($userDb) {
            return $userDb->isAdmin($username);
        }, 'You have no admin status.')
        ->resolve(
            function ($username) use ($app, $userDb, $deleteUser, $someError) {
                $app->get->either('user')
                    ->filter(function ($formUsername) use ($username) {
                        return ! ($formUsername == $username);
                    }, "You can't delete yourself.")
                    ->filter(function ($formUsername) use ($userDb) {
                        return $userDb->exists($formUsername);
                    }, 'User not found')
                    ->resolve($deleteUser, $someError);
            },
            $someError
        );
});


$app->router->add('handle/admin/passwordchange', function () use ($app) {
    $userDb = new _404\Database\Users($app->dbconnection);

    $newPasswordMaybe = $app->post->maybe('new-password-1')
        ->filter(function ($newPassword1) use ($app) {
            return $newPassword1 === $app->post->maybe('new-password-2')->withDefault(false);
        });

    // Helper
    $passwordMatch = function () use ($newPasswordMaybe) {
        return $newPasswordMaybe->isJust();
    };

    // Happy path
    $passwordChange = function ($formUsername) use ($app, $userDb, $newPasswordMaybe) {
        $newPassword = password_hash($newPasswordMaybe->withDefault(''), PASSWORD_DEFAULT);
        $userDb->changePassword($formUsername, $newPassword);
        $app->redirect('admin/passwordchangesuccess');
    };

    // Nope, cant do happy path
    $someError = function ($error) use ($app) {
        $errQuery = urlencode($error);
        $app->redirect("errorwithinfofromget?login=show&error=$errQuery");
    };

    // Check admin and resolve
    $app->session->either('user')
        ->filter(function ($username) use ($userDb) {
            return $userDb->isAdmin($username);
        }, 'You have no admin status.')
        ->resolve(
            function () use ($app, $userDb, $passwordMatch, $passwordChange, $someError) {
                $app->post->either('username')
                    ->filter($passwordMatch, 'New passwords did not match.')
                    ->filter(function ($formUsername) use ($userDb) {
                        return $userDb->exists($formUsername);
                    }, 'User not found')
                    ->resolve($passwordChange, $someError);
            },
            $someError
        );
});

$app->router->add('admin/passwordchangesuccess', function () use ($app) {
    $showSuccess = function () use ($app) {
        $app->view->add("layout", ["title" => "Lösenordet ändrat"], "layout");
        $app->view->add("admin/passwordchangesuccess", [], "main");

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
