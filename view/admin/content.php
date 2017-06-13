<div class="container">
    <h2>Administrera innehåll</h2>
    <hr>
    <a href="<?= $app->url->create('admin/content/new') ?>">Nytt innehåll</a>
    <hr>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>#</th>
            <th>Titel</th>
            <th>Typ</th>
            <th>Path</th>
            <th>Slug</th>
            <th>Skapad</th>
            <th>Publicerad</th>
            <th>Uppdaterad</th>
            <th>Borttagen</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($contents as $content) : ?>
            <tr class="<?= $content->deleted ? 'content-deleted' : '' ?>">
                <td><?= $content->id ?></td>
                <td><?= $content->title ?></td>
                <td><?= $content->type ?></td>
                <td><?= $content->path ?></td>
                <td><?= $content->slug ?></td>
                <td><?= $content->created ?></td>
                <td><?= $content->published ?></td>
                <td><?= $content->updated ?></td>
                <td><?= $content->deleted ?></td>
                <td><a href="<?= $app->url->create("admin/content/edit/$content->id") ?>">Redigera</a><br>
                <a href="<?= $app->url->create("admin/handle/content/delete/$content->id") ?>">Radera</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <br>

</div>
