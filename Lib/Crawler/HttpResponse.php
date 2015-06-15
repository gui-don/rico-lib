<?php

namespace Rico\Lib\Crawler;

use Rico\Lib\Crawler\Exception\FileException;
use Rico\Lib\Crawler\Interfaces\HttpResponseInterface;
use Rico\Lib\File;
use Rico\Lib\String;

/**
 * HttpResponse class
 */
class HttpResponse implements HttpResponseInterface
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
     * @param type $filename
     * @param type $override
     */
    public function save($filename, $override = true)
    {
        if(!is_string($filename)) {
            return false;
        }

        // Create a filename id needed
        if (is_dir($filename)) {
            $filename .= md5($this->getContent()).'.'.String::getResourceNameInUrl($this->getMime());
        }

        // File already exists, without override
        if (file_exists($filename) && !$override) {
            throw new FileException('Cannot save the downloaded content to “'.$filename.'”. Target already exist! Use override=true to ignore.');
        }

        // If the content already exist, with override
        if (file_exists($filename) && $override) {
            unlink($filename);
        }

        // Create path if it does not exist
        File::createPath(dirname($filename));

        $saveContentHandler = fopen($filename, 'w');
        fwrite($saveContentHandler, $this->getContent());
        fclose($saveContentHandler);

        return true;
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