<?php

declare(strict_types=1);

namespace Rico\Lib;

use Rico\Slib\MathUtils as StaticMathUtils;

class MathUtils
{
    /**
     * Rounds a $number adding decimal part only when int part of $number < $idealLength.
     *
     * @param float $number
     * @param int $idealLength
     *
     * @return float
     */
    public function smartRound(float $number, int $idealLength = 3): float
    {
        return StaticMathUtils::smartRound($number, $idealLength);
    }
}
