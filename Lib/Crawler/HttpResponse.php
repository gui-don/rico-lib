<?php

namespace Rico\Lib\Crawler;

use Rico\Lib\Crawler\Interfaces\HttpResponseInterface;

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
     * @param type $path
     * @param type $override
     */
    public function save($path, $override = true)
    {
//        if (!empty($url)) {
//            $previousUrl = $this->getUrl();
//
//            if (!$this->setUrl($url)) {
//                $this->setUrl($previousUrl);
//                throw new InvalidUrlException('The URL (“'.$url.'”) is not valid or host does not match.');
//            }
//        }
//
//        // Download content
//        $content = $this->download($this->getUrl());
//
//        // Get content name
//        $contentName = String::getResourceNameInUrl($url);
//        if (empty($contentName)) {
//            md5($content);
//        }
//
//        var_dump(fil$content);
//        exit;
//
//        // File already exists, without override
//        if (file_exists($path.$contentName) && !$override) {
//            throw new FileException('Cannot save the downloaded content to “'.$path.$contentName.'”. Target already exist! Use override=true to ignore.');
//        }
//
//        // If the content already exist, with override
//        if (file_exists($path.$contentName) && $override) {
//            unlink($path.$contentName);
//        }
//
//        $saveContentHandler = fopen($path.$contentName, 'x');
//        fwrite($saveContentHandler, $content);
//        fclose($saveContentHandler);
//
//        // Content too light - not saving
//        if (strlen($content) < 10) {
//            $this->riseError('The content seems too light - not saving');
//        }
//
//        unset($content);
//
//        $this->setUrl($previousUrl);
//
//        return $path.$contentName;
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