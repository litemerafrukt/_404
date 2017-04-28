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
        "blog" => [
            "text"  => "Blog",
            "route" => $this->app->url->create("blog"),
        ],
        "pages" => [
            "text"  => "Sidor",
            "route" => $this->app->url->create("pages"),
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
            "text"  => '<i class="fa fa-user"></i> ' . $this->app->user->name(),
            "route" => $this->app->url->create("?login=show"),
        ]
    ]
];
