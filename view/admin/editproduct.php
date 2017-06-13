<div class="container">
    <h2><?= $product->description; ?></h2>
    <hr>
    <form class="form-horizontal" method="post" action="<?= $app->url->create('admin/handle/webshop/edit') ?>">
        <div class="form-group">
            <label class="control-label col-sm-1">Produkt: </label>
            <div class="col-sm-4">
                <input type="text" name="description" class="form-control" value="<?= $product->description; ?>"/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-1">Bild: </label>
            <div class="col-sm-4">
                <input type="text" name="image_path" class="form-control" value="<?= $product->image_path; ?>"/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-1">Pris: </label>
            <div class="col-sm-4">
                <input type="text" name="price" class="form-control" value="<?= $product->price; ?>"/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-1">Kategori: </label>
            <div class="col-sm-4">
                <input type="text" name="category_description" class="form-control" value="<?= $product->category_description; ?>"/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-1">Lager: </label>
            <div class="col-sm-4">
                <input type="text" name="inventory" class="form-control" value="<?= $product->inventory; ?>"/>
            </div>
        </div>
        <div class="form-group">
            <input type="hidden" name="id" value="<?= $product->id ?>"/>
        </div>
        <hr>
        <button class="btn btn-primary pull-right">Spara</button>
        <a href="<?= $app->url->create("admin/webshop"); ?>" class="btn btn-warning pull-right right-margin-2rem">Till webshop</a>
    </form>
</div>
<br>
