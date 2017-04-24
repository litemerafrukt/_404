<?php

namespace _404\Components\LoginButton;

use _404\Component\ComponentRenderInterface;
use _404\Component\ComponentRenderTrait;
use _404\Database\Users;
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
//
//    public function user($default)
//    {
//        return $this->app->session->get('user', $default);
//    }

    public function __call($methodName, $data)
    {

        $this->validateViewMethod($methodName);

//        $userDb = new Users($this->app->dbconnection);

//        list($userLoggedIn, $username, $isAdmin) = $this->app->session->either('user')
//            ->resolve(
//                function ($user) use ($userDb) {
//                    return [true, $user, _404_APP_ADMIN_LEVEL == $userDb->getLevel($user)];
//                },
//                function () {
//                    return [false, '', 100];
//                }
//            );

        $userLoggedIn = $this->app->user->isUser();
        $username     = $this->app->user->name();
        $isAdmin      = $this->app->user->isAdmin();
        $showLogin    = $this->app->get->maybe('login')->withDefault(false);

        $viewData = array_merge(
            compact('showLogin', 'userLoggedIn', 'username', 'isAdmin'),
            $this->config['routes'],
            $data
        );

        return $this->renderComponent($this->config['views'][$methodName], $viewData);
    }
}
