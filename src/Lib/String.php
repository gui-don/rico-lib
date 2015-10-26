<?php

namespace Rico\Lib;

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

        // Be careful, there are non secable spaces here
        $string = str_replace(array(' ;', ' ?', ' !', ' :', ' »', '« ', '\'', '...'), array(' ;', ' ?', ' !', ' :', ' »', '« ', '’', '…'), $string);
        $string = preg_replace('#(\d)\s?([$€£¥])#u', '$1 $2', $string);
        $string = preg_replace_callback('#\d{4,}#u', function($matches) { return number_format($matches[0], 0, ',', ' '); }, $string);

        // Count quotes
        $QuotesCount = strlen($string) - strlen(str_replace('"', '', $string));

        // Repeat two times is important for quotes inside quotes
        if ($QuotesCount % 2 == 0) {
            $string = preg_replace('#([\s\r\n\p{P}]|^|)(\")([^\"]*)(\")([\s\p{P}]|$)#u', '$1“$3”$5', $string);
            $string = preg_replace('#([\s\r\n\p{P}]|^|)(\")([^\"]*)(\")([\s\p{P}]|$)#u', '$1“$3”$5', $string);
        }

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

    /**
     * Get the name of a resource (image, pdf, ...) out of an URL
     * @param string $url
     * @return string Name of the resource
     */
    public static function getResourceNameInUrl($url)
    {
        if (!is_string($url)) {
            return false;
        }

        preg_match("/\/([^\/\?]+)(?:[\?\#].*)?$/", $url, $matches);

        return !empty($matches[1]) ? $matches[1] : '';
    }

    /**
     * Convert an alphabetic string into an identifier (an integer)
     * @param string $string Alphanumeric string to be converted
     * @param string $secret Password to decode the alphabetic string
     * @return int
     */
    public static function alphaToId($string, $secret = '')
    {
        if (!is_string($secret) || !is_string($string)) {
            return false;
        }

        $out =  '';
        $index = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $base = strlen($index);
        $stringLength = strlen($string) - 1;

        if ($secret) {
            $splitIndex = str_split($index);

            // Create a new generated secret order based on secret
            array_multisort(array_slice(str_split(hash('sha512', $secret)), 0, $base), SORT_DESC, $splitIndex);
            $index = implode($splitIndex);
        }

        for ($t = $stringLength; $t >= 0; $t--) {
            $bcp = $base ** ($stringLength - $t);
            $out += strpos($index, substr($string, $t, 1)) * $bcp;
        }

        return $out;
    }

    /**
     * Convert a integer into an alphanumeric string
     * @param int $identifier Identifier to be converted
     * @param string $secret Password to encode the integer
     * @return string
     */
    public static function IdToAlpha($identifier, $secret = '')
    {
        if (!is_string($secret) || !is_numeric($identifier)) {
            return false;
        }

        $out =  '';
        $index = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $base = strlen($index);

        if ($secret) {
            $splitIndex = str_split($index);

            // Create a new generated secret order based on secret
            array_multisort(array_slice(str_split(hash('sha512', $secret)), 0, $base), SORT_DESC, $splitIndex);
            $index = implode($splitIndex);
        }

        for ($t = ($identifier != 0 ? floor(log($identifier, $base)) : 0); $t >= 0; $t--) {
            $bcp = $base ** $t;
            $a = floor($identifier / $bcp) % $base;
            $out .= substr($index, $a, 1);
            $identifier = $identifier - ($a * $bcp);
        }

        return $out;
    }
}
