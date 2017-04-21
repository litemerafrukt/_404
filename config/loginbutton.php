<?php

return [
    'views' => [
        'form'   => _404_APP_PATH . '/view/loginbutton/form.php',
    ],
    'routes' => [
        'loginHandler'  => $this->app->url->create('handle/login'),
        'logoutHandler' => $this->app->url->create('handle/logout'),
        'userProfile'   => $this->app->url->create('user/profile'),
        'newUser'       => $this->app->url->create('user/register'),
    ]
];
