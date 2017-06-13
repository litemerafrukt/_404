<?php

namespace _404\Components\TextBox;

class TextFilter
{
    private $filtersToParsers;

    /**
     * TextFilter constructor. Construct object with textfilters/parsers
     * implementing ITextFilter.
     *
     * @param $filtersToParsers
     */
    public function __construct($filtersToParsers)
    {
        $this->filtersToParsers = $filtersToParsers;
    }

    /**
     * Filter a text. Takes comma separetad string with filternames. Returns a TextBox.
     *
     * @param $text
     * @param $filters
     * @return TextBox
     */
    public function filter($text, $filters)
    {
        $parsers = array_reduce(explode(',', $filters), function ($carry, $filter) {
            return isset($this->filtersToParsers[trim($filter)])
                ? array_merge($carry, [$this->filtersToParsers[trim($filter)]])
                : $carry;
        }, []);

        $textBox = new TextBox($text);

        foreach ($parsers as $parser) {
            $textBox->parseWith($parser);
        }

        return $textBox;
    }
}
