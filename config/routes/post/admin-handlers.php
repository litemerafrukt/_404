<?php

$app->router->add('handle/admin/deleteuser', function () use ($app) {
    $userDb = new _404\Database\Users($app->dbconnection);

    // Happy path
    $deleteUser = function ($userId) use ($app, $userDb) {
        $userDb->delete($userId);
        $app->redirectBack();
    };

    // Check admin and resolve
    $app->get->either('user')
        ->filter([$app->user->eitherAdminOr(''), 'isRight'], 'Du har inte adminstatus')
        ->filter(function ($formUsername) use ($app) {
            return ! ($formUsername == $app->user->name());
        }, 'Du kan inte radera dig själv.')
        ->filter(function ($formUsername) use ($userDb) {
            return $userDb->exists($formUsername);
        }, 'Användaren hittades inte i databasen.')
        ->resolve($deleteUser, [$app, 'stdErr']);
});


$app->router->add('handle/admin/passwordchange', function () use ($app) {
    $userDb = new _404\Database\Users($app->dbconnection);

    $newPasswordMaybe = $app->post->maybe('new-password-1')
        ->filter(function ($newPassword1) use ($app) {
            return $newPassword1 === $app->post->maybe('new-password-2')->withDefault(false);
        });

    // Helperfilter
    $passwordMatch = function () use ($newPasswordMaybe) {
        return $newPasswordMaybe->isJust();
    };

    // Happy path
    $passwordChange = function ($formUsername) use ($app, $userDb, $newPasswordMaybe) {
        $newPassword = password_hash($newPasswordMaybe->withDefault(''), PASSWORD_DEFAULT);
        $userDb->changePassword($formUsername, $newPassword);
        $app->redirect('admin/passwordchangesuccess');
    };

    $app->post->either('username')
        ->filter([$app->user->eitherAdminOr(''), 'isRight'], 'Du har inte adminstatus.')
        ->filter($passwordMatch, 'Lösenorden matchar inte')
        ->resolve($passwordChange, [$app, 'stdErr']);
});

