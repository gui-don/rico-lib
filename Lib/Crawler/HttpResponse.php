<?php

namespace Rico\Lib\Crawler;

/**
 * HttpResponse class
 */
class HttpResponse
{
    protected $content;
    protected $mime;
    protected $code;
    protected $headers;

    /**
     * Create a new HttpResponse object
     * @param type $content
     * @param type $code
     * @param type $headers
     * @param type $mime
     */
    public function __construct($content, $code, $headers = array(), $mime = '')
    {
        $this->setContent($content);
        $this->setCode($code);
        $this->setHeaders($headers);
        $this->setMime($mime);
    }

    /**
     * Save the response in a file
     * @param type $path
     * @param type $override
     */
    public function save($path, $override = true)
    {
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getMime()
    {
        return $this->mime;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    public function setMime($mime)
    {
        $this->mime = $mime;
        return $this;
    }

    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    public function setHeaders($headers)
    {
        $this->headers = $headers;
        return $this;
    }

}