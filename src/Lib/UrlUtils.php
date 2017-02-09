<?php

declare(strict_types=1);

namespace Rico\Lib;

use Rico\Slib\UrlUtils as StaticUrlUtils;

class UrlUtils
{
    /**
     * Gets the name of a resource (image, pdf, …) out of an $url.
     *
     * @param string $url
     *
     * @return string
     */
    public function getResourceName(string $url): string
    {
        return StaticUrlUtils::getResourceName($url);
    }

    /** Gets the URL without the resource (image, pdf, …).
     * @param string $url
     *
     * @return string
     */
    public function stripResourceName(string $url): string
    {
        return StaticUrlUtils::stripResourceName($url);
    }
}
