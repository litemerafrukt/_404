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
