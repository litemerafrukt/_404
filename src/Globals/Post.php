<?php

namespace _404\Globals;

use _404\Types\Either\EitherFactoryInterface;
use _404\Types\Either\Right;
use _404\Types\Either\Left;
use _404\Types\Maybe\MaybeFactoryInterface;
use _404\Types\Maybe\Just;
use _404\Types\Maybe\Nothing;

/**
 * Class Post
 *
 * Get a value from POST superglobal in form of a Either or a Maybe.
 *
 * @package _404\Globals
 */
class Post implements EitherFactoryInterface, MaybeFactoryInterface
{
    /**
     * Get a Maybe from POST.
     *
     * @param $key
     * @return Just|Nothing
     */
    public static function maybe($key)
    {
        return isset($_POST[$key])
            ? new Just($_POST[$key])
            : new Nothing(null);
    }

    /**
     * Get a Either from POST.
     *
     * @param $key
     * @return Right|Left
     */
    public static function either($key)
    {
        return isset($_POST[$key])
            ? new Right($_POST[$key])
            : new Left($key . ' not found');
    }
}
