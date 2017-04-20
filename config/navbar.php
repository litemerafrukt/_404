<?php

return [
    "views" => [
        /**
         * Look in Navbar class method __call() to see what values
         * is supplied to view.
         */
        "headerMenu" => _404_APP_PATH . "/view/navbar2/headerView.php",
        "footerMenu" => _404_APP_PATH . "/view/navbar2/footerView.php",
    ],
    "items" => [
        "home" => [
            "text"  => "Hem",
            "route" => $this->app->url->create(""),
        ],
        "reports" => [
            "text"  => "Rapporter",
            "route" => $this->app->url->create("reports"),
        ],
        "calendar" => [
            "text"  => "Kalender",
            "route" => $this->app->url->create("calendar"),
        ],
        "session_testing" => [
            "text"  => "Session",
            "route" => $this->app->url->create("session"),
        ],
        "about" => [
            "text"  => "Om",
            "route" => $this->app->url->create("about"),
        ],
        "login" => [
            "text"  => "Logga in",
            "route" => $this->app->url->create("?login=show"),
        ]
    ]
];
