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



        // TODO: Borde göra koll att $metodName finns som fil i konfigurationen, annars kasta error



        $data = array_merge(
            $data,
            ['routes' => $this->config['items'], 'currentRoute' => $this->app->request->getCurrentUrl()]
        );

        return $this->renderMenu($this->config['views'][$methodName], $data);
    }
}
