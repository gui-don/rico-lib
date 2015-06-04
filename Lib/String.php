<?php

namespace Lib;

/**
 * Library String
 */
abstract class String
{
    /**
     * Remove all spaces from a string
     * @param string $string
     * @return string
     */
    public static function removeWhitespace($string)
    {
        /**
         * \0 :  NIL char
         * \xC2 : non-breaking space
         * \xA0 : non-breaking space
         * \x0B : vertical tab
         * \t : tab
         */
        return preg_replace('/[\0\xC2\xA0\x0B\t\ \ \ \]+/u', '', $string);
    }

    /**
     * Remove all type of spaces by a simple space
     * @param string $string
     * @return string
     */
    public static function normalizeWhitespace($string) {
        /**
         * \0 :  NIL char
         * \xC2 : non-breaking space
         * \xA0 : non-breaking space
         * \x0B : vertical tab
         * \t : tab
         */
        return trim(preg_replace('/[\0\xC2\xA0\x0B\t\ \ \ \]+/u', ' ', $string));
    }

    /**
     * Remove line breaks
     * @param string $string
     * @return string
     */
    public static function removeLine($string)
    {
        $string = preg_replace('/[\r\n]+/', '', $string);
        return $string;
    }

    /**
     * Remove multi-spaces, line breaks, indentations and HTML tags
     * @param String $string Chaîne de caractères à traiter
     * @return String Chaîne de caractères traitée
     */
    public static function normalize($string)
    {
        $string = str_replace(array('<br/>', '<br />', '</br>', '<br>', '<br >', '< br >'), ' ', $string);
        $string = html_entity_decode($string, ENT_HTML5, 'UTF-8');
        $string = strip_tags($string);
        $string = self::removeLine($string);
        $string = self::normalizeWhitespace($string);
        return $string;
    }

    /**
     * Generates a random string of alphanumeric characters
     * @param int $length Desired chain length
     * @param string $allowedChars Characters allowed
     * @return string Random string of alphanumeric characters
     */
    public static function randString($length = 10, $allowedChars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        if (!self::isPositiveInt($length)) {
            return false;
        }

        $randString = '';
        $allowedCharsLength = mb_strlen($allowedChars, 'UTF-8');
        for ($i = 0; $i < $length; $i++) {
            $randString .= mb_substr($allowedChars, mt_rand(0, ($allowedCharsLength - 1)), 1, 'UTF-8');
        }

        return $randString;
    }

    /**
     * Checks that a variable is a positive integer
     * @param mixed $mixed Variable to test
     * @return boolean True if positive integer, false otherwise
     */
    public static function isPositiveInt($mixed)
    {
        if (!isset($mixed) || is_bool($mixed)) {
            return false;
        }

        return filter_var($mixed, FILTER_VALIDATE_INT, array('options' => array('min_range' => 0))) ? true : false;
    }

    /**
     * Check that a value or an array of values are numbers
     * @param mixed $mixed Variable or array
     * @return boolean True if value is a number, false otherwise
     */
    public static function isNumber($mixed)
    {
        if (is_array($mixed)) {
            foreach ($mixed as $mix) {
                if (!self::isNumber($mix)) {
                    return false;
                }
            }
        } else {
            if (!preg_match('/^\-?[0-9]+\.?[0-9]*$/', $mixed)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Transform a random string into a ascii-only string
     * @param string $string
     * @return string Ascii-only string separated by -
     */
    public static function slugify($string)
    {
        if (!is_string($string)) {
            return null;
        }

        setlocale(LC_CTYPE, 'fr_FR.UTF-8');

        // replace non letter or digits by -
        $string = preg_replace('#[^\\pL\d]+#u', '-', $string);

        // Transliterate
        $string = iconv('utf-8', 'us-ascii//TRANSLIT', $string);

        // Lowercase
        $string = strtolower($string);

        // Remove unwanted characters
        $string = preg_replace('~[^-\w]+~', '', $string);

        // Trim
        $string = trim($string, '-');

        return $string;
    }

    /**
     * Checks that a variable is an hexadecimal value
     * @param mixed $string Variable to test
     * @return boolean True if hexadecimal, false otherwise
     */
    public static function isHexadecimal($string)
    {
        if (!is_string($string)) {
            return false;
        }

        if (!preg_match('/^\b[0-9A-F]{6}\b$/i', $string)) {
            return false;
        }

        return true;
    }

    /**
     * Transform an ugly string (with incorrect ponctuation) into beautiful string (with correct ponctuation)
     * @param string $string String to be beautiful-ised
     * @return string Beautiful-ised string
     */
    public static function beautifulise($string)
    {
        if (!is_string($string)) {
            return false;
        }

        $string = str_replace(array(' ;', ' ?', ' !', ' :', ' »', '« ', '\'', '...'), array(' ;', ' ?', ' !', ' :', ' »', '« ', '’', '…'), $string);
        $string = preg_replace('#([^\"]*)\"([^\"]*)\"([^\"]*)#u', '$1“$2”$3', $string);

        return $string;
    }

    /**
     * Verify a string to be an URL
     * @param string $string String to test
     * @return boolean True if the string is an URL, false otherwise
     */
    public static function isURL($string) {
        if (!is_string($string)) {
            return false;
        }

        return (bool) preg_match('_^(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)(?:\.(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)*(?:\.(?:[a-z\x{00a1}-\x{ffff}]{2,})))(?::\d{2,5})?(?:/[^\s]*)?$_iuS', $string);
    }
}
