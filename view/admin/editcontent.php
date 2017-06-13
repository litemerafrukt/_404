<div class="container">
    <h2><?= $content->title; ?></h2>
    <h5>Senast uppdaterad: <?= $content->updated; ?></h5>
    <hr>
    <form class="form-horizontal" method="post" action="<?= $app->url->create('admin/handle/content/edit') ?>">
        <div class="form-group">
            <label class="control-label col-sm-1">Titel: </label>
            <div class="col-sm-4">
                <input type="text" name="title" class="form-control" value="<?= $content->title; ?>"/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-1">Path: </label>
            <div class="col-sm-4">
                <input type="text" name="path" class="form-control" value="<?= $content->path; ?>"/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-1">Slug: </label>
            <div class="col-sm-4">
                <input type="text" name="slug" class="form-control" value="<?= $content->slug; ?>"/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-1">Typ: </label>
            <div class="col-sm-4">
                <input type="text" name="type" class="form-control" value="<?= $content->type; ?>"/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-1">Filter: </label>
            <div class="col-sm-4">
                <input type="text" name="filter" class="form-control" value="<?= $content->filter; ?>"/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-1">Publiserad: </label>
            <div class="col-sm-4">
                <input type="datetime-local" name="published" class="form-control" value="<?= $content->published ? date('Y-m-d\TH:i', strtotime($content->published)) : ''; ?>"/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-1">Borttagen: </label>
            <div class="col-sm-4">
                <input type="datetime-local" name="deleted" class="form-control" value="<?= $content->deleted ? date('Y-m-d\TH:i', strtotime($content->deleted)) : ''; ?>"/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-1">Data: </label>
            <div class="col-sm-8">
                <textarea rows="10" name="data" class="form-control"><?= $content->data; ?></textarea>
            </div>
        </div>
        <div class="form-group">
            <input type="hidden" name="id" value="<?= $content->id ?>"/>
        </div>
        <hr>
        <button class="btn btn-primary pull-right">Spara</button>
        <a href="<?= $app->url->create("admin/content"); ?>" class="btn btn-warning pull-right right-margin-2rem">Till innehåll</a>
    </form>
</div>
<br>
