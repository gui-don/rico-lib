<?php

namespace Lib;

/**
 * String Library
 */
abstract class String
{
    /**
     * Remove all sort of spaces from a string
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
     * Replace all sort of spaces by a simple space
     * @param string $string
     * @return string
     */
    public static function normalizeWhitespace($string)
    {
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
     * Remove all sort of line breaks
     * @param string $string
     * @return string
     */
    public static function removeLine($string)
    {
        $string = preg_replace('/[\r\n]+/', '', $string);
        return $string;
    }

    /**
     * Clean a string by removing multi-spaces, line breaks, indents and HTML tags
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
        if (!is_int($length) || $length <= 0) {
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
     * REmove whitespaces, line breaks and comment out of a string
     * @param string $string
     * @return string Minified string
     */
    public static function minify($string)
    {
        $string = preg_replace('#\/\*.*\*\/#s', '', $string);
        $string = self::removeLine($string);
        $string = self::normalizeWhitespace($string);
        $string = preg_replace('# ?([\;\:\{\}\,]) ?#', '$1', $string);
        return $string;
    }
}
