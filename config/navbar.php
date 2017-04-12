<?php

return [
    "views" => [
        "headerMenu" => _404_APP_PATH . "/view/navbar2/headerView.php",
        "footerMenu" => _404_APP_PATH . "/view/navbar2/footerView.php",
    ],
    "items" => [
        "home" => [
            "text" => "Hem",
            "route" => $this->app->url->create("index.php"),
        ],
        "reports" => [
            "text" => "Rapporter",
            "route" => $this->app->url->create("reports"),
        ],
        "session_testing" => [
            "text" => "Session",
            "route" => $this->app->url->create("session"),
        ],
        "about" => [
            "text" => "Om",
            "route" => $this->app->url->create("about"),
        ],
    ]
];
