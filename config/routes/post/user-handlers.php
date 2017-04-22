<?php

$app->router->add('handle/user/register', function () use ($app) {
    $userDb = new _404\Database\Users($app->dbconnection);

    $newUserMaybe   = $app->post->maybe('username')
        ->map('trim')
        ->map('htmlentities')
        ->filter(function ($username) {
            return ! empty($username);
        });
    $password1Maybe = $app->post->maybe('password-1');
    $password2Maybe = $app->post->maybe('password-2');
    $emailMaybe     = $app->post->maybe('email')
        ->map('trim')
        ->map('htmlentities');
    $levelMaybe     = $app->post->maybe('level')
        ->map('trim')
        ->map('htmlentities');
    $cookieMaybe = $app->post->maybe('cookie');

    $checkPasswords = function () use ($password1Maybe, $password2Maybe) {
        return $password1Maybe->filter(function ($password) use ($password2Maybe) {
            return $password === $password2Maybe->withDefault(false);
        })->withDefault(false);
    };

    $onOkSaveUser = function () use ($app, $userDb, $newUserMaybe, $password1Maybe, $emailMaybe, $levelMaybe, $cookieMaybe) {
        $username = $newUserMaybe->withDefault('JohnDoe'); // This should be filtered beforehand
        $password = password_hash($password1Maybe->withDefault(''), PASSWORD_DEFAULT);
        $email = $emailMaybe->withDefault('');
        $level = $levelMaybe->withDefault(3);
        $userDb->addUser($username, $password, $email, $level);
        // Login user if not logged in
        $app->session->set(
            'user',
            $app->session->maybe('user')->withDefault($username)
        );
        // Set cookie
        $cookie = $cookieMaybe->withDefault('Din kaka kan du justera på "Redigera profil');
        $app->cookie->set($username, $cookie);
        $app->redirect("user/profile?newuser=$username");
    };

    $notOk = function ($error) use ($app) {
        $errQuery = urlencode($error);
        $app->redirect("errorwithinfofromget?error=$errQuery");
    };

    $eitherSave = $app->post->either('save');

    $eitherSave
        ->filter([$newUserMaybe, 'isJust'], 'No username')
        ->filter($checkPasswords, 'Passwords did not match.')
        ->resolve($onOkSaveUser, $notOk);
});




$app->router->add('handle/user/edit', function () use ($app) {
    $userDb = new _404\Database\Users($app->dbconnection);

    $onOkEdit = function ($user) use ($app, $userDb) {
        $email = $app->post->maybe('email')->withDefault('');
        $userlevel = $app->post->maybe('userlevel')->withDefault(3);
        $cookie = $app->post->maybe('cookie')->withDefault('Kakfel');

        $userDb->setDetails($user, $email, $userlevel);

        // Set cookie
        $app->cookie->set($user, $cookie);

        $app->redirectBack();
    };

    $notOk = function ($error) use ($app) {
        $errQuery = urlencode($error);
        $app->redirect("errorwithinfofromget?error=$errQuery");
    };

    $app->session->either('user')
//        ->filter(function ($user) use ($userDb) {
//            return _404_APP_ADMIN_LEVEL == $userDb->getLevel($user);
//        }, 'Wrong user level.')
        ->resolve($onOkEdit, $notOk);
});




$app->router->add('handle/user/passwordchange', function () use ($app) {
    $userDb = new _404\Database\Users($app->dbconnection);

    $newPasswordMaybe = $app->post->maybe('new-password-1')
        ->filter(function ($newPassword1) use ($app) {
            return $newPassword1 === $app->post->maybe('new-password-2')->withDefault(false);
        });

    $passwordMatch = function () use ($newPasswordMaybe) {
        return $newPasswordMaybe->isJust();
    };

    $onOkSavePassword = function ($user) use ($app, $userDb, $newPasswordMaybe) {
        $newPassword = password_hash($newPasswordMaybe->withDefault(''), PASSWORD_DEFAULT);
        $userDb->changePassword($user, $newPassword);
        $app->redirect('user/passwordchangesuccess');
    };

    $notOk = function ($error) use ($app) {
        $errQuery = urlencode($error);
        $app->redirect("errorwithinfofromget?error=$errQuery");
    };

    $loggedIn = $app->session->either('user');

    $loggedIn
        ->filter($passwordMatch, 'New passwords did not match.')
        ->resolve($onOkSavePassword, $notOk);
});
