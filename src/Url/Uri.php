<?php

namespace _404\Url;

class Uri
{
    private $uri;

    /**
     * Uri constructor.
     * @param $uri
     */
    public function __construct($uri)
    {
        $this->uri = $uri;
    }

    /**
     * Is uri empty?
     *
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->uri);
    }

    /**
     * Check if uri starts with string.
     *
     * @param $str
     * @return bool
     */
    public function startsWith($str)
    {
        $len = strlen($str);
        return substr($this->uri, 0, $len) == $str;
    }

    /**
     * Check if uri starts with any of supplied strings.
     *
     * @param $strArr
     * @return bool
     */
    public function startsWithAny($strArr)
    {
        return array_reduce($strArr, function ($carry, $string) {
            return $this->startsWith($string) || $carry;
        }, false);
    }

    /**
     * Prepend uri with another uri.
     *
     * @param Uri $uri
     * @return $this
     */
    public function prepend(Uri $uri)
    {
        $this->uri = $uri->uri() . "/" . ltrim($this->uri(), "/");
        return $this;
    }

    /**
     * Remove basename from uri if basename is basename.
     *
     * @param string $basename
     * @return $this
     */
    public function removeBasename($basename)
    {
        $this->uri = basename($this->uri) == $basename
            ? dirname($this->uri)
            : $this->uri;
        return $this;
    }

    /**
     * Get uri as string.
     *
     * @return string
     */
    public function uri()
    {
        return rtrim($this->uri, "/");
    }
}
