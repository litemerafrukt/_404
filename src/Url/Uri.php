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

    public function isEmpty()
    {
        return empty($this->uri);
    }

    public function startsWith($str)
    {
        $len = strlen($str);
        return substr($this->uri, 0, $len) == $str;
    }

    public function startsWithAny($strArr)
    {
        return array_reduce($strArr, function ($carry, $string) {
            return $this->startsWith($string) || $carry;
        }, false);
    }

    public function prepend(Uri $uri)
    {
        $this->uri = $uri->uri() . "/" . ltrim($this->uri(), "/");
        return $this;
    }

    public function removeBasename($basename)
    {
        $this->uri = basename($this->uri) == $basename
            ? dirname($this->uri)
            : $this->uri;
        return $this;
    }

    public function uri()
    {
        return rtrim($this->uri, "/");
    }
}
