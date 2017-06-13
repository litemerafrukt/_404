<?php

namespace _404\Types\Either;

class Right implements Either
{
    private $value;

    /**
     * Construct a Right.
     *
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * True since we are Right.
     *
     * @return bool
     */
    public function isRight()
    {
        return true;
    }

    /**
     * False 'cos we are Right.
     *
     * @return bool
     */
    public function isLeft()
    {
        return false;
    }

    /**
     * Run function on value, return new Right.
     *
     * @param $func
     * @return Either
     */
    public function map($func)
    {
        return new self($func($this->value));
    }

    /**
     * Returns this.
     *
     * @param Either $either
     * @return Either
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function orElse(Either $either)
    {
        return $this;
    }

    /**
     * Filter the value, return Right|Left depending on predicate.
     *
     * @param $filterFunc
     * @param $error
     * @return Either
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function filter($filterFunc, $error)
    {
        return $filterFunc($this->value) ? $this : new Left($error);
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
     * Returns the value in Right.
     *
     * @param mixed $default
     * @return mixed
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function withDefault($default)
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
        return $rightFunc($this->value);
    }
}
