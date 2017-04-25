<?php

$app->router->add('handle/user/register', function () use ($app) {
    $userDb = new _404\Database\Users($app->dbconnection);

    $newUserMaybe = $app->post->maybe('username')
        ->map('trim')
        ->filter(function ($username) {
            return ! empty($username);
        })
        ->filter(function ($username) use ($userDb) {
            return ! $userDb->exists($username);
        });

    $emailMaybe = $app->post->maybe('email')
        ->map('trim')
        ->map('htmlentities');

    $levelMaybe = $app->post->maybe('userlevel')
        ->map('trim')
        ->filter(function ($level) {
            return in_array($level, [_404_APP_ADMIN_LEVEL, _404_APP_USER_LEVEL]);
        });

    $cookieMaybe = $app->post->maybe('cookie');

    $otherPassword = $app->post->maybe('password-2')->map('trim');
    $passwordMaybe = $app->post->maybe('password-1')
        ->map('trim')
        ->filter(function ($password1) use ($otherPassword) {
            return $password1 === $otherPassword->withDefault(false);
        });

    $onOkSaveUser = function () use ($app, $userDb, $newUserMaybe, $passwordMaybe, $emailMaybe, $levelMaybe, $cookieMaybe) {
        $username = $newUserMaybe->withDefault('JohnDoe'); // This should be filtered beforehand
        $password = password_hash($passwordMaybe->withDefault(''), PASSWORD_DEFAULT);
        $email = $emailMaybe->withDefault('');
        $level = $levelMaybe->withDefault(2);

        $userDb->addUser($username, $password, $email, $level);

        // Login user if not logged in
        if (! $app->user->isUser()) {
            $app->session->set(
                'user',
                new \_404\User\User($username, $email, $level)
            );
        }

        // Set cookie
        $cookie = $cookieMaybe->withDefault('Detta är en kaka');
        $app->cookie->set($username, $cookie);

        $urlencodedUsername = urlencode($username);
        return $app->setRedirect("user/profile?user=$urlencodedUsername");
    };

    return $app->post->either('save')
        ->filter([$newUserMaybe, 'isJust'], 'Användarnamnet upptaget.')
        ->filter([$passwordMaybe, 'isJust'], 'Lösenorden matchar inte.')
        ->resolve($onOkSaveUser, [$app, 'stdErr']);
});




$app->router->add('handle/user/edit', function () use ($app) {
    $okEdit = function ($username) use ($app) {
        $userDb = new _404\Database\Users($app->dbconnection);

        $email = $app->post->maybe('email')->withDefault('');
        $userlevel = $app->post->maybe('userlevel')
            ->filter(function ($level) {
                return in_array($level, [_404_APP_ADMIN_LEVEL, _404_APP_USER_LEVEL]);
            })->withDefault(2);
        $cookie = $app->post->maybe('cookie')->withDefault('Kakfel');

        $userDb->setDetails($username, $email, $userlevel);

        // Set cookie
        $app->cookie->set($username, $cookie);
        return $app->setRedirect("user/profile?user=$username");
    };

    // Decide
    return $app->user->eitherAdminNameOr('Du behöver admin-behörighet')
        ->map(function ($adminName) use ($app) {
            return $app->post->maybe('username')->withDefault($adminName);
        })
        ->orElse($app->user->eitherUserNameOr('Du är inte inloggad'))
        ->resolve($okEdit, [$app, 'stdErr']);
});


$app->router->add('handle/user/passwordchange', function () use ($app) {
    $onOkSavePassword = function ($password) use ($app) {
        $userDb = new _404\Database\Users($app->dbconnection);
        $newPassword = password_hash($password, PASSWORD_DEFAULT);
        $userDb->changePassword($app->user->name(), $newPassword);

        return $app->setRedirect('user/passwordchangesuccess');
    };

    $password2Maybe = $app->post->maybe('new-password-2');

    $passwordMatch = function ($password1) use ($password2Maybe) {
        return $password1 === $password2Maybe->withDefault(false);
    };

    return $app->post->either('new-password-1')
        ->filter([$app->user->eitherUserOr(''), 'isRight'], 'Du är inte inloggad.')
        ->filter($passwordMatch, 'Lösenorden matchar inte')
        ->resolve($onOkSavePassword, [$app, 'stdErr']);
});
