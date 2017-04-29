<div class="container big-horisontal-margins">
    <form class="form-horizontal" method="post" action="<?= $app->url->create('admin/handle/passwordchange') ?>">
        <h2>Ändra lösenord för: <?= $username ?></h2>
        <div class="form-group">
            <label class="control-label col-sm-2">Nytt lösenord: </label>
            <div class="col-sm-4">
                <input type="password" name="new-password-1" class="form-control"/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2">Repetera: </label>
            <div class="col-sm-4">
                <input type="password" name="new-password-2" class="form-control"/>
            </div>
        </div>
        <div class="form-group">
            <input type="hidden" name="username" value="<?= $username ?>"/>
        </div>
        <hr>
        <button class="btn btn-primary pull-right">Spara</button>
        <a href="<?= $app->previousRoute(); ?>" class="btn btn-warning pull-right right-margin-2rem">Avbryt</a>
    </form>
</div>
