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

    /**
     * Get fully qualified route from config.
     *
     * @param string $item name of route in config
     * @return string route
     */
    public function getRouteFor($item)
    {
        return $this->app->url->create(
            $this->config['items'][$item]['route']
        );
    }

    /**
     * Get fully qualified route for item.
     *
     * @param $item
     * @return string route
     */
    public function getRoute($item)
    {
        return $this->app->url->create(
            $item['route']
        );
    }

    /**
     * Get text for item.
     *
     * @param $item
     * @return mixed
     */
    public function getText($item)
    {
        return $item['text'];
    }

    /**
     * Returns true if $route is the current route
     *
     * @param $routeItem
     * @return bool
     */
    public function isCurrentRoute($routeItem)
    {
        $currentRoute = $this->app->request->getCurrentUrl();
        $route = $this->app->url->create($routeItem['route']);
        return  $currentRoute == $route;
    }

    /**
     * Yield all routes from config.
     *
     * @return \Generator
     */
    public function routes()
    {
        foreach ($this->config['items'] as $route) {
            yield $route;
        }
    }
}
