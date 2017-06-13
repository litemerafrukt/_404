<?php

namespace _404\Components\Articles;

use Parsedown;

/**
 * Instansies represents one article.
 */
class Article
{

    private $articleDate;
    private $articleTitle;
    private $articleHtml;

    /**
     *  load and parses one markdown file
     *
     * @param string $filepath
     */
    public function __construct($filepath)
    {
        if (! file_exists("$filepath")) {
            //Just return == empty object
            return;
        }
        $htmlParser = new Parsedown();
        // Load and parse markdownfile
        $fileContent = file_get_contents("$filepath");
        $this->articleHtml = $htmlParser->text($fileContent);

        $filename = basename($filepath);
        // get date part and namepart (eg 2016-06-11_name_fin.md)
        $filenameParts = explode('_', $filename);
        $this->articleDate = strtotime($filenameParts[0]);
        $this->articleTitle = $filenameParts[1];
    }

    public function title()
    {
        return $this->articleTitle;
    }

    public function date()
    {
        return date('Y-m-d', $this->articleDate);
    }

    public function html()
    {
        return $this->articleHtml;
    }
}
