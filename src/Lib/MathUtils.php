<?php

namespace Rico\Lib;

use Rico\Slib\MathUtils as StaticMathUtils;

class MathUtils
{
    /**
     * Rounds a $number adding decimal part only when int part of $number < $idealLength.
     *
     * @param float|int $number
     * @param int $idealLength
     *
     * @return string
     */
    public function smartRound($number, int $idealLength = 3): string
    {
        return StaticMathUtils::smartRound($number, $idealLength);
    }
}
