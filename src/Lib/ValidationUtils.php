<?php

declare(strict_types=1);

namespace Rico\Lib;

use Rico\Slib\ValidationUtils as StaticValidationUtils;

class ValidationUtils
{
    /**
     * Checks that $mixed value is an email.
     *
     * @param mixed $mixed
     *
     * @return bool
     */
    public function isEmail($mixed): bool
    {
        return StaticValidationUtils::isEmail($mixed);
    }

    /**
     * Checks that $mixed value is a hexadecimal value.
     *
     * @param mixed $mixed
     *
     * @return bool
     */
    public function isHexadecimal($mixed)
    {
        return StaticValidationUtils::isHexadecimal($mixed);
    }

    /**
     * Checks that $mixed value is an IP (v4 or v6).
     *
     * @param mixed $mixed
     *
     * @return bool
     */
    public function isIp($mixed): bool
    {
        return StaticValidationUtils::isIp($mixed);
    }

    /**
     * Checks that $mixed value is a decimal number (float or integer).
     *
     * @param mixed $mixed
     *
     * @return bool
     */
    public function isNumber($mixed): bool
    {
        return StaticValidationUtils::isNumber($mixed);
    }

    /**
     * Checks that $string value is a phone number.
     *
     * @param string $string
     *
     * @return bool
     */
    public function isPhoneNumber(string $string): bool
    {
        return StaticValidationUtils::isPhoneNumber($string);
    }

    /**
     * Checks that $mixed value is a positive integer (primary key).
     *
     * @param mixed $mixed
     *
     * @return bool
     */
    public function isPositiveInt($mixed): bool
    {
        return StaticValidationUtils::isPositiveInt($mixed);
    }

    /**
     * Checks that $mixed value is an URL.
     *
     * @param mixed $mixed
     *
     * @return bool
     */
    public function isURL($mixed): bool
    {
        return StaticValidationUtils::isURL($mixed);
    }

    /**
     * Checks that $mixed value is magnet URL.
     *
     * @param mixed $mixed
     *
     * @return bool
     */
    public function isURLMagnet($mixed): bool
    {
        return StaticValidationUtils::isURLMagnet($mixed);
    }
}
