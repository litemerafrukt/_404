<?php

namespace _404\App;

use _404\Globals\Server;

/**
 * Class App.
 * Wraps framework components.
 *
 * @package _404\App
 */
class App
{
    /**
     * Shortner for redirect.
     * @param $route
     */
    public function redirect($route)
    {
        $this->response->redirect($this->url->create($route));
    }

    /**
     * Go back to previous route
     */
    public function redirectBack()
    {
        $previousRoute = $this->server->maybe('HTTP_REFERER')
            ->map(function ($referer) {
                return explode("?", $referer)[0];
            })
            ->withDefault('');

        $this->redirect($previousRoute);
    }
}
