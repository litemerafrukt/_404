<?php

namespace _404\Components\LoginButton;

use _404\Component\ComponentRenderInterface;
use _404\Component\ComponentRenderTrait;
use Anax\Common\AppInjectableInterface;
use Anax\Common\AppInjectableTrait;
use Anax\Common\ConfigureInterface;
use Anax\Common\ConfigureTrait;

/**
 * Class LoginButton
 */
class LoginButton implements AppInjectableInterface, ConfigureInterface, ComponentRenderInterface
{
    use ConfigureTrait;
    use AppInjectableTrait;
    use ComponentRenderTrait;

    public function user($default)
    {
        return $this->app->session->get('user', $default);
    }

    public function __call($methodName, $data)
    {
        $this->validateViewMethod($methodName);

        $viewData = [
            'showForm'      => $this->app->request->getGet('login'),
            'userLoggedIn'  => $this->app->session->has('user'),
            'userName'      => $this->app->session->get('user'),
        ];

        $viewData = array_merge($this->config['routes'], $viewData);
        $viewData = array_merge($viewData, $data);

        return $this->renderComponent($this->config['views'][$methodName], $viewData);
    }
}
