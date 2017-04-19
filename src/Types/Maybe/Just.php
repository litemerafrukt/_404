<?php

namespace _404\Types\Maybe;

class Just implements Maybe
{
    private $value;

    /**
     * Construct a Just.
     *
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * True since we are Just.
     *
     * @return bool
     */
    public function isJust()
    {
        return true;
    }

    /**
     * False 'cos we are Just.
     *
     * @return bool
     */
    public function isNothing()
    {
        return false;
    }

    /**
     * Run function on value, return new Maybe.
     *
     * @param $func
     * @return Maybe
     */
    public function map($func)
    {
        return new self($func($this->value));
    }

    /**
     * Returns the value in Just.
     *
     * @param Maybe $maybe
     * @return Maybe
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function orElse(Maybe $maybe)
    {
        return $this;
    }

    /**
     * Filter the value, return Just|Nothing depending on predicate.
     *
     * @param $filterFunc
     * @return Maybe
     */
    public function filter($filterFunc)
    {
        return $filterFunc($this->value) ? $this : new Nothing(null);
    }

    /**
     * Returns the value this Just holds.
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
}
