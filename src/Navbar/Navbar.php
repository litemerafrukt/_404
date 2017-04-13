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
     * Render the menu with view file.
     *
     * @param $file
     * @param $data
     * @return string
     * @throws \Exception
     */
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

    /**
     * Validate that method name is present in config. Else throw.
     *
     * @param $methodName
     * @throws \BadMethodCallException
     */
    private function validateViewMethod($methodName)
    {
        if (! array_key_exists($methodName, $this->config['views'])) {
            throw new \BadMethodCallException('No view method named: ' . $methodName);
        }
    }

    /**
     * Make views from config callable. PHP magic method.
     *
     * @param $methodName
     * @param array $data
     * @return string
     */
    public function __call($methodName, $data = [])
    {
        $this->validateViewMethod($methodName);

        $data = array_merge(
            $data,
            ['routes' => $this->config['items'], 'currentRoute' => $this->app->request->getCurrentUrl()]
        );

        return $this->renderMenu($this->config['views'][$methodName], $data);
    }
}
