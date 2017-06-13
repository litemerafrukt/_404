<?php
$urlHome  = $app->url->create("");
$urlAbout = $app->url->create("about");


$navbar = [
//     not used currently
//    "config" => [
//        "navbar-class" => "extra-style-navbar"
//    ],
    "items" => [
        "home" => [
            "text" => "Hem",
            "route" => $app->url->create(""),
        ],
        "reports" => [
            "text" => "Rapporter",
            "route" => $app->url->create("reports"),
        ],
        "about" => [
            "text" => "Om",
            "route" => $app->url->create("about"),
        ],
    ]
];
?>

<nav class="navbar navbar-default navbar-fixed-top topnav">
    <div class="container topnav">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?= $navbar["items"]["home"]["route"] ?>">Me oophp</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <?php foreach ($navbar["items"] as $item) : ?>
                    <li class="<?= $app->request->getCurrentUrl() == $item['route'] ? 'active': '' ?>">
                        <a
                                href="<?= $item['route'] ?>"
                        >
                            <?= $item['text'] ?>
                        </a>
                    </li>
                <?php endforeach ?>
            </ul>
        </div>
    </div>
</nav>
