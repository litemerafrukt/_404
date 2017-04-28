<?php

namespace _404\Components\TextBox;

/**
 * Class to parse BBCode encoded text.
 */
class FBBCodeToHtml implements ITextFilter
{
    /**
     * Parse bbcode to html.
     *
     * @param string
     * @return string
     */
    public function parse($text)
    {
        // From https://dbwebb.se/forum/viewtopic.php?t=288
        $search = array(
            '/\[b\](.*?)\[\/b\]/is',
            '/\[i\](.*?)\[\/i\]/is',
            '/\[u\](.*?)\[\/u\]/is',
            '/\[img\](https?.*?)\[\/img\]/is',
            '/\[url\](https?.*?)\[\/url\]/is',
            '/\[url=(https?.*?)\](.*?)\[\/url\]/is'
        );
        $replace = array(
            '<strong>$1</strong>',
            '<em>$1</em>',
            '<u>$1</u>',
            '<img src="$1" />',
            '<a href="$1">$1</a>',
            '<a href="$1">$2</a>'
        );
        return preg_replace($search, $replace, $text);
    }
}
