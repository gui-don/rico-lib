<?php

namespace Rico\Slib;

abstract class MathUtils
{
    /**
     * Rounds a $number adding decimal part only when int part of $number < $idealLength.
     *
     * @param float|int $number
     * @param int $idealLength
     *
     * @return string
     */
    public static function smartRound($number, int $idealLength = 3): string
    {
        if ($number == 0) {
            return 0;
        }

        $intPart = intval($number);

        $precision = $idealLength - strlen($intPart);
        if ($precision < 0) {
            $precision = 0;
        }

        return sprintf("%.{$precision}f", $number);
    }
}
