<?php

namespace Lib;

use \Lib\DomParser;
use \Lib\String;

/**
 * Tool to crawl HTTP pages
 * @depends \Lib\DomParser
 * @depends \Lib\String
 */
class HttpCrawler
{
    const RETRY = 3;

    protected $url;
    protected $domain;
    protected $userAgent = 'Mozilla/5.0 (Windows NT 6.1; rv:35.0) Gecko/20100101 Firefox/35.0';
    protected $cookies = array();
    protected $referer;
    protected $sockProxy;
    protected $sleep = 5;
    protected $sleepMargin = 2;
    protected $recursiveDownload = false;

    protected $httpCode;
    protected $parser;
    protected $rawBody;

    protected $headerAccept = 'application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5';
    protected $headerAcceptLanguage = 'en';
    protected $headerCacheControl = 'max-age=0';
    protected $headerDnt = true;
    protected $headerAcceptEncoding = 'gzip, deflate';
    protected $headerConnection = 'Keep-Alive';
    protected $headerAcceptCharset = 'utf-8';

    protected static $firstTime = true;

    /**
     * Crawl a page
     * @param string $url URL to crawl
     */
    public function __construct($url)
    {
        if (!$this->setUrl($url)) {
            $this->logMessage('The URL (“'.$url.'”) is not valid or domain does not match.');
        }
    }

    /**
     * Try to get content to parse
     */
    public function downloadCurrentPage()
    {
        $this->setRawBody($this->download($this->getUrl()));
        $this->setParser(new DomParser($this->getRawBody()));
        $this->setReferer($this->getUrl());
    }

    /**
     * Simulate click on a link (download a new page to parse)
     * @param string $link New link to parse
     */
    public function clickTo($link)
    {
        if (!$this->setUrl($link)) {
            $this->logMessage('The URL (“'.$link.'”) is not valid or domain does not match');
            return false;
        }
        $this->downloadCurrentPage();
    }

    /**
     * Try to download a content (image, file, etc.)
     * @param string $url Url of the content to download
     * @param string $path Where to save the content
     * @param bool $override True to override previously saved content, false to keep local version
     * @return string Content
     */
    public function downloadContent($url, $path, $override = true)
    {
        $previousUrl = $this->getUrl();

        if (!$this->setUrl($url)) {
            $this->logMessage('The URL (“'.$link.'”) is not valid or domain does not match');
            $this->setUrl($previousUrl);
            return false;
        }

        // Get content name
        preg_match("/\/([^\/]+)$/", $this->getUrl(), $matches);
        if (!empty($matches[1])) {
            $contentName = $matches[1];
        } else {
            $this->logMessage('The ressource (“'.$this->getUrl().'”) got no name - not downloading');
            $this->setUrl($previousUrl);
            return false;
        }

        // File already exists, no override
        if (file_exists($path.$contentName) && !$override) {
            return $contentName;
        }

        // If the content already exist, override
        if (file_exists($path.$contentName) && $override) {
            unlink($path.$contentName);
        }

        // Download content
        $content = $this->download($this->getUrl());
        $saveContentHandler = fopen($path.$contentName, 'x');
        fwrite($saveContentHandler, $content);
        fclose($saveContentHandler);

        // Content too light - not saving
        if (strlen($content) < 10) {
            $this->logMessage('The content seems too light - not saving');
        }

        unset($content);

        $this->setUrl($previousUrl);

        return $path.$contentName;
    }

    /**
     * Log a message into the console
     * @param string $message Message content
     */
    protected function logMessage($message)
    {
        if (php_sapi_name() == "cli") {
            echo '### '.$message.PHP_EOL;
        }
    }

    /**
     * Handle HTTP download with CURL
     * @param $url URL to download
     * @depends php5-curl
     */
    protected function download($url)
    {
        // Wait some time to fool distant servers
        $sleep = (int) mt_rand(($this->getSleep() - $this->getSleepMargin()), ($this->getSleep() + $this->getSleepMargin())).'.'.mt_rand(0, 9);
        if (!self::$firstTime) {
            sleep($sleep);
        } else {
            self::$firstTime = false;
        }

        // Log what we are doing now
        $this->logMessage(date('Y-m-d H:i:s').' - [Sleep time: '.$sleep.'] - Try to get '.$url);

        // Get cURL resource
        $curl = curl_init();

        // Set CURL main CURL options
        if (!empty($url)) {
            curl_setopt($curl, CURLOPT_URL, $url);
        }

        if (!empty($this->getReferer())) {
            curl_setopt($curl, CURLOPT_REFERER, $this->getReferer());
        }

        if (!empty($this->getSockProxy())) {
            curl_setopt($curl, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
            curl_setopt($curl, CURLOPT_PROXY, $this->getSockProxy());
        }

        // Set headers
        $headers = array();
        $headers[]  = 'Host: '.parse_url($url, PHP_URL_HOST);

        if (!empty($this->getUserAgent())) {
            $headers[]  = 'User-Agent: '.$this->getUserAgent();
        }

        if (!empty($this->getHeaderAccept())) {
            $headers[]  = 'Accept: '.$this->getHeaderAccept();
        }

        if (!empty($this->getHeaderAcceptCharset())) {
            $headers[]  = 'Accept-Charset: '.$this->getHeaderAcceptCharset();
        }

        if (!empty($this->getHeaderAcceptEncoding())) {
            $headers[]  = 'Accept-Encoding: '.$this->getHeaderAcceptEncoding();
        }

        if (!empty($this->getHeaderAcceptLanguage())) {
            $headers[]  = 'Accept-Language: '.$this->getHeaderAcceptLanguage();
        }

        if (!empty($this->getHeaderCacheControl())) {
            $headers[]  = 'Cache-Control: '. $this->getHeaderCacheControl();
        }

        if (!empty($this->getHeaderConnection())) {
            $headers[]  = 'Connection: '.$this->getHeaderConnection();
        }

        if (!empty($this->getCookies())) {
            $cookieString = '';
            foreach ($this->getCookies() as $cookieName => $cookieValue) {
                $cookieString .= $cookieName.'='.$cookieValue.';';
            }
            $headers[]  = 'Cookie: '.$cookieString;
        }

        if (!empty($this->getHeaderDnt())) {
            $headers[]  = 'DNT: 1';
        }

        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HEADER => 1,
            CURLOPT_AUTOREFERER => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_DNS_CACHE_TIMEOUT => 3600,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1,
            CURLOPT_HTTPHEADER => $headers,
        ));

