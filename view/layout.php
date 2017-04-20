<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Mobile -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?= $title ?></title>

    <link rel="shortcut icon" href="<?= $app->url->asset('img/favicon.ico') ?>">

    <!-- Bootstrap Core CSS -->
    <link href="http://getbootstrap.com/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/dist/css/bootstrap-theme.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?= $app->url->asset('style/groundwork/css/landing-page.css') ?>" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?= $app->url->asset('style/groundwork/font-awesome/css/font-awesome.css') ?>" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">

    <!-- My-styles -->
    <link href="<?= $app->url->asset('style/style.css') ?>" rel="stylesheet">
</head>

<body>

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
                <?= $app->navbar->headerMenu(); ?>
            </div>
            <div class="<?= $app->request->getGet('login') ? '' : 'hidden' ?>">
                <form class="navbar-text navbar-right" action="">
                    <label for="user">Användarnamn: </label>
                    <input id="user" type="text">
                    <label for="password">Lösenord: </label>
                    <input id="password" type="password">
                    <button id="login-attempt" type="submit" class="btn">Logga in</button>
                    <button id="login-cancel" class="btn">Avbryt</button>
                </form>
            </div>
        </div>
    </nav>


    <?php if ($this->regionHasContent("main")) : ?>
        <div class="main-wrap">
            <?php $this->renderRegion("main") ?>
        </div>
    <?php endif; ?>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                        <?= $app->navbar->footerMenu(); ?>
                    <hr>
                    <div class="copyright text-muted small">
                        <p>
                            Kontakt: <a href="mailto:litemerafrukt@gmail.com">litemerafrukt@gmail.com</a>
                        </p>
                        <p>
                            <a href="http://litemerafrukt.se">litemerafrukt.se</a>
                        </p>
                        <p>
                            Copyright &copy; Anders Nygren.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

</body>
</html>
