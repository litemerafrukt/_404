<?php

return [
    'views' => [
        'form'   => _404_APP_PATH . '/view/loginbutton/form.php',
    ],
    'routes' => [
        'loginHandler'   => $this->app->url->create('handle/login'),
        'logoutHandler'  => $this->app->url->create('handle/logout'),
        'userProfile'    => $this->app->url->create('user/profile'),
        'changePassword' => $this->app->url->create('user/passwordchange'),
        'newUser'        => $this->app->url->create('register/user'),
        'adminUsers'     => $this->app->url->create('admin/users'),
        'adminContent'   => $this->app->url->create('admin/content'),
        'adminWebshop'   => $this->app->url->create('admin/webshop'),
    ],
];
