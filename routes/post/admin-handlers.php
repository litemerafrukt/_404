<?php

$app->router->add('admin/handle/deleteuser/{username}', function ($username) use ($app, $tlz) {
    $userDb = new _404\Database\Users($app->dbconnection);

    // Happy path
    $deleteUser = function ($username) use ($app, $userDb) {
        $userDb->delete($username);
        return $app->setRedirectBack();
    };

    // Check and resolve
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

$app->router->add('admin/handle/content/edit', function () use ($app, $tlz) {
    $contentDb = new \_404\Database\Content($app->dbconnection);

    // Form data
    $form = [];

    $form['id'] = $app->post->either('id')
        ->filter('is_numeric', "Id är inte numeriskt.")
        ->filter([$contentDb, 'idExists'], "Innehållets id finns inte i databasen.");

    $form['path'] = $app->post->either('path')
        ->map('trim')
        ->map(function ($path) {
            return $path == '' ? null : $path;
        });

    $form['slug'] = $app->post->either('slug')
        ->map('trim')
        ->map(function ($slug) {
            return $slug == '' ? null : $slug;
        });

    $form['title'] = $app->post->either('title')
        ->map('trim');

    $form['type'] = $app->post->either('type')
        ->map('trim');

    // Map empty date to null
    $form['published'] = $app->post->either('published')
        ->map('trim')
        ->map(function ($datetime) {
            if (! empty($datetime)) {
                try {
                    return (new DateTime($datetime))->format("Y-m-d H:i:s");
                } catch (Exception $e) {
                    return null;
                }
            }
            return null;
        });

    // Map empty date to null
    $form['deleted'] = $app->post->either('deleted')
        ->map('trim')
        ->map(function ($datetime) {
            if (! empty($datetime)) {
                try {
                    return (new DateTime($datetime))->format("Y-m-d H:i:s");
                } catch (Exception $e) {
                    return null;
                }
            }
            return null;
        });

    $form['filter'] = $app->post->either('filter')
        ->map('trim');

    $form['data'] = $app->post->either('data');

    // Combine to an either with array
    $eitherFormArr = $tlz->combineEither($form);

    // Create slug if no slug on a post and err if slug is not uniqe
    $eitherFormArr = $eitherFormArr
        ->map(function ($formArr) use ($app) {
            if ((empty($formArr['slug'])) && ($formArr['type'] == 'post')) {
                $formArr['slug'] = $app->url->slugify($formArr['title']);
            }
            return $formArr;
        })
        ->filter(function ($formArr) use ($contentDb) {
            if ($formArr['slug'] == null) {
                return true;
            }
            return (! $contentDb->idFromSlug($formArr['slug'])) || ($formArr['id'] == $contentDb->idFromSlug($formArr['slug']));
        }, 'Sluggen är inte unik.');

    // Err if path is not uniqe
    $eitherFormArr = $eitherFormArr
        ->filter(function ($formArr) use ($contentDb) {
            if ($formArr['path'] == null) {
                return true;
            }
            return (! $contentDb->idFromPath($formArr['path'])) || ($formArr['id'] == $contentDb->idFromPath($formArr['path']));
        }, 'Path är inte unik.');

    return $eitherFormArr->resolve(
        function ($formArr) use ($app, $contentDb) {
            $contentDb->update(
                $formArr['id'],
                $formArr['title'],
                $formArr['path'],
                $formArr['slug'],
                $formArr['type'],
                $formArr['published'],
                $formArr['deleted'],
                $formArr['filter'],
                $formArr['data']
            );
            return $app->setRedirectBack();
        },
        [$app, 'stdErr']
    );
});

$app->router->add('admin/handle/content/delete/{contentId}', function ($contentId) use ($app, $tlz) {
    $contentDb = new _404\Database\Content($app->dbconnection);

    // Happy path
    $deleteContent = function ($contentId) use ($app, $contentDb) {
        $contentDb->delete($contentId);
        return $app->setRedirectBack();
    };

    // Check and resolve
    return $tlz->eitherEmpty($contentId, 'Inget innehålls-id.')
        ->filter([$contentDb, 'idExists'], 'Innehållet hittades inte i databasen.')
        ->resolve($deleteContent, [$app, 'stdErr']);
});

$app->router->add('admin/handle/content/new', function () use ($app) {
    $contentDb = new _404\Database\Content($app->dbconnection);

    $createAndRedirect = function ($title) use ($contentDb, $app) {
        $id = $contentDb->newContent($title);
        return $app->setRedirect("admin/content/edit/$id");
    };

    return $app->post->either('title')
        ->map('trim')
        ->resolve($createAndRedirect, [$app, 'stdErr']);
});
