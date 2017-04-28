<?php

namespace _404\Toolz;

use _404\Types\Either\Right;

class Toolz
{
    public function partial($func, ...$args)
    {
        return function () use ($func, $args) {
            return call_user_func_array($func, array_merge($args, func_get_args()));
        };
    }

    public function combineEither($arrOfEither)
    {
        $combine = function ($carry, $either) {
            if ($either->isLeft()) {
                return $either;
            }
            if ($carry->isLeft()) {
                return $carry;
            }
            return $carry->map(function (array $combinedArr) use ($either) {
                $combinedArr[] = $either->get();
                return $combinedArr;
            });
        };

        $combinedEither = array_reduce($arrOfEither, $combine, new Right([]));

        return $combinedEither;
    }
}
