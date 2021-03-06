<?php

namespace _404\User;

use _404\Types\Either\Left;
use _404\Types\Either\Right;

class User
{
    private $name;
    private $email;
    private $level;

    /**
     * User constructor.
     *
     * @param $name
     * @param string $email
     * @param int $level
     * @throws \Exception
     */
    public function __construct($name, $email = '', $level = _404_APP_GUEST_LEVEL)
    {
        if (! $name) {
            throw new \Exception("No name constructing user.", 1);
        }
        $this->name = $name;
        $this->email = $email;
        $this->level = $level;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function email()
    {
        return $this->email;
    }

    /**
     * Is user admin?
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->level == _404_APP_ADMIN_LEVEL;
    }

    /**
     * Is user admin or user?
     *
     * @return bool
     */
    public function isUser()
    {
        return $this->level == _404_APP_ADMIN_LEVEL || $this->level == _404_APP_USER_LEVEL;
    }

    /**
     * Is user only guest?
     *
     * @return bool
     */
    public function isGuest()
    {
        return $this->level == _404_APP_GUEST_LEVEL;
    }

    /**
     * Get admin wrapped in an Either
     *
     * @param $error
     * @return Left|Right
     */
    public function eitherAdminOr($error)
    {
        return $this->level == _404_APP_ADMIN_LEVEL
            ? new Right($this)
            : new Left($error);
    }

    /**
     * Get user wrapped in an Either
     *
     * @param $error
     * @return Left|Right
     */
    public function eitherUserOr($error)
    {
        return $this->level == _404_APP_USER_LEVEL || $this->level == _404_APP_ADMIN_LEVEL
            ? new Right($this)
            : new Left($error);
    }

    /**
     * Get admin name wrapped in an Either
     *
     * @param $error
     * @return Left|Right
     */
    public function eitherAdminNameOr($error)
    {
        return $this->level == _404_APP_ADMIN_LEVEL
           ? new Right($this->name)
           : new Left($error);
    }

    /**
     * Get user name wrapped in an Either
     *
     * @param $error
     * @return Left|Right
     */
    public function eitherUserNameOr($error)
    {
        return $this->level == _404_APP_USER_LEVEL || $this->level == _404_APP_ADMIN_LEVEL
            ? new Right($this->name)
            : new Left($error);
    }
}
