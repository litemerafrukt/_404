<?php

namespace _404\Components\TextBox;

use Parsedown;

/**
 * Class to parse from markdown
 */
class FMarkdownToHtml implements ITextFilter
{
    /**
     * Parse input to html
     *
     * @param string
     * @return string
     */
    public function parse($text)
    {
        $parser = new Parsdown;
        return $parser->text($text);
    }
}
