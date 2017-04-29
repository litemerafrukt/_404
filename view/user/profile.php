<div class="container big-horisontal-margins">
    <h2><?= $username ?> - profil</h2>

    <?php if ($edit) : ?>

        <form class="form-horizontal" method="post" action="<?= $app->url->create('user/handle/edit') ?>">
            <div class="form-group">
                <label class="control-label col-sm-1">Email: </label>
                <div class="col-sm-4">
                    <input type="text" name="email" class="form-control" value="<?= $email; ?>" />
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-1">Level: </label>
                <div class="col-sm-4">
                    <input required type="number" min="1" max="2" name="userlevel" class="form-control" value="<?= $userlevel ?>" />
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-1">Kaka: </label>
                <div class="col-sm-4">
                    <input type="text" name="cookie" class="form-control" placeholder="Sätt kaka" value="<?= htmlentities($app->cookie->maybe($username)->withDefault('')) ?>" />
                </div>
            </div>
            <div class="form-group">
                <input type="hidden" name="username" value="<?= $username ?>" />
            </div>
            <hr>
            <button class="btn btn-primary pull-right">Spara</button>
            <a href="<?= $app->previousRoute(); ?>" class="btn btn-warning pull-right right-margin-2rem">Avbryt</a>
        </form>

    <?php else : ?>

        <div class="row">
            <h5><span class="col-sm-2">Email: </span><span class="col-sm-10"><?= $email ?></span></h5>
        </div>
        <div class="row">
            <h5><span class="col-sm-2">Level: </span><span class="col-sm-10"><?= $userlevel ?></span></h5>
        </div>
        <div class="row">
            <h5><span class="col-sm-2">Kaka: </span><span class="col-sm-10"><?= htmlentities($app->cookie->maybe($username)->withDefault('Sätt din kaka på "Redigera profil"')) ?></span></h5>
        </div>
        <hr>
        <a href="<?php
            $queryName = urlencode($username);
            echo $app->url->create("user/profile?edit=true&user=$queryName");
        ?>">Redigera profil</a>

        <?php if ($isAdmin) : ?>
            <br>
            <a href="<?= $app->url->create('admin/users') ?>">Till admin</a>
        <?php endif ?>

    <?php endif ?>

</div>
