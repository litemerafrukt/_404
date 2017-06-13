<?php

namespace _404\Toolz;

/**
 * Trait implementing ToolzInjectableInterface for classes which need to
 * be injectad with $tlz.
 */
trait ToolzInjectableTrait
{
    /**
     * Properties
     *
     */
    private $tlz;  // Contains all framework resources.



    /**
     * Inject $tlz into this class.
     *
     * @param object $tlz containing tools.
     *
     * @return $this for chaining.
     */
    public function setTlz($tlz)
    {
        $this->tlz = $tlz;
        return $this;
    }
}
