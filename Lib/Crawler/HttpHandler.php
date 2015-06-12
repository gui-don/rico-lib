<?php

namespace Rico\Lib\Crawler;

use Rico\Lib\Crawler\HttpRequest;
use Rico\Lib\Crawler\HttpResponse;
use Rico\Lib\Crawler\DomParser;
use Rico\Lib\Crawler\Exception\ResponseException;

/**
 * Tool to handle HTTP pages
 * @depends php5-curl
 */
class HttpHandler
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
    protected $disableReferer = false;
    protected $strictMode = true;

    private $firstTime = true;

    /**
     * Crawl a page
     * @param string $url URL to crawl
     */
    public function __construct($url)
    {
        $this->setRequest(new HttpRequest($url));
    }

    /**
     * Get the content of the current page
     */
    public function get()
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
            $this->getRequest()->setHeaderReferer('');
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
        preg_match('#\R(?:Set-Cookie\:\ )(\N+);?#u', $this->getResponse()->getHeaders(), $matches);
        if (isset($matches[1])) {
            $newCookies = array();
            parse_str(preg_replace('#\; ?#', '&', trim($matches[1])), $newCookies);
            $oldCookies = array();
            parse_str(preg_replace('#\; ?#', '&', $this->getRequest()->getHeaderCookie()), $oldCookies);

            $this->getRequest()->setHeaderCookie(http_build_query($newCookies + $oldCookies, '', '; '));
        }
    
        // Set referer
        if (!$this->getDisableReferer()) {
            $this->getRequest()->setHeaderReferer($this->getRequest()->getUrl());
        } else {
            
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
        $previousHost = $this->getRequest()->getHeaderHost();

        // Set a new URL
        $this->getRequest()->setUrl($url);

        if ($preserveHost) {
            $this->getRequest()->setHeaderHost($previousHost);
        }

        // Set the first time variable to determine the delay
        $newHost = $this->getRequest()->getHeaderHost();
        if ($newHost == $previousHost) {
            $this->setFirstTime(false);
        } else {
            $this->setFirstTime(true);
        }

        // Download the content
        $this->get();
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