<?php

namespace _404\Types\Either;

/**
 * Either type
 *
 * Interface for Right and Left
 *
 * @package _404\Globals
 */
interface Either
{
    public function __construct($value);
    public function isRight();
    public function isLeft();
    public function map($func);
    public function orElse(Either $either);
    public function filter($filterFunc, $error);
    public function get();
    public function withDefault($default);
    public function resolve($rightFunc, $leftFunc);
}
