<?php

namespace Rico\Lib\Crawler;

class HttpHeader
{
    protected $headers = array();

    /**
     * Create a new header object
     * @param $rawHeaders Raw header string
     */
    public function __construct($rawHeaders = '')
    {
        $this->hydrate($rawHeaders);
    }

    /**
     * Hydrate raw headers into a readable HttpHeader object
     * @param $rawHeaders Raw Header string
     */
    public function hydrate($rawHeaders)
    {
        preg_match_all('#^([A-Za-z0-9\-]+)\s?\:\s?([^\r\n\f\x0b\x85]+)#m', $rawHeaders, $matches);

        for ($i = 0, $j = count($matches[0]); $i < $j; $i++) {
            if (isset($this->headers[strtolower($matches[1][$i])][0])) {
                $this->headers[strtolower($matches[1][$i])][1] = $matches[2][$i];
            } else {
                $this->addHeader($matches[1][$i], $matches[2][$i]);
            }
        }

        return $this;
    }

    /**
     * Convert this object into an array
     */
    public function convert()
    {
        $headers = array();
        foreach ($this->headers as $header) {
            if (!empty($header[1])) {
                $headers[$header[0]] = $header[1];
            }
        }

        return $headers;
    }

    /**
     * Add another header
     * @param string $header Header
     * @param string $value Value of the header
     */
    public function addHeader($header, $value)
    {
        if (!is_string($header) || !is_string($value)) {
            return false;
        }

        $this->headers[strtolower($header)][0] = $header;
        $this->headers[strtolower($header)][1] = $value;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

}
