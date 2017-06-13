<?php

namespace _404\Types\Maybe;

class Nothing implements Maybe
{
    /**
     * Constructor should take value but discard it.
     *
     * @param mixed $value
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function __construct($value)
    {
    }

    /**
     * Returns orElse since we are Nothing
     *
     * @param Maybe $maybe
     * @return Maybe
     */
    public function orElse(Maybe $maybe)
    {
        return $maybe;
    }

    /**
     * Return false since we are Nothing.
     *
     * @return bool
     */
    public function isJust()
    {
        return false;
    }

    /**
     * Return true.
     *
     * @return bool
     */
    public function isNothing()
    {
        return true;
    }

    /**
     * Return this since $filterFunc on Nothing == Nothing
     *
     * @param $filterFunc
     * @return $this
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function filter($filterFunc)
    {
        return $this;
    }

    /**
     * Running a function on value in Nothing == Nothing
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
     * Just get the value inside the Nothing. Since it is noting then null.
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
        return null;
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
}
