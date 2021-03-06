<?php

/************************************************
* Admin users
************************************************/

$app->router->add('admin/users', function () use ($app) {
    $userDb = new _404\Database\Users($app->dbconnection);

    $dbFilter = $app->get->maybe('filter')
        ->map('trim')
        ->filter(function ($filter) {
            return $filter != '';
        })
        ->withDefault('%');

    $columns = ["id", "username", "email", "userlevel"];
    $orders = ["asc", "desc"];

    $orderBy = $app->get->maybe('orderby')
        ->filter(function ($orderBy) use ($columns) {
            return in_array($orderBy, $columns);
        })
        ->withDefault('id');

    $order = $app->get->maybe('order')
        ->filter(function ($order) use ($orders) {
            return in_array($order, $orders);
        })
        ->withDefault('asc');

    $limit = $app->get->maybe('hits')
        ->filter('is_numeric')
        ->map(function ($limit) {
            return $limit < 0 ? 1 : $limit;
        })
        ->withDefault(1000);

    $offset = $app->get->maybe('page')
        ->filter('is_numeric')
        ->map(function ($page) use ($limit) {
            return ($page - 1) * $limit;
        })
        ->withDefault(0);

    $users = $userDb->getUsers($dbFilter, $orderBy, $order, $limit, $offset);
    $nrOfPages = ceil($userDb->countWithFilter($dbFilter) / $limit);

    $viewData = compact('users', 'dbFilter', 'nrOfPages');

    $app->view->add("layout", ["title" => "Administrera användare"], "layout");
    $app->view->add("admin/users", $viewData, "main");

    return $app->response->setBody($app->view->renderBuffered("layout"));
});

$app->router->add('admin/passwordchange/{username}', function ($username) use ($app) {
    $app->view->add("layout", ["title" => "Ändra lösenord"], "layout");
    $app->view->add("admin/passwordchange", ['username' => $username], "main");

    return $app->response->setBody($app->view->renderBuffered("layout"));
});

$app->router->add('admin/passwordchangesuccess', function () use ($app) {
    $app->view->add("layout", ["title" => "Lösenordet ändrat"], "layout");
    $app->view->add("admin/passwordchangesuccess", [], "main");

    return $app->response->setBody($app->view->renderBuffered("layout"));
});


/************************************************
* Admin content
************************************************/
$app->router->add('admin/content/new', function () use ($app) {
    $app->view->add("layout", ["title" => "Nytt innehåll"], "layout");
    $app->view->add("admin/newcontent", [], "main");

    return $app->response->setBody($app->view->renderBuffered("layout"));
});

$app->router->add('admin/content', function () use ($app) {
    $contentDb = new _404\Database\Content($app->dbconnection);

    $contents = $contentDb->all();

    $app->view->add("layout", ["title" => "Administrera innehåll"], "layout");
    $app->view->add("admin/content", compact('contents'), "main");

    return $app->response->setBody($app->view->renderBuffered("layout"));
});

$app->router->add('admin/content/edit/{id}', function ($id) use ($app, $tlz) {
    $contentDb = new _404\Database\Content($app->dbconnection);

    $eitherContent = $tlz->eitherEmpty(
        $contentDb->fetch($id),
        "Inget innehåll med id: $id"
    );

    return $eitherContent->resolve(
        function ($content) use ($app) {
            $app->view->add("layout", ["title" => "$content->title"], "layout");
            $app->view->add("admin/editcontent", compact('content'), "main");

            return $app->response->setBody($app->view->renderBuffered("layout"));
        },
        [$app, 'stdErr']
    );
});


/************************************************
* Admin webshop
************************************************/
$app->router->add('admin/webshop', function () use ($app) {
    $productsDb = new _404\Database\Products($app->dbconnection);

    $products = $productsDb->all();

    $app->view->add("layout", ["title" => "Lager"], "layout");
    $app->view->add("admin/webshop", compact('products'), "main");

    return $app->response->setBody($app->view->renderBuffered("layout"));
});

$app->router->add('admin/webshop/new', function () use ($app) {
    $app->view->add("layout", ["title" => "Ny produkt"], "layout");
    $app->view->add("admin/newproduct", [], "main");

    return $app->response->setBody($app->view->renderBuffered("layout"));
});

$app->router->add('admin/webshop/edit/{id}', function ($id) use ($app, $tlz) {
    $productsDb = new _404\Database\Products($app->dbconnection);

    $eitherContent = $tlz->eitherEmpty(
        $productsDb->fetch($id),
        "Ingen produkt med id: $id"
    );

    return $eitherContent->resolve(
        function ($product) use ($app) {
            $app->view->add("layout", ["title" => "$product->description"], "layout");
            $app->view->add("admin/editproduct", compact('product'), "main");

            return $app->response->setBody($app->view->renderBuffered("layout"));
        },
        [$app, 'stdErr']
    );
});
