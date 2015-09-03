<?php

namespace Rico\Lib\Crawler;

use Rico\Lib\Crawler\Interfaces\HttpRequestInterface;
use Rico\Lib\Crawler\Interfaces\HttpRequestHeaderInterface;
use Rico\Lib\Checker;
use Rico\Lib\Crawler\HttpResponse;
use Rico\Lib\Crawler\Exception\DownloadException;
use Rico\Lib\Crawler\Exception\InvalidUrlException;
use Rico\Lib\Crawler\Exception\RequestException;

class HttpRequest implements HttpRequestInterface
{
    const STATUS_NOT_SEND = 0;
    const STATUS_SEND = 1;

    protected $status = self::STATUS_NOT_SEND;

    protected $url;
    protected $sockProxy;
    /**
     * Determine whether or not the request should follow HTTP redirection (301 and 302)
     * @var bool
     */
    protected $followRedirection = true;

    /**
     * @var HttpRequestHeaderInterface
     */
    protected $headers;

    public function __construct()
    {
        $this->setHeaders(new HttpRequestHeader());
    }

    /**
     * Send a http request and get the response
     * @return HttpResponse
     * @throws DownloadException
     */
    public function send()
    {
        if (empty($this->getUrl())) {
            throw new RequestException('Cannot send a request. No URL!');
        }

        // Construct header array
        $headers = array();
        foreach ($this->getHeaders()->convert() as $header => $value) {
            $headers[] = $header.': '.$value;
        }

        // Get cURL resource
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->getUrl());

        if (!empty($this->getSockProxy())) {
            curl_setopt($curl, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
            curl_setopt($curl, CURLOPT_PROXY, $this->getSockProxy());
        }

        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HEADER => 1,
            CURLOPT_AUTOREFERER => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_DNS_CACHE_TIMEOUT => 3600,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1,
            CURLOPT_FOLLOWLOCATION => $this->getFollowRedirection(),
            CURLOPT_HTTPHEADER => $headers,
        ));

        if ($response = curl_exec($curl)) {
            $this->setStatus(self::STATUS_SEND);
            $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
            $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $responseHeaders = substr($response, 0, $headerSize);
            $responseContent = substr($response, $headerSize);
            preg_match('#[Cc]ontent\-[Tt]ype\: ([A-Za-z\-\/\.]+)#', $responseHeaders, $matches);
            $responseType = $matches[1];

            // Handling compression if necessary
            if (preg_match("#[Cc]ontent\-[Ee]ncoding\: gzip#", $responseHeaders)) {
                $responseContent = gzdecode($responseContent);
            } else if (preg_match("#[Cc]ontent\-[Ee]ncoding\: deflate#", $responseHeaders)) {
                $responseContent = gzinflate($responseContent);
            }

            // Close request
            curl_close($curl);

            return new HttpResponse((string) $responseContent, $responseCode, (string) $responseHeaders, (string) $responseType);
        } else {
            throw new DownloadException('Cannot send the http request to “'.$this->getUrl().'”. Curl returned this error: “'.curl_error($curl).'”');
        }
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getSockProxy()
    {
        return $this->sockProxy;
    }

    public function getFollowRedirection()
    {
        return $this->followRedirection;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function setHeaders(HttpRequestHeaderInterface $headers)
    {
        $this->headers = $headers;
        return $this;
    }

    public function setUrl($url)
    {
        if (Checker::isUrl($url)) {
            $this->url = $url;
            $this->setStatus(self::STATUS_NOT_SEND);

            if (empty($this->getHeaders()->getHost()) || parse_url($url, PHP_URL_HOST) != $this->getHeaders()->getHost()) {
                $this->getHeaders()->setHost(parse_url($url, PHP_URL_HOST));
            }

            return $this;
        }

        // If there is no URL, throw an exception
        if (empty($this->url)) {
            throw new InvalidUrlException('The URL (“'.$url.'”) is invalid.');
        }

        return false;
    }

    public function setSockProxy($sockProxy)
    {
        $this->sockProxy = $sockProxy;
        return $this;
    }


    public function setFollowRedirection($followRedirection)
    {
        $this->followRedirection = $followRedirection;
        return $this;
    }

}
