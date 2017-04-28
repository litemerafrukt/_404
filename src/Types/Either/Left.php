<?php

namespace _404\Types\Either;

class Left implements Either
{
    private $value;

    /**
     * Constructor
     *
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * Returns orElse since we are Left
     *
     * @param Either $either
     * @return Either
     */
    public function orElse(Either $either)
    {
        return $either;
    }

    /**
     * Return false since we are Left.
     *
     * @return bool
     */
    public function isRight()
    {
        return false;
    }

    /**
     * Return true.
     *
     * @return bool
     */
    public function isLeft()
    {
        return true;
    }

    /**
     * Return this since $filterFunc on Left == Left
     *
     * @param $filterFunc
     * @param $error - only used in Right
     * @return $this
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function filter($filterFunc, $error)
    {
        return $this;
    }

    /**
     * Running a function on value in Left == Left
     *
     * @param $func
     * @return $this
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function map($func)
    {
        return $this;
    }

    /**
     * Get default value since Nothing holds nothing.
     *
     * @param $default
     * @return mixed
     */
    public function withDefault($default)
    {
        return $default;
    }

    /**
     * Just get the value inside the Either.
     *
     * This should not be your first-hand choice, use withDefault instead.
     * Rational: Since this is PHP we do not have super support for
     * functional programming. Sometimes you have done all checks
     * allready and just need the vaule.
     *
     * TODO: Could be that the need for this method could be
     * reduced with map2, map3 and so forth
     *
     * @return mixed
     */
    public function get()
    {
        return $this->value;
    }

    /**
     * Resolve / fold to right or left
     *
     * @param $rightFunc
     * @param $leftFunc
     * @return mixed
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function resolve($rightFunc, $leftFunc)
    {
        return $leftFunc($this->value);
    }
}
