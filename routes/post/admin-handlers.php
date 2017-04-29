<?php

$app->router->add('admin/handle/deleteuser/{username}', function ($username) use ($app, $tlz) {
    $userDb = new _404\Database\Users($app->dbconnection);

    // Happy path
    $deleteUser = function ($username) use ($app, $userDb) {
        $userDb->delete($username);
        return $app->setRedirectBack();
    };

    // Check admin and resolve
    return $tlz->eitherEmpty($username, 'Tomt användarnamn.')
        ->filter(function ($username) use ($app) {
            return ! ($username == $app->user->name());
        }, 'Du kan inte radera dig själv.')
        ->filter([$userDb, 'exists'], 'Användaren hittades inte i databasen.')
        ->resolve($deleteUser, [$app, 'stdErr']);
});


$app->router->add('admin/handle/passwordchange', function () use ($app) {
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
        return $app->setRedirect('admin/passwordchangesuccess');
    };

    return $app->post->either('username')
        ->filter([$userDb, 'exists'], 'Användaren hittades inte i databasen.')
        ->filter($passwordMatch, 'Lösenorden matchar inte')
        ->resolve($passwordChange, [$app, 'stdErr']);
});
