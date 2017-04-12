<nav class="navbar navbar-default navbar-fixed-top topnav">
    <div class="container topnav">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?= $app->navbar->getRoute('home') ?>">Me oophp</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <?php
                $app->navbar->mapView(
                    function ($route, $text) use ($app) {
                        ?>
                        <li class="<?= $app->request->getCurrentUrl() == $route ? 'active': '' ?>">
                            <a href="<?= $route ?>"><?= $text ?></a>
                        </li>
                <?php
                    }
                );
                ?>
            </ul>
        </div>
    </div>
</nav>
