<?php

namespace _404\Toolz;

use _404\Components\TextBox\Fhtmlentities;
use _404\Components\TextBox\FBBCodeToHtml;
use _404\Components\TextBox\FClickableLinks;
use _404\Components\TextBox\FMarkdownToHtml;
use _404\Components\TextBox\Fnl2br;
use _404\Components\TextBox\TextBox;
use _404\Components\TextBox\TextFilter;
use _404\Types\Either\Left;
use _404\Types\Either\Right;

class Toolz
{
    /**
     * Create a partially applied function
     *
     * @param  callable $func
     * @param  $args
     * @return callable
     */
    public function partial($func, ...$args)
    {
        return function () use ($func, $args) {
            return call_user_func_array($func, array_merge($args, func_get_args()));
        };
    }

    /**
     * Combine an array of eithers to a Right with an array or a Left.
     * If any either in the array is Left then returns first left.
     * If all either is Right then return a Right with array of values.
     *
     * @param $arrOfEither
     * @return Right|Left
     */
    public function combineEither($arrOfEither)
    {
        $combine = function ($carry, $key, $either) {
            if ($carry->isLeft()) {
                return $carry;
            }
            if ($either->isLeft()) {
                return $either;
            }
            return $carry->map(function (array $combinedArr) use ($key, $either) {
                $combinedArr[$key] = $either->get();
                return $combinedArr;
            });
        };

        // PHP apparently can't reduce with preserved keys.
//        $combinedEither = array_reduce($arrOfEither, $combine, new Right([]));

        $combinedEither = new Right([]);
        foreach ($arrOfEither as $key => $either) {
            $combinedEither = $combine($combinedEither, $key, $either);
        }

        return $combinedEither;
    }

    /**
     * Box up a value in an either. If value is empty() then Left else Right.
     *
     * @param mixed $value The value to box up
     * @param string $errorMsg Error message for Left if the $value is empty
     * @return Left/Right
     */
    public function eitherEmpty($value, $errorMsg)
    {
        return empty($value)
            ? new Left($errorMsg)
            : new Right($value);
    }

    /**
     * Boxup a string in a textbox
     *
     * @param string $text
     * @return TextBox
     */
    public function textbox($text)
    {
        return new TextBox($text);
    }

    /**
     * Filter a text with filters.
     *
     * @param string $text - the text
     * @param string $filters - a comma separated string with filters
     * @return string - the filtered string
     */
    public function filterText($text, $filters)
    {
        return $this->filterTextBox($text, $filters)->text();
    }

    /**
     * Filter a text with filters.
     *
     * @param string - the text
     * @param string - a comma separated string with filters
     * @return string - the filtered string
     */
    public function filterTextBox($text, $filters)
    {
        $textFilter = new TextFilter([
            'nl2br' => new Fnl2br(),
            'link' => new FClickableLinks(),
            'markdown' => new FMarkdownToHtml(),
            'bbcode' => new FBBCodeToHtml(),
        ]);

        return $textFilter->filter($text, $filters);
    }

    /**
     * Render view file with data.
     *
     * @param $file
     * @param $data
     * @return string
     * @throws \Exception
     */
    public function render($file, $data)
    {
        ob_start();

        if (!file_exists($file)) {
            throw new \Exception("View template not found: $file.");
        }

        extract($data);

        include $file;

        $output = ob_get_clean();

        return $output;
    }
}
