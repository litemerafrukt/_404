<?php

namespace _404\Navbar;

use Anax\Common\AppInjectableInterface;
use Anax\Common\AppInjectableTrait;
use Anax\Common\ConfigureInterface;
use Anax\Common\ConfigureTrait;

class Navbar implements AppInjectableInterface, ConfigureInterface
{
    use ConfigureTrait;
    use AppInjectableTrait;

    public function __construct()
    {
    }

    /**
     * @param $item
     * @return string route
     */
    public function getRoute($item)
    {
        return $this->app->url->create(
            $this->config['items'][$item]['route']
        );
    }

    /**
     * Render the navbar with a supplied callback
     *
     * @param callable $viewCallback the callback receives route as first param and text as second
     */
    public function mapView($viewCallback)
    {
        foreach ($this->config["items"] as $navItem) {
            $viewCallback($this->app->url->create($navItem['route']), $navItem['text']);
        }
    }
}
