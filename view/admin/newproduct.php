<div class="container big-horisontal-margins">
    <h2>Ny Produkt</h2>
    <form class="form-horizontal" method="post" action="<?= $app->url->create('admin/handle/webshop/new') ?>">
        <div class="form-group">
            <label class="control-label col-sm-2">Produkt: </label>
            <div class="col-sm-4">
                <input autofocus type="text" name="description" class="form-control"/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2">Produktkategori: </label>
            <div class="col-sm-4">
                <input autofocus type="text" name="category_description" class="form-control"/>
            </div>
        </div>
        <hr>
        <button name="save" value="true" class="btn btn-primary pull-right">Spara</button>
        <a href="<?= $app->previousRoute(); ?>" class="btn btn-warning pull-right right-margin-2rem">Avbryt</a>
    </form>
</div>
