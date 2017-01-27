<?php

declare(strict_types=1);

namespace Rico\Slib;

abstract class UrlUtils
{
    /**
     * Gets the name of a resource (image, pdf, …) out of an $url.
     *
     * @param string $url
     *
     * @return string
     */
    public static function getResourceName(string $url): string
    {
        preg_match("/\/([^\/\?]+)(?:[\?\#].*)?$/", $url, $matches);

        return $matches[1] ?? '';
    }

    /**
     * Gets the URL without the resource (image, pdf, …).
     *
     * @param string $url
     *
     * @return string
     */
    public static function getUrlWithoutResourceName(string $url): string
    {
        preg_match("#([^\?]+\/)(?:[^\/\?]*\??.*)$#", $url, $matches);

        return $matches[1] ?? '';
    }
}
