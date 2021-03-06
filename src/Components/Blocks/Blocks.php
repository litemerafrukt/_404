<?php

namespace _404\Components\Blocks;

use _404\Database\ContentAccess;
use _404\Toolz\ToolzInjectableTrait;
use _404\Toolz\ToolzInjectableInterface;
use Anax\Common\AppInjectableInterface;
use Anax\Common\AppInjectableTrait;

class Blocks implements AppInjectableInterface, ToolzInjectableInterface
{
    use AppInjectableTrait;
    use ToolzInjectableTrait;

    /**
     * Get a block by title
     *
     * @param  string
     * @return string
     */
    public function get($title)
    {
        $contentDb = new ContentAccess($this->app->dbconnection);

        $block = $contentDb->getBlock($title);
        return ! empty($block)
            ? $this->tlz->filterText($block->data, $block->filter)
            : "";
    }

    /**
     * Check if block with title exists
     *
     * @param  string
     * @return bool
     */
    public function has($title)
    {
        return empty($this->get($title))
            ? false
            : true;
    }
}
