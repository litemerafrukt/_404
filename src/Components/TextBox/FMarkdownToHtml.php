<?php

namespace _404\Components\TextBox;

use Michelf\Markdown;

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
        // $parser = new Parsedown();
        // return $parser->text($text);
        return (new Markdown)->defaultTransform($text);
    }
}