        // Send the request & get responses
        $response = curl_exec($curl);
        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $respHeaders = substr($response, 0, $headerSize);

        // Set cookies
        preg_match_all('#\R(?:Set-Cookie\:\ )?([^;:]+);#u', $respHeaders, $matches);
        $cookies = $this->getCookies();
        foreach ($matches[1] as $cookie) {
            $cookie = explode('=', $cookie);
            $cookies[$cookie[0]] = $cookie[1];
        }
        $this->setCookies($cookies);

        // Close request to clear up some resources
        curl_close($curl);

        // Error log message
        if ($httpCode != 200) {
            $this->logMessage('Error when getting '.$url.' (CODE : '.$httpCode);
        }

        // Return content - handling gzip if necessary
        if (preg_match("#.*Content\-Encoding\: gzip.*#", $respHeaders)) {
            return gzdecode(substr($response, $headerSize));
        } else {
            return substr($response, $headerSize);
        }
    }

   /* ### GETTER & SETTER ### */

    public function getRecursiveDownload()
    {
        return $this->recursiveDownload;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getUserAgent()
    {
        return $this->userAgent;
    }

    public function getCookies()
    {
        return $this->cookies;
    }

    public function getReferer()
    {
        return $this->referer;
    }

    public function getSockProxy()
    {
        return $this->sockProxy;
    }

    public function getSleep()
    {
        return $this->sleep;
    }

    public function getSleepMargin()
    {
        return $this->sleepMargin;
    }

    public function getHttpCode()
    {
        return $this->httpCode;
    }

    /**
     * @return DomParser
     */
    public function getParser()
    {
        return $this->parser;
    }

    public function getDomBody()
    {
        return $this->domBody;
    }

    public function getRawBody()
    {
        return $this->rawBody;
    }

    public function getHeaderAccept()
    {
        return $this->headerAccept;
    }

    public function getHeaderAcceptLanguage()
    {
        return $this->headerAcceptLanguage;
    }

    public function getHeaderCacheControl()
    {
        return $this->headerCacheControl;
    }

    public function getHeaderDnt()
    {
        return $this->headerDnt;
    }

    public function getHeaderAcceptEncoding()
    {
        return $this->headerAcceptEncoding;
    }

    public function getHeaderConnection()
    {
        return $this->headerConnection;
    }

    public function getHeaderAcceptCharset()
    {
        return $this->headerAcceptCharset;
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function setRecursiveDownload($recursiveDownload)
    {
        $this->recursiveDownload = $recursiveDownload;
        return $this;
    }

    public function setUrl($url)
    {
        if (String::isUrl($url)) {
            $urlInfo = parse_url($url);

            if (empty($this->getDomain())) {
                $this->domain = $urlInfo['host'];
            }

            if ($urlInfo['host'] == $this->getDomain()) {
                $this->url = $url;
                return $this;
            }
        }

        return false;
    }

    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
        return $this;
    }

    public function setCookies($cookies)
    {
        $this->cookies = $cookies;
        return $this;
    }

    public function setReferer($referer)
    {
        $this->referer = $referer;
        return $this;
    }

    public function setSockProxy($proxy)
    {
        $this->sockProxy = $proxy;
        return $this;
    }

    public function setSleep($sleep)
    {
        $this->sleep = $sleep;
        return $this;
    }

    public function setSleepMargin($sleepMargin)
    {
        $this->sleepMargin = $sleepMargin;
        return $this;
    }

    public function setParser($parser)
    {
        $this->parser = $parser;
        return $this;
    }

    public function setDomBody($domBody)
    {
        $this->domBody = $domBody;
        return $this;
    }

    public function setRawBody($rawBody)
    {
        $this->rawBody = $rawBody;
        return $this;
    }

    public function setHeaderAccept($headerAccept)
    {
        $this->headerAccept = $headerAccept;
        return $this;
    }

    public function setHeaderAcceptLanguage($headerAcceptLanguage)
    {
        $this->headerAcceptLanguage = $headerAcceptLanguage;
        return $this;
    }

    public function setHeaderCacheControl($headerCacheControl)
    {
        $this->headerCacheControl = $headerCacheControl;
        return $this;
    }

    public function setHeaderDnt($headerDnt)
    {
        $this->headerDnt = $headerDnt;
        return $this;
    }

    public function setHeaderAcceptEncoding($headerAcceptEncoding)
    {
        $this->headerAcceptEncoding = $headerAcceptEncoding;
        return $this;
    }

    public function setHeaderConnection($headerConnection)
    {
        $this->headerConnection = $headerConnection;
        return $this;
    }

    public function setHeaderAcceptCharset($headerAcceptCharset)
    {
        $this->headerAcceptCharset = $headerAcceptCharset;
        return $this;
    }
}