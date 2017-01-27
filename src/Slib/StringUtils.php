<?php

declare(strict_types=1);

namespace Rico\Slib;

abstract class StringUtils
{
    /**
     * Removes all sort of spaces from a $string.
     *
     * @param string $string
     *
     * @return string
     */
    public static function removeWhitespace(string $string): string
    {
        /*
         * \0 :  NIL char
         * \xC2 : non-breaking space
         * \xA0 : non-breaking space
         * \x0B : vertical tab
         * \t : tab
         */
        return preg_replace('/[\0\xC2\xA0\x0B\t\ \ \ \]+/u', '', $string);
    }

    /**
     * Replaces all sort of spaces (tab, nil, non-breaking…) in a $string by a simple space.
     *
     * @param string $string
     *
     * @return string
     */
    public static function normalizeWhitespace(string $string): string
    {
        /*
         * \0 :  NIL char
         * \xC2 : non-breaking space
         * \xA0 : non-breaking space
         * \x0B : vertical tab
         * \t : tab
         */
        return trim(preg_replace('/[\0\xC2\xA0\x0B\t\ \ \ \]+/u', ' ', $string));
    }

    /**
     * Removes all sort of line breaks inside a $string.
     *
     * @param string $string
     *
     * @return string
     */
    public static function removeLine(string $string): string
    {
        return preg_replace('/[\r\n]+/', '', $string);
    }

    /**
     * Cleans a $string by removing multi-spaces, line breaks, indents and HTML tags.
     *
     * @param string $string
     *
     * @return string
     */
    public static function normalize(string $string): string
    {
        $string = str_replace(['<br/>', '<br />', '</br>', '<br>', '<br >', '< br >'], ' ', $string);
        $string = html_entity_decode($string, ENT_HTML5, 'UTF-8');
        $string = strip_tags($string);
        $string = self::removeLine($string);
        $string = self::normalizeWhitespace($string);

        return $string;
    }

    /**
     * Generates a random string of $length $allowedChars.
     *
     * @param int    $length
     * @param string $allowedChars
     *
     * @return string
     */
    public static function randString(int $length = 10, string $allowedChars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'): string
    {
        if ($length <= 0) {
            return '';
        }

        $randString = '';
        $allowedCharsLength = mb_strlen($allowedChars, 'UTF-8');
        for ($i = 0; $i < $length; ++$i) {
            $randString .= mb_substr($allowedChars, mt_rand(0, ($allowedCharsLength - 1)), 1, 'UTF-8');
        }

        return $randString;
    }

    /**
     * Transforms a $string into a ascii-only string separated by -.
     *
     * @param string $string
     *
     * @return string
     */
    public static function slugify(string $string): string
    {
        setlocale(LC_CTYPE, 'fr_FR.UTF-8');

        // replace non letter or digits by -
        $string = preg_replace('#[^\\pL\d]+#u', '-', $string);

        // Transliterate
        $string = \iconv('utf-8', 'us-ascii//TRANSLIT', $string);

        $string = strtolower($string);

        // Remove unwanted characters
        $string = preg_replace('~[^-\w]+~', '', $string);

        $string = trim($string, '-');

        return $string;
    }

    /**
     * Transforms an $uglyString (with incorrect ponctuation) into beautiful string (with correct ponctuation).
     *
     * @param string $uglyString
     *
     * @return string
     */
    public static function beautifulise(string $uglyString): string
    {
        $uglyString = self::normalizeWhitespace($uglyString);
        // Be careful, there are non secable spaces here
        $uglyString = str_replace(['\'\'', ' ;', ' ?', ' !', ' :', ' »', '« ', '\'', '...'], ['"', ' ;', ' ?', ' !', ' :', ' »', '« ', '’', '…'], $uglyString);
        $uglyString = preg_replace('#(\d)\s?([$€£¥])#u', '$1 $2', $uglyString);
        $uglyString = preg_replace_callback('#\d{4,}#u', function ($matches) {
            return number_format((float) $matches[0], 0, ',', ' ');
        }, $uglyString);

        // Count quotes
        $QuotesCount = strlen($uglyString) - strlen(str_replace('"', '', $uglyString));

        // Repeat two times is important for quotes inside quotes
        if ($QuotesCount % 2 == 0) {
            $uglyString = preg_replace('#([\s\r\n\p{P}]|^|)(\")([^\"]*)(\")([\s\p{P}]|$)#u', '$1“$3”$5', $uglyString);
            $uglyString = preg_replace('#([\s\r\n\p{P}]|^|)(\")([^\"]*)(\")([\s\p{P}]|$)#u', '$1“$3”$5', $uglyString);
        }

        return $uglyString;
    }

    /**
     * Removes whitespaces, line breaks and comment out of a $string.
     *
     * @param string $string
     *
     * @return string
     */
    public static function minify(string $string): string
    {
        $string = preg_replace('#\/\*.*\*\/#s', '', $string);
        $string = self::removeLine($string);
        $string = self::normalizeWhitespace($string);
        $string = preg_replace('# ?([\;\:\{\}\,]) ?#', '$1', $string);

        return $string;
    }

    /**
     * Gets the name of a resource (image, pdf, …) out of an $url.
     *
     * @param string $url
     *
     * @return string
     */
    public static function getResourceNameInUrl(string $url): string
    {
        preg_match("/\/([^\/\?]+)(?:[\?\#].*)?$/", $url, $matches);

        return $matches[1] ?? '';
    }

    /**
     * Converts an alphabetic $string into an identifier (an integer).
     *
     * @param string $string
     * @param string $secret to decode the alphabetic string
     *
     * @return int
     */
    public static function alphaToId(string $string, string $secret = ''): int
    {
        $out = 0;
        $index = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $base = strlen($index);
        $stringLength = strlen($string) - 1;

        if ($secret) {
            $splitIndex = str_split($index);

            // Create a new generated secret order based on secret
            array_multisort(array_slice(str_split(hash('sha512', $secret)), 0, $base), SORT_DESC, $splitIndex);
            $index = implode($splitIndex);
        }

        for ($t = $stringLength; $t >= 0; --$t) {
            $bcp = $base ** ($stringLength - $t);
            $out += strpos($index, substr($string, $t, 1)) * $bcp;
        }

        return $out;
    }

    /**
     * Converts a $identifier into an alphanumeric string.
     *
     * @param int    $identifier
     * @param string $secret     to encode the integer
     *
     * @return string
     */
    public static function IdToAlpha(int $identifier, string $secret = ''): string
    {
        $out = '';
        $index = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $base = strlen($index);

        if ($secret) {
            $splitIndex = str_split($index);

            // Create a new generated secret order based on secret
            array_multisort(str_split(substr(hash('sha512', $secret), 0, $base)), SORT_DESC, $splitIndex);
            $index = implode($splitIndex);
        }

        for ($t = ($identifier != 0 ? floor(log($identifier, $base)) : 0); $t >= 0; --$t) {
            $bcp = $base ** $t;
            $a = floor($identifier / $bcp) % $base;
            $out .= substr($index, $a, 1);
            $identifier = $identifier - ($a * $bcp);
        }

        return $out;
    }

    /**
     * Gets a human readable string of a size in $bytes.
     *
     * @param int $bytes
     *
     * @return string
     */
    public static function humanFilesize(int $bytes): string
    {
        $size = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $factor = floor((strlen((string) $bytes) - 1) / 3);

        return MathUtils::smartRound($bytes / pow(1024, $factor)) . @$size[$factor];
    }
}