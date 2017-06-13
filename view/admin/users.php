<?php

/**
 * TODO: These helpers should maybe be in a class?
 */


/**
 * Helper to create links for sorting table.
 *
 * @param $column - database column
 * @param $route - route to prepend
 * @return string
 */
function orderby($column, $route)
{
    $asc = mergeQueryString(["orderby" => $column, "order" => "asc"], $route);
    $desc = mergeQueryString(["orderby" => $column, "order" => "desc"], $route);
    return "
        <span class='orderby'>
            <a href='$asc'>&darr;</a>
            <a href='$desc'>&uarr;</a>
        </span>
    ";
}

/**
 * Helper to build querystring from current query.
 *
 * @param $options
 * @param string $prepend
 * @return string
 */
function mergeQueryString($options, $prepend = "?")
{
    // Parse querystring into array
    $query = [];
    parse_str($_SERVER["QUERY_STRING"], $query);

    // Merge query string with new options
    $query = array_merge($query, $options);

    // Build and return the modified querystring as url
    return $prepend . http_build_query($query);
}

$adminUserRoute = $app->url->create("admin/users?")
?>

<div class="container">
    <h2>Administrera användare</h2>
    <hr>
    <a href="<?= $app->url->create('user/register') ?>">Ny användare</a>
    <hr>
    <form class="form-horizontal" method="get" action="<?= $app->url->create('admin/users'); ?>">
        <div class="form-group"><label class="control-label col-sm-1">SQL-sökning: </label>
            <div class="col-sm-3">
                <input type="text" name="filter" class="form-control" value="<?= htmlentities($dbFilter) ?>"/>
            </div>
            <button class="btn btn-primary">Sök</button>
            <a class="btn btn-primary" href="<?= $app->url->create('admin/users') ?>">Alla</a>
        </div>
    </form>

    <hr>

    <p>
        <span class="right-margin-2rem">Användare per sida:</span>
        <a class="right-divider" href="<?= mergeQueryString(["hits" => 2], $adminUserRoute) ?>">2</a>
        <a class="right-divider" href="<?= mergeQueryString(["hits" => 4], $adminUserRoute) ?>">4</a>
        <a class="right-divider active" href="<?= mergeQueryString(["hits" => 8], $adminUserRoute) ?>">8</a>
        <a class="" href="<?= mergeQueryString(["hits" => 100], $adminUserRoute) ?>">100</a>
    </p>


    <table class="table table-striped">
        <thead>
        <tr>
            <th># <?= orderby("id", $adminUserRoute) ?></th>
            <th>Användare <?= orderby("username", $adminUserRoute) ?></th>
            <th>Email <?= orderby("email", $adminUserRoute) ?></th>
            <th>Level <?= orderby("userlevel", $adminUserRoute) ?></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user) : ?>
            <tr>
                <td><?= $user->id ?></td>
                <td><?= $user->username ?></td>
                <td><?= $user->email ?></td>
                <td><?= $user->userlevel ?></td>
                <td><a href="<?= $app->url->create("user/profile?edit=true&user=$user->username"); ?>">Redigera</a></td>
                <td><a href="<?= $app->url->create("admin/passwordchange/{$user->username}"); ?>">Ändra lösenord</a></td>
                <td><a href="<?= $app->url->create("admin/handle/deleteuser/$user->username") ?>">Ta bort</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <p>
        <span class="right-margin-2rem">Sida: </span>
        <?php for ($i = 1; $i <= $nrOfPages; $i++) : ?>
            <a class="right-margin-2rem" href="<?= mergeQueryString(["page" => $i], $adminUserRoute) ?>"><?= $i ?></a>
        <?php endfor; ?>
    </p>

    <br>

</div>
