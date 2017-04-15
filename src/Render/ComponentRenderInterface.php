<?php

namespace _404\Component;

interface ComponentRenderInterface
{
    /**
     * Render component view file with data.
     *
     * @return string
     * @throws \Exception
     */
    public function render();
}
