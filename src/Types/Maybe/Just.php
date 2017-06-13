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
     * Just get the value inside the Just.
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
