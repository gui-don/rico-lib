<?php

namespace Rico\Lib\Crawler\Interfaces;

/**
 * HttpResponse interface
 */
interface HttpResponseInterface
{
    /**
     * Create a new HttpResponse object
     * @param type $content
     * @param type $code
     * @param type $headers
     * @param type $mime
     */
    public function __construct($content, $code, $headers = array(), $mime = '');

    public function save($path, $override = true);

    public function getContent();

    public function getMime();

    public function getCode();

    public function getHeaders();

    public function setContent($content);

    public function setMime($mime);

    public function setCode($code);

    public function setHeaders($headers);

}