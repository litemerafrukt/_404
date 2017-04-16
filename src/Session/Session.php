<?php

namespace _404\Session;

class Session
{

    private $name;

    /**
     * Session constructor.
     *
     * @param $name session name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Start a session
     *
     */
    public function start()
    {
        session_name($this->name);

        if (!empty(session_id())) {
            $this->destroy();
        }
        session_start();
    }

    /**
     * Check if key exist in session
     *
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($key, $_SESSION);
    }

    /**
     * Set value in $_SESSION
     *
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Get a value from $_SESSION
     *
     * @param $key
     * @param string $default
     * @return string
     */
    public function get($key, $default = null)
    {
        return $this->has($key)
            ? $_SESSION[$key]
            : $default;
    }

    /**
     * Deletes a key from session
     *
     * @param string $key
     */
    public function delete($key)
    {
        unset($_SESSION[$key]);
    }

    /**
     * Return var_dump of session
     *
     * @return string
     */
    public function dump()
    {
        ob_start();
        var_dump($_SESSION);
        return ob_get_clean();
    }

    /**
     * Destroy session
     *
     */
    public function destroy()
    {
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 420000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();
    }
}
