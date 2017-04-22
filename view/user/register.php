<div class="container big-horisontal-margins">
    <h2>Ny användare</h2>
    <form class="form-horizontal" method="post" action="<?= $app->url->create('handle/user/register') ?>">
        <div class="form-group">
            <label class="control-label col-sm-2">Användarnamn: </label>
            <div class="col-sm-4">
                <input type="text" name="username" class="form-control"/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2">Lösenord: </label>
            <div class="col-sm-4">
                <input type="password" name="password-1" class="form-control"/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2">Repetera lösenord: </label>
            <div class="col-sm-4">
                <input type="password" name="password-2" class="form-control"/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2">Email: </label>
            <div class="col-sm-4">
                <input type="text" name="email" class="form-control"/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2">Level: </label>
            <div class="col-sm-4">
                <input type="number" min="1" max="3" name="userlevel" value="3" class="form-control" />
            </div>
        </div>
        <hr>
        <button name="save" value="true" class="btn btn-primary pull-right">Spara</button>
        <a href="<?= $app->previousRoute(); ?>" class="btn btn-warning pull-right right-margin-2rem">Avbryt</a>
    </form>
</div>
