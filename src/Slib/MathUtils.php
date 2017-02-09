<?php

declare(strict_types=1);

namespace Rico\Slib;

abstract class MathUtils
{
    /**
     * Rounds a $number adding decimal part only when int part of $number < $idealLength.
     *
     * @param float $number
     * @param int   $idealLength
     *
     * @return float
     */
    public static function smartRound(float $number, int $idealLength = 3): float
    {
        if ($number == 0) {
            return 0.0;
        }

        $intPart = intval($number);

        $precision = $idealLength - mb_strlen((string) $intPart);
        if ($precision < 0) {
            $precision = 0;
        }

        return (float) sprintf("%.{$precision}f", $number);
    }
}
