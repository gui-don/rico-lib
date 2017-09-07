<?php

declare(strict_types=1);

namespace Rico\Slib;

abstract class ValidationUtils
{
    /**
     * Checks that $mixed value is an email.
     *
     * @param mixed $mixed
     *
     * @return bool
     */
    public static function isEmail($mixed): bool
    {
        return
            is_string($mixed) &&
            // Use of preg_replace instead of preg_match, as preg_match acts unexpectedly with this regular expression
            preg_replace('#[a-zA-Z0-9\!\#\$\%\&\'\*\+\/\=\?\^\_\`\{\|\}\~\-]+(?:\.[a-zA-Z0-9\!\#\$\%\&\'\*\+\/\=\?\^\_\`\{\|\}\~\-]+)*\@(?:[A-Za-z0-9](?:[A-Za-z0-9-]*[A-Za-z0-9])?\.)+(?:[a-z]{2}|aero|asia|biz|cat|com|coop|info|int|jobs|mobi|museum|name|net|org|pro|tel|travel|xxx|edu|gov|mil)$#', 'ok', $mixed) === 'ok'
        ;
    }

    /**
     * Checks that $mixed value is a hexadecimal value.
     *
     * @param mixed $mixed
     *
     * @return bool
     */
    public static function isHexadecimal($mixed)
    {
        return
            is_string($mixed) &&
            preg_match('/^\b[0-9A-F]{6}\b$/i', $mixed)
        ;
    }

    /**
     * Checks that $mixed value is an IP (v4 or v6).
     *
     * @param mixed $mixed
     *
     * @return bool
     */
    public static function isIp($mixed): bool
    {
        return
            is_string($mixed) &&
            (
                // IPv4 check
                preg_match('#^((25[0-5]|2[0-4][0-9]|1[0-9]{2}|0?[0-9]?[1-9]|0?[1-9][0-9])\.)((25[0-5]|(2[0-4]|(1|0)?[0-9])?[0-9])\.){2}(25[0-5]|(2[0-4]|(1|0)?[0-9])?[0-9])$#', $mixed) ||
                // IPv6 check
                preg_match('#^(([0-9a-fA-F]{1,4}:){7,7}[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,7}:|([0-9a-fA-F]{1,4}:){1,6}:[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,5}(:[0-9a-fA-F]{1,4}){1,2}|([0-9a-fA-F]{1,4}:){1,4}(:[0-9a-fA-F]{1,4}){1,3}|([0-9a-fA-F]{1,4}:){1,3}(:[0-9a-fA-F]{1,4}){1,4}|([0-9a-fA-F]{1,4}:){1,2}(:[0-9a-fA-F]{1,4}){1,5}|[0-9a-fA-F]{1,4}:((:[0-9a-fA-F]{1,4}){1,6})|:((:[0-9a-fA-F]{1,4}){1,7}|:)|fe80:(:[0-9a-fA-F]{0,4}){0,4}%[0-9a-zA-Z]{1,}|::(ffff(:0{1,4}){0,1}:){0,1}((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])|([0-9a-fA-F]{1,4}:){1,4}:((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9]))$#', $mixed)
            )
        ;
    }

    /**
     * Checks that $mixed value is a decimal number (float or integer).
     *
     * @param mixed $mixed
     *
     * @return bool
     */
    public static function isNumber($mixed): bool
    {
        if (!is_numeric($mixed)) {
            return false;
        }

        return (bool) preg_match('/^\-?[0-9]+\.?[0-9]*$/', (string) $mixed);
    }

    /**
     * Checks that $string value is a phone number.
     *
     * @param string $string
     *
     * @return bool
     */
    public static function isPhoneNumber(string $string): bool
    {
        // Sanitize string before the test itself
        $string = preg_replace('/(\(([0-9]+)\))+/', '$2', $string);
        $string = preg_replace('/([0-9]+)([ \\\–\-\.\/]{1})/', '$1$3', $string);

        return (bool) preg_match('/^(\(?\+\ ?[0-9]{1,4}\)?)?\ ?[0-9]{7,15}$/', $string);
    }

    /**
     * Checks that $mixed value is a positive integer (primary key).
     *
     * @param mixed $mixed
     *
     * @return bool
     */
    public static function isPositiveInt($mixed): bool
    {
        if (!isset($mixed) || is_bool($mixed)) {
            return false;
        }

        return filter_var($mixed, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) ? true : false;
    }

    /**
     * Checks that $mixed value is an URL.
     *
     * @param mixed $mixed
     *
     * @return bool
     */
    public static function isURL($mixed): bool
    {
        return
            is_string($mixed) &&
            preg_match('_^(https?|ftp)://(\S+(:\S*)?@)?(([1-9]|[1-9]\d|1\d\d|2[0-1]\d|22[0-3])(\.([0-9]|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])){2}(\.([1-9]|[1-9]\d|1\d\d|2[0-4]\d|25[0-4]))|(([a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)(\.([a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)*(\.([a-z\x{00a1}-\x{ffff}]{2,})))(:\d{2,5})?(/[^\s]*)?$_iuS', $mixed)
        ;
    }

    /**
     * Checks that $mixed value is magnet URL.
     *
     * @param mixed $mixed
     *
     * @return bool
     */
    public static function isURLMagnet($mixed): bool
    {
        return
            is_string($mixed) &&
            preg_match('#^magnet:\?xt=urn:[a-z0-9][a-z0-9-]{0,31}:[a-z0-9]{32,40}#i', $mixed)
        ;
    }
}
