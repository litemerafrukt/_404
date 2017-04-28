<?php

namespace _404\Types\Maybe;

/**
 * Maybe type
 *
 * Interface for Just and Nothing
 *
 * @package _404\Globals
 */
interface Maybe
{
    public function __construct($value);
    public function isJust();
    public function isNothing();
    public function map($func);
    public function orElse(Maybe $maybe);
    public function filter($filterFunc);
    public function get();
    public function withDefault($default);
}
