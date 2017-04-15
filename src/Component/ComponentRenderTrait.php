<?php

namespace _404\Component;

trait ComponentRenderTrait
{
    /**
     * Render component view file with data.
     *
     * @param $file
     * @param $data
     * @return string
     * @throws \Exception
     */
    private function renderComponent($file, $data)
    {
        ob_start();

        if (!file_exists($file)) {
            throw new \Exception("Component view template not found: $file.");
        }

        extract($data);

        include $file;

        $output = ob_get_clean();

        return $output;
    }

    /**
     * Validate that method name is present in config. Else throw.
     *
     * @param $methodName
     * @throws \BadMethodCallException
     */
    private function validateViewMethod($methodName)
    {
        if (! array_key_exists($methodName, $this->config['views'])) {
            throw new \BadMethodCallException('No view method named: ' . $methodName);
        }
    }
}
