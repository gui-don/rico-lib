<?php

namespace Rico\Lib\Crawler\Interfaces;

/**
 * HttpHeader Interface
 */
interface HttpHeaderInterface
{
    public function convert();

    public function hydrate($rawHeader);

    public function addHeader($header, $value);

    public function getHeaders();
}
