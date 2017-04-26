<?php

/**
 * Class TextBox
 *
 * Container for text to transform
 */

class TextBox
{
    private $text;

    /**
     * Thou constructor
     *
     * @param string
     */
    public function __construct($text)
    {
        $this->text = $text;
    }

    /**
     * Convert newlines to <br>
     *
     * @return self
     */
    public function nl2br()
    {
        $this->text = nl2br($this->text);
        return $this;
    }

    /**
     * Run htmleneties on text
     *
     * @return self
     */
    public function esc()
    {
        $this->text = htmlentities($this->text);
        return $this;
    }

    /**
     * Make links in text clickable
     *
     * @return self
     */
    public function clickableLinks()
    {
        $this->text =  preg_replace_callback(
            '#\b(?<![href|src]=[\'"])https?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#',
            function ($matches) {
                return "<a href=\'{$matches[0]}\'>{$matches[0]}</a>";
            },
            $this->text
        );

        return $this;
    }

    /**
     * Run parser on text. Parser must implement ITextFilter.
     *
     * @param ITextFilter
     * @return self
     */
    public function parseWith(ITextFilter $parser)
    {
        $this->text = $parser->toHtml($this->text);
        return $this;
    }

    /**
     * Return boxed text
     *
     * @return string
     */
    public function text()
    {
        return $this->text;
    }
}
