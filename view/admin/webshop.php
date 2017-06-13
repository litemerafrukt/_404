<div class="container">
    <h2>Administrera Webshop</h2>
    <hr>
    <a href="<?= $app->url->create('admin/webshop/new') ?>">Ny produkt</a>
    <hr>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>#</th>
            <th>Bild</th>
            <th>Produkt</th>
            <th>Pris</th>
            <th>Kategori</th>
            <th>Lager</th>
            <th>Virtuellt lager</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($products as $product) : ?>
            <tr>
                <td><?= $product->id ?></td>
                <!-- <td><?= $product->image_path ?></td> -->
                <td><a href="../<?= $product->image_path ?>"><img src="../<?= $product->image_path ?>?w=50"></a></td>
                <td><?= $product->description ?></td>
                <td><?= $product->price ?></td>
                <td><?= $product->category_description ?></td>
                <td><?= $product->inventory ?></td>
                <td><?= $product->virtual_inventory ?></td>
                <td><a href="<?= $app->url->create("admin/webshop/edit/$product->id") ?>">Redigera</a><br>
                <a href="<?= $app->url->create("admin/handle/webshop/delete/$product->id") ?>">Radera</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <br>

</div>
