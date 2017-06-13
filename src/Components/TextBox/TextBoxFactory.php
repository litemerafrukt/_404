<?php

namespace _404\Components\TextBox;

/**
 * Faktory to produce TextBoxes
 *
 * Just if you need an object to produce TextBoxes
 */
class TextBoxFactory
{
    /**
     * Convenience function for box
     *
     * @param string
     * @return TextBox
     */
    public function __invoke($text)
    {
        return $this->box($text);
    }

    /**
     * Box up a string in a TextBox
     *
     * @param string
     * @return TextBox
     */
    public function box($text)
    {
        return new TextBox($text);
    }
}
