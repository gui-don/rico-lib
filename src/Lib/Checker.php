<?php

namespace Rico\Lib;

/**
 * Checker Library.
 */
abstract class Checker
{
    /**
     * Checks a variable to be a positive integer (primary key).
     *
     * @param mixed $mixed Variable to test
     *
     * @return bool True if positive integer, false otherwise
     */
    public static function isPositiveInt($mixed)
    {
        if (!isset($mixed) || is_bool($mixed)) {
            return false;
        }

        return filter_var($mixed, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) ? true : false;
    }

    /**
     * Check a value to be a decimal number (float or integer).
     *
     * @param mixed $mixed
     *
     * @return bool True if value is a decimal number, false otherwise
     */
    public static function isNumber($mixed)
    {
        return (bool) preg_match('/^\-?[0-9]+\.?[0-9]*$/', $mixed);
    }

    /**
     * Check a value to be a hexadecimal value.
     *
     * @param mixed $mixed
     *
     * @return bool True if hexadecimal, false otherwise
     */
    public static function isHexadecimal($mixed)
    {
        if (!is_string($mixed)) {
            return false;
        }

        if (!preg_match('/^\b[0-9A-F]{6}\b$/i', $mixed)) {
            return false;
        }

        return true;
    }

    /**
     * Check a string to be an URL.
     *
     * @param string $string String to test
     *
     * @return bool True if the string is an URL, false otherwise
     */
    public static function isURL($string)
    {
        if (!is_string($string)) {
            return false;
        }

        return (bool) preg_match('_^(https?|ftp)://(\S+(:\S*)?@)?(([1-9]|[1-9]\d|1\d\d|2[0-1]\d|22[0-3])(\.([0-9]|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])){2}(\.([1-9]|[1-9]\d|1\d\d|2[0-4]\d|25[0-4]))|(([a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)(\.([a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)*(\.([a-z\x{00a1}-\x{ffff}]{2,})))(:\d{2,5})?(/[^\s]*)?$_iuS', $string);
    }

    /**
     * Check a string to be an IP (v4 or v6).
     *
     * @param string $string
     *
     * @return bool
     */
    public static function isIp($string)
    {
        if (!is_string($string)) {
            return null;
        }

        if (preg_match('#^((25[0-5]|2[0-4][0-9]|1[0-9]{2}|0?[0-9]?[1-9]|0?[1-9][0-9])\.)((25[0-5]|(2[0-4]|(1|0)?[0-9])?[0-9])\.){2}(25[0-5]|(2[0-4]|(1|0)?[0-9])?[0-9])$#', $string)) {
            return true;
        }

        return (bool) preg_match('#^(([0-9a-fA-F]{1,4}:){7,7}[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,7}:|([0-9a-fA-F]{1,4}:){1,6}:[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,5}(:[0-9a-fA-F]{1,4}){1,2}|([0-9a-fA-F]{1,4}:){1,4}(:[0-9a-fA-F]{1,4}){1,3}|([0-9a-fA-F]{1,4}:){1,3}(:[0-9a-fA-F]{1,4}){1,4}|([0-9a-fA-F]{1,4}:){1,2}(:[0-9a-fA-F]{1,4}){1,5}|[0-9a-fA-F]{1,4}:((:[0-9a-fA-F]{1,4}){1,6})|:((:[0-9a-fA-F]{1,4}){1,7}|:)|fe80:(:[0-9a-fA-F]{0,4}){0,4}%[0-9a-zA-Z]{1,}|::(ffff(:0{1,4}){0,1}:){0,1}((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])|([0-9a-fA-F]{1,4}:){1,4}:((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9]))$#', $string);
    }

    /**
     * Check a string to be an email.
     *
     * @param string $string
     *
     * @return bool True if string is an email, false otherwise
     */
    public static function isEmail($string)
    {
        if (!is_string($string)) {
            return false;
        }

        // Hack with preg_replace => preg_match has a oddly unwanted behavior with that regular expression
        return preg_replace('#[a-zA-Z0-9\!\#\$\%\&\'\*\+\/\=\?\^\_\`\{\|\}\~\-]+(?:\.[a-zA-Z0-9\!\#\$\%\&\'\*\+\/\=\?\^\_\`\{\|\}\~\-]+)*\@(?:[A-Za-z0-9](?:[A-Za-z0-9-]*[A-Za-z0-9])?\.)+(?:[a-z]{2}|aero|asia|biz|cat|com|coop|info|int|jobs|mobi|museum|name|net|org|pro|tel|travel|xxx|edu|gov|mil)$#', 'ok', $string) === 'ok';
    }

    /**
     * Check a string to be a phone number.
     *
     * @param string $string Chaîne de caractères à tester
     *
     * @return bool True if string is a phone number, false otherwise
     */
    public static function isPhoneNumber($string)
    {
        if (!is_string($string)) {
            return false;
        }

        // Sanitize string before the test itself
        $string = preg_replace('/(\(([0-9]+)\))+/', '$2', $string);
        $string = preg_replace('/([0-9]+)([ \\\–\-\.\/]{1})/', '$1$3', $string);

        return (bool) preg_match('/^(\(?\+\ ?[0-9]{1,4}\)?)?\ ?[0-9]{7,15}$/', $string);
    }
}
