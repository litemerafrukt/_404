<?php

namespace _404\Components\Articles;

/**
 * Collection of articles
*/
class Articles
{

    private $dir = "";
    private $articleFiles = [];

    public function __construct($dir)
    {
        $this->dir = $dir;
        // Get all filenames, eg a list of all reports
        $this->articleFiles = array_reverse(glob("$dir/*_fin.md"));
    }

    public function allArticles()
    {
        return array_map(function ($filename) {
            return new Article("$this->dir/$filename");
        }, $this->articleFiles);
    }

    public function article($title)
    {
        // search blogpost dir with glob
        $matchingFiles = glob("$this->dir/*?_{$title}_fin.md");
        $file = ! empty($matchingFiles) ? $matchingFiles[0] : "";

        return new Article($file);
    }

    public function nrOfArticles()
    {
        return count($this->articleFiles);
    }
}
