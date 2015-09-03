<?php

namespace Rico\Lib\Crawler\Interfaces;

use Rico\Lib\Crawler\Interfaces\HttpRequestHeaderInterface;

/**
 * HttpRequest Interface
 */
interface HttpRequestInterface
{
    public function __construct();

    /**
     * Send a http request and get the response
     * @return HttpResponse
     * @throws DownloadException
     */
    public function send();

    public function getStatus();

    public function getUrl();

    public function getSockProxy();

    public function getFollowRedirection();

    public function getHeaders();

    public function setStatus($status);

    public function setUrl($url);

    public function setSockProxy($sockProxy);

    public function setFollowRedirection($followRedirection);

    public function setHeaders(HttpRequestHeaderInterface $headers);
}
