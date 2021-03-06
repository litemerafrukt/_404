<?php

namespace _404\Components\TextBox;

/**
 * Class for nl2br filter
 */
class Fhtmlentities implements ITextFilter
{
    /**
     * Run htmlentities as a parse filter.
     *
     * @param string $text
     * @return string
     */
    public function parse($text)
    {
        return htmlentities($text);
    }
}
