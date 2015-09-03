<?php

namespace Rico\Lib\Crawler;

use Rico\Lib\Crawler\Interfaces\HttpRequestInterface;
use Rico\Lib\Crawler\HttpRequest;
use Rico\Lib\Crawler\HttpResponse;
use Rico\Lib\Crawler\DomParser;
use Rico\Lib\Crawler\Exception\ResponseException;

/**
 * Tool to handle HTTP pages
 * @depends php5-curl
 */
class HttpBrowser
{
    /**
     * @var HttpRequest
     */
    protected $request;
    /**
     * @var HttpResponse
     */
    protected $response;
    /**
     * @var DomParser
     */
    protected $parser;

    protected $delay = 0;
    protected $delayMargin = 0;
    /**
     * Determine whether or not the referer to be set and send at each request
     * @var bool
     */
    protected $disableReferer = false;
    /**
     * Determine whether or not an error should rise if the HTTP code is not 200
     * @var bool
     */
    protected $strictMode = true;

    private $firstTime = true;

    /**
     * Crawl a page
     * @param string $httpRequest URL to crawl
     */
    public function __construct()
    {
        $this->setRequest(new HttpRequest());
    }

    /**
     * Get the content of the current page
     */
    public function load()
    {
        // Wait some time to fool distant servers - bypass the first time
        $sleep = (int) mt_rand(($this->getDelay() - $this->getDelayMargin()), ($this->getDelay() + $this->getDelayMargin())).'.'.mt_rand(0, 9);
        if (!$this->getFirstTime() && $sleep > 0) {
            usleep($sleep * 1000000);
        } else {
            $this->setFirstTime(false);
        }

        // Disable referer if needed
        if ($this->getDisableReferer()) {
            $this->getRequest()->getHeaders()->setReferer('');
        }

        // Send request and get response
        $this->setResponse($this->getRequest()->send());

        // Handle error
        if ($this->getStrictMode() && $this->getResponse()->getCode() != 200) {
            throw new ResponseException('Requesting “'.$this->getRequest()->getUrl().'” returns an error (CODE: '.$this->getResponse()->getCode().')');
        }

        // Create a new DomParser with the downloaded content
        $this->setParser(new DomParser($this->getResponse()->getContent()));

        // Set cookies if needed
        if ($this->getResponse()->getHeaders()->getSetCookie()) {
            $newCookies = array();
            parse_str(preg_replace('#\; ?#', '&', trim($this->getResponse()->getHeaders()->getSetCookie())), $newCookies);
            $oldCookies = array();
            parse_str(preg_replace('#\; ?#', '&', $this->getRequest()->getHeaders()->getCookie()), $oldCookies);

            $this->getRequest()->getHeaders()->setCookie(http_build_query($newCookies + $oldCookies, '', '; '));
        }

        // Set referer
        if (!$this->getDisableReferer()) {
            $this->getRequest()->getHeaders()->setReferer($this->getRequest()->getUrl());
        }
    }

    /**
     * Simulate click on a link (download a new page to parse)
     * @param string $url New link to download
     * @param bool $preserveHost Determine wether or not the host must not change (useful when your URL contains IP instead of domain)
     */
    public function clickTo($url, $preserveHost = false)
    {
        // Get previous host
        $previousHost = $this->getRequest()->getHeaders()->getHost();

        // Set a new URL
        $this->getRequest()->setUrl($url);

        if ($preserveHost) {
            $this->getRequest()->getHeaders()->setHost($previousHost);
        }

        // Set the first time variable to determine the delay
        $newHost = $this->getRequest()->getHeaders()->getHost();
        if ($newHost == $previousHost) {
            $this->setFirstTime(false);
        } else {
            $this->setFirstTime(true);
        }

        // Download the content
        $this->load();
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function getParser()
    {
        return $this->parser;
    }

    public function getDelay()
    {
        return $this->delay;
    }

    public function getDelayMargin()
    {
        return $this->delayMargin;
    }

    public function getDisableReferer()
    {
        return $this->disableReferer;
    }

    public function getStrictMode()
    {
        return $this->strictMode;
    }

    public function getFirstTime()
    {
        return $this->firstTime;
    }

    public function setRequest(HttpRequest $request)
    {
        $this->request = $request;
        return $this;
    }

    public function setResponse(HttpResponse $response)
    {
        $this->response = $response;
        return $this;
    }

    public function setParser(DomParser $parser)
    {
        $this->parser = $parser;
        return $this;
    }

    public function setDelay($delay)
    {
        $this->delay = $delay;
        return $this;
    }

    public function setDelayMargin($delayMargin)
    {
        $this->delayMargin = $delayMargin;
        return $this;
    }

    public function setDisableReferer($disableReferer)
    {
        $this->disableReferer = $disableReferer;
        return $this;
    }

    public function setStrictMode($strictMode)
    {
        $this->strictMode = $strictMode;
        return $this;
    }

    public function setFirstTime($firstTime)
    {
        $this->firstTime = $firstTime;
        return $this;
    }

}