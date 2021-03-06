<?php

namespace _404\Components\TextBox;

/**
 * Convert links in text to a-tags
 */
class FClickableLinks implements ITextFilter
{
    /**
     * Make textlinks clickable
     *
     * @param string
     * @return string
     */
    public function parse($text)
    {
        $text =  preg_replace_callback(
            '#\b(?<![href|src]=[\'"])https?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#',
            function ($matches) {
                return "<a href='{$matches[0]}'>{$matches[0]}</a>";
            },
            $text
        );

        return $text;
    }
}
