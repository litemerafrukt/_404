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

/************************************************
* webshop
************************************************/
$app->router->add('admin/handle/webshop/edit', function () use ($app, $tlz) {
    $productDb = new \_404\Database\Products($app->dbconnection);

    // Form data
    $formEithers = [];

    $prodIdExists = $tlz->partial([$productDb, 'exists'], 'id');

    $formEithers['id'] = $app->post->either('id')
        ->filter('is_numeric', "Produkt id är inte numeriskt.")
        ->filter($prodIdExists, "Produktens id finns inte i databasen.");

    $formEithers['description'] = $app->post->either('description')
        ->map('trim')
        ->filter('is_string', "Felaktig produktbeskrivning.");

    $formEithers['image_path'] = $app->post->either('image_path')
        ->map('trim')
        ->filter('is_string', "Felaktig bildsökväg.");

    $formEithers['price'] = $app->post->either('price')
        ->filter('is_numeric', "Pris måste vara numeriskt.");

    $formEithers['category_description'] = $app->post->either('category_description')
        ->map('trim')
        ->map(function ($catDesc) {
            return $catDesc == "" ? 'okategoriserat' : $catDesc;
        });

    $formEithers['inventory'] = $app->post->either('inventory')
        ->filter('is_numeric', "Lager måste vara numeriskt.");

    $valuesEither = $tlz->combineEither($formEithers);

    // var_dump($tlz->combineEither($formEithers));
    // die();

    return $valuesEither->resolve(
        function ($values) use ($app, $productDb) {
            $productDb->update(
                $values['id'],
                $values['description'],
                $values['image_path'],
                $values['price'],
                $values['category_description'],
                $values['inventory']
            );
            return $app->setRedirectBack();
        },
        [$app, 'stdErr']
    );
});

$app->router->add('admin/handle/webshop/delete/{productId}', function ($productId) use ($app, $tlz) {
    $productDb = new _404\Database\Products($app->dbconnection);

    // Happy path
    $deleteContent = function ($productId) use ($app, $productDb) {
        $productDb->delete($productId);
        return $app->setRedirectBack();
    };

    $prodIdExists = $tlz->partial([$productDb, 'exists'], 'id');

    // Check and resolve
    return $tlz->eitherEmpty($productId, 'Inget produkt-id.')
        ->filter($prodIdExists, "Produktens id finns inte i databasen.")
        ->resolve($deleteContent, [$app, 'stdErr']);
});

$app->router->add('admin/handle/webshop/new', function () use ($app, $tlz) {
    $productDb = new _404\Database\Products($app->dbconnection);

    $formEithers = [];

    $formEithers['description'] = $app->post->either('description')
        ->map('trim')
        ->filter(function ($desc) {
            return !empty($desc);
        }, 'Produktbeskrivning saknas');

    $formEithers['category_description'] = $app->post->either('category_description')
        ->map('trim')
        ->map(function ($catDesc) {
            return $catDesc == "" ? 'okategoriserat' : $catDesc;
        });

    $valuesEither = $tlz->combineEither($formEithers);

    $createAndRedirect = function ($values) use ($productDb, $app) {
        $id = $productDb->newProduct($values['description'], $values['category_description']);
        return $app->setRedirect("admin/webshop/edit/$id");
    };

    return $valuesEither->resolve($createAndRedirect, [$app, 'stdErr']);
});
