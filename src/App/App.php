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

    /**
     * Shortner for setRedirect.
     * @param $route
     * @return Response
     */
    public function setRedirect($route)
    {
        return $this->response->setRedirect($this->url->create($route));
    }

    /**
     * Set header to go back to previous route
     * @return Response
     */
    public function setRedirectBack()
    {
        $previousRoute = $this->server->maybe('HTTP_REFERER')
            ->map(function ($referer) {
                return explode("?", $referer)[0];
            })
            ->withDefault('');

        return $this->setRedirect($previousRoute);
    }

    /**
     * Previous route without query.
     *
     * @return string - previous route or homepage. No query.
     */
    public function previousRoute()
    {
        return $this->server->maybe('HTTP_REFERER')
            ->map(function ($referer) {
                $noQuery = explode("?", $referer)[0];
                return $this->url->create($noQuery);
            })
            ->withDefault($this->url->create(""));
    }

    /**
     * Previous route with query.
     *
     * @return string - previous route or homepage.
     */
    public function previousRouteWithQuery()
    {
        return $this->server->maybe('HTTP_REFERER')
            ->withDefault($this->url->create(""));
    }

    /**
     * Standard error. Redirects with error info in query.
     *
     * @param string
     * @return Response
     */
    public function stdErr($errorMsg)
    {
        $errQuery = urlencode($errorMsg);
        return $this->setRedirect("errorwithinfofromget?error=$errQuery");
    }
}
