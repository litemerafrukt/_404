<?php

namespace _404\Components\TextBox;

/**
 * Class for nl2br filter
 */
class Fnl2br implements ITextFilter
{
    /**
     * Just run nl2br as a parse filter.
     *
     * @param string $text
     * @return string
     */
    public function parse($text)
    {
        // Actually nl2br does not!!!!! do what you think or the name implies. It does not replace
        // all newlines with br-tags. Sooooo lets fix that.
//        return nl2br($text);
        return str_replace(["\r\n", "\r", "\n"], "<br>", $text);
    }
}
