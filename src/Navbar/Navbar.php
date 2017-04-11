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
    public function getRoute($item)
    {
        return $this->app->url->create(
            $this->config['items'][$item]['route']
        );
    }

    /**
     * Render the navbar with a supplied callback
     *
     * @param callable $callback the callback receives route as first param and text as second
     */
//    public function each($callback)
//    {
//        foreach ($this->config["items"] as $navItem) {
//            $callback($this->app->url->create($navItem['route']), $navItem['text']);
//        }
//    }

    private function renderMenu($file, $data)
    {
        ob_start();

        if (!file_exists($file)) {
            throw new \Exception("Menu template not found: $file.");
        }

        extract($data);

        include $file;

        $output = ob_get_clean();

        return $output;
    }

    public function __call($methodName, $data = [])
    {



        // Borde göra koll att $metodName finns som fil i konfigurationen



        $data = array_merge(
            $data,
            ['routes' => $this->config['items'], 'currentRoute' => $this->app->request->getCurrentUrl()]
        );

        return $this->renderMenu($this->config['views'][$methodName], $data);
    }
}
