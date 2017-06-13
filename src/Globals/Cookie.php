<?php

namespace _404\Globals;

use _404\Types\Either\EitherFactoryInterface;
use _404\Types\Either\Left;
use _404\Types\Either\Right;
use _404\Types\Maybe\MaybeFactoryInterface;
use _404\Types\Maybe\Just;
use _404\Types\Maybe\Nothing;

class Cookie implements MaybeFactoryInterface, EitherFactoryInterface
{
    private $expire;

    /**
     * Constructor
     * Sets $expire to 30 days. 86400 = 1 day * 30 = 30 days
     *
     * @param $time int expire time
     */
    public function __construct($time = 86400*30)
    {
        $this->expire = time() + $time;
    }

    /**
     * Check if key exists in $_COOKIE
     *
     * @param $key string The key to check for in $_COOKIE
     * @return bool true if $key exists, otherwise false
     */
    public function has($key)
    {
        return array_key_exists($key, $_COOKIE);
    }

    /**
     * Sets a cookie
     *
     * @param $name string The name of the $_COOKIE
     * @param $value string The value of the $_COOKIE
     * @return void
     */
    public function set($name, $value)
    {
        setcookie($name, $value, time() + $this->expire, '/');
    }

    /**
     * Retrieve a cookie
     *
     * @param $key string The key to get from $_COOKIE
     * @param $default optional The return value if not found
     * @return string The cookie if present, else $default
     */
    public function get($key, $default = null)
    {
        return $this->has($key)
            ? $_COOKIE[$key]
            : $default;
    }

    /**
     * Get a Maybe from $_SESSION.
     *
     * @param $key
     * @return Just|Nothing
     */
    public function maybe($key)
    {
        return $this->has($key)
            ? new Just($_COOKIE[$key])
            : new Nothing(null);
    }

    /**
     * Get an Either from $_SESSION.
     *
     * @param $key
     * @return Right|Left
     */
    public function either($key)
    {
        return $this->has($key)
            ? new Right($_COOKIE[$key])
            : new Left($key . ' not found');
    }

    /**
     * Return var_dump of cookie
     *
     * @return string
     */
    public function dump()
    {
        ob_start();
        var_dump($_COOKIE);
        return ob_get_clean();
    }

    /**
     * Deletes variable from $_COOKIE if exists
     *
     * @param $key string The key variable to unset from $_COOKIE
     * @return void
     */
    public function delete($key)
    {
        unset($_COOKIE[$key]);
    }

    /**
     * Destroys all variables from $_COOKIE if exists
     *
     * @return void
     */
    public function destroy()
    {
        foreach ($_COOKIE as $key => $value) {
            setcookie($key, $value, time()-3600);
        }
    }
}
