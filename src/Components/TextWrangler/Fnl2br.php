<?php

namespace _404\Components\TextWrangler;

/**
 * Class to parse from markdown
 */
class FMarkdown implements ITextFilter
{
    /**
     * Parse input to html
     *
     * @param string
     * @return string
     */
    public function toHtml($text)
    {
        $parser = new Parsdown;
        return $parser->text($text);
    }
}
