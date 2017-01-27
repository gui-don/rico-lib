<?php

declare(strict_types=1);

namespace Rico\Lib;

use Rico\Slib\StringUtils as StaticStringUtils;

class StringUtils
{
    /**
     * Removes all sort of spaces from a $string.
     *
     * @param string $string
     *
     * @return string
     */
    public function removeWhitespace(string $string): string
    {
        return StaticStringUtils::removeWhitespace($string);
    }

    /**
     * Replaces all sort of spaces (tab, nil, non-breaking…) in a $string by a simple space.
     *
     * @param string $string
     *
     * @return string
     */
    public function normalizeWhitespace(string $string): string
    {
        return StaticStringUtils::normalizeWhitespace($string);
    }

    /**
     * Removes all sort of line breaks inside a $string.
     *
     * @param string $string
     *
     * @return string
     */
    public function removeLine(string $string): string
    {
        return StaticStringUtils::removeLine($string);
    }

    /**
     * Cleans a $string by removing multi-spaces, line breaks, indents and HTML tags.
     *
     * @param string $string
     *
     * @return string
     */
    public function normalize(string $string): string
    {
        return StaticStringUtils::normalize($string);
    }

    /**
     * Generates a random string of $length $allowedChars.
     *
     * @param int    $length
     * @param string $allowedChars
     *
     * @return string
     */
    public function randString(int $length = 10, string $allowedChars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'): string
    {
        return StaticStringUtils::randString($length, $allowedChars);
    }

    /**
     * Transforms a $string into a ascii-only string separated by -.
     *
     * @param string $string
     *
     * @return string
     */
    public function slugify(string $string): string
    {
        return StaticStringUtils::slugify($string);
    }

    /**
     * Transforms an ugly $string (with incorrect ponctuation) into beautiful string (with correct ponctuation).
     *
     * @param string $string
     *
     * @return string
     */
    public function beautifulise(string $string): string
    {
        return StaticStringUtils::beautifulise($string);
    }

    /**
     * Removes whitespaces, line breaks and comment out of a $string.
     *
     * @param string $string
     *
     * @return string
     */
    public function minify(string $string): string
    {
        return StaticStringUtils::minify($string);
    }

    /**
     * Gets the name of a resource (image, pdf, …) out of an $url.
     *
     * @param string $url
     *
     * @return string
     */
    public function getResourceNameInUrl(string $url): string
    {
        return StaticStringUtils::getResourceNameInUrl($url);
    }

    /**
     * Converts an alphabetic $string into an identifier (an integer).
     *
     * @param string $string
     * @param string $secret to decode the alphabetic string
     *
     * @return int|string
     */
    public function alphaToId(string $string, string $secret = '')
    {
        return StaticStringUtils::alphaToId($string, $secret);
    }

    /**
     * Converts a $identifier into an alphanumeric string.
     *
     * @param int    $identifier
     * @param string $secret     to encode the integer
     *
     * @return string
     */
    public function IdToAlpha(int $identifier, string $secret = ''): string
    {
        return StaticStringUtils::IdToAlpha($identifier, $secret);
    }

    /**
     * Gets a human readable string of a size in $bytes.
     *
     * @param int $bytes
     *
     * @return string
     */
    public function humanFilesize(int $bytes): string
    {
        return StaticStringUtils::humanFilesize($bytes);
    }
}
