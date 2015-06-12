<?php

namespace Rico\Lib\Crawler;

use Rico\Lib\Checker;
use Rico\Lib\Crawler\HttpResponse;
use Rico\Lib\Crawler\Exception\DownloadException;
use Rico\Lib\Crawler\Exception\ResponseException;
use Rico\Lib\Crawler\Exception\InvalidUrlException;

class HttpRequest
{
    const STATUS_NOT_SEND = 0;
    const STATUS_SEND = 1;

    protected $status = self::STATUS_NOT_SEND;

    protected $url;
    protected $sockProxy;

    protected $headerAccept = 'application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5';
    protected $headerAcceptLanguage = 'en';
    protected $headerAcceptEncoding = 'gzip, deflate';
    protected $headerAcceptCharset = 'utf-8';
    protected $headerCacheControl = 'max-age=0';
    protected $headerConnection = 'Keep-Alive';
    protected $headerCookie;
    protected $headerContentLength;
    protected $headerContentType;
    protected $headerHost;
    protected $headerIfMatch;
    protected $headerIfModifiedSince;
    protected $headerIfNoneMatch;
    protected $headerMaxForwards;
    protected $headerOrigin;
    protected $headerPragma;
    protected $headerReferer;
    protected $headerUserAgent = 'Mozilla/5.0 (Windows NT 6.1; rv:40.0) Gecko/20100101 Firefox/40.0';
    protected $headerVia;

    protected $headerDnt;
    protected $headerForwardedFor;
    protected $headerForwardedHost;
    protected $headerForwardedProto;
    protected $headerRequestedWith;
    protected $headerProxyConnection;


    public function __construct($url)
    {
        if (!$this->setUrl($url)) {
            throw new InvalidUrlException('The URL (“'.$url.'”) is invalid.');
        }
    }

    /**
     * Send a http request and get the response
     * @return HttpResponse
     * @throws DownloadException
     */
    public function send()
    {
        // Set headers
        $headers = array();
        if (!empty($this->getHeaderHost())) {
            $headers[]  = 'Host: '.$this->getHeaderHost();
        }

        if (!empty($this->getHeaderUserAgent())) {
            $headers[]  = 'User-Agent: '.$this->getHeaderUserAgent();
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

        if (!empty($this->getHeaderContentLength())) {
            $headers[]  = 'Content-Length: '.$this->getHeaderContentLength();
        }

        if (!empty($this->getHeaderContentType())) {
            $headers[]  = 'Content-Type: '.$this->getHeaderContentType();
        }

        if (!empty($this->getHeaderIfMatch())) {
            $headers[]  = 'If-Match: '.$this->getHeaderIfMatch();
        }

        if (!empty($this->getHeaderIfModifiedSince())) {
            $headers[]  = 'If-Modified-Since: '.$this->getHeaderIfModifiedSince();
        }

        if (!empty($this->getHeaderIfNoneMatch())) {
            $headers[]  = 'If-None-Match: '.$this->getHeaderIfNoneMatch();
        }

        if (!empty($this->getHeaderMaxForwards())) {
            $headers[]  = 'Max-Forwards: '.$this->getHeaderMaxForwards();
        }

        if (!empty($this->getHeaderOrigin())) {
            $headers[]  = 'Origin: '.$this->getHeaderOrigin();
        }

        if (!empty($this->getHeaderPragma())) {
            $headers[]  = 'Pragma: '.$this->getHeaderPragma();
        }

        if (!empty($this->getHeaderReferer())) {
            $headers[]  = 'Pragma: '.$this->getHeaderReferer();
        }

        if (!empty($this->getHeaderVia())) {
            $headers[]  = 'Via: '.$this->getHeaderVia();
        }

        if (!empty($this->getHeaderProxyConnection())) {
            $headers[]  = 'Proxy-Connection: '.$this->getHeaderProxyConnection();
        }

        if (!empty($this->getHeaderForwardedFor())) {
            $headers[]  = 'X-Forwarded-For: '.$this->getHeaderForwardedFor();
        }

        if (!empty($this->getHeaderForwardedHost())) {
            $headers[]  = 'X-Forwarded-Host: '.$this->getHeaderForwardedHost();
        }

        if (!empty($this->getHeaderForwardedProto())) {
            $headers[]  = 'X-Forwarded-Proto: '.$this->getHeaderForwardedProto();
        }

        if (!empty($this->getHeaderRequestedWith())) {
            $headers[]  = 'X-Requested-With: '.$this->getHeaderRequestedWith();
        }

        if (!empty($this->getHeaderCookie())) {
            $headers[]  = 'Cookie: '.$this->getHeaderCookie();
        }

        if (!empty($this->getHeaderDnt())) {
            $headers[]  = 'DNT: 1';
        }

        if (!empty($this->getHeaderProxyConnection())) {
            $headers[]  = 'Proxy-Connection: '.$this->getHeaderProxyConnection();
        }

        if (!empty($this->getHeaderReferer())) {
            $headers[]  = 'Referer: '.$this->getHeaderReferer();
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

    public function getHeaderAccept()
    {
        return $this->headerAccept;
    }

    public function getHeaderAcceptLanguage()
    {
        return $this->headerAcceptLanguage;
    }

    public function getHeaderAcceptEncoding()
    {
        return $this->headerAcceptEncoding;
    }

    public function getHeaderAcceptCharset()
    {
        return $this->headerAcceptCharset;
    }

    public function getHeaderCacheControl()
    {
        return $this->headerCacheControl;
    }

    public function getHeaderConnection()
    {
        return $this->headerConnection;
    }

    public function getHeaderCookie()
    {
        return $this->headerCookie;
    }

    public function getHeaderContentLength()
    {
        return $this->headerContentLength;
    }

    public function getHeaderContentType()
    {
        return $this->headerContentType;
    }

    public function getHeaderHost()
    {
        return $this->headerHost;
    }

    public function getHeaderIfMatch()
    {
        return $this->headerIfMatch;
    }

    public function getHeaderIfModifiedSince()
    {
        return $this->headerIfModifiedSince;
    }

    public function getHeaderIfNoneMatch()
    {
        return $this->headerIfNoneMatch;
    }

    public function getHeaderMaxForwards()
    {
        return $this->headerMaxForwards;
    }

    public function getHeaderOrigin()
    {
        return $this->headerOrigin;
    }

    public function getHeaderPragma()
    {
        return $this->headerPragma;
    }

    public function getHeaderReferer()
    {
        return $this->headerReferer;
    }

    public function getHeaderUserAgent()
    {
        return $this->headerUserAgent;
    }

    public function getHeaderVia()
    {
        return $this->headerVia;
    }

    public function getHeaderDnt()
    {
        return $this->headerDnt;
    }

    public function getHeaderForwardedFor()
    {
        return $this->headerForwardedFor;
    }

    public function getHeaderForwardedHost()
    {
        return $this->headerForwardedHost;
    }

    public function getHeaderForwardedProto()
    {
        return $this->headerForwardedProto;
    }

    public function getHeaderRequestedWith()
    {
        return $this->headerRequestedWith;
    }

    public function getHeaderProxyConnection()
    {
        return $this->headerProxyConnection;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function setUrl($url)
    {
        if (Checker::isUrl($url)) {
            $this->url = $url;
            $this->setStatus(self::STATUS_NOT_SEND);

            if (empty($this->getHeaderHost()) || parse_url($url, PHP_URL_HOST) != $this->getHeaderHost()) {
                $this->setHeaderHost(parse_url($url, PHP_URL_HOST));
            }

            return $this;
        }

        return false;
    }

    public function setSockProxy($sockProxy)
    {
        $this->sockProxy = $sockProxy;
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

    public function setHeaderAcceptEncoding($headerAcceptEncoding)
    {
        $this->headerAcceptEncoding = $headerAcceptEncoding;
        return $this;
    }

    public function setHeaderAcceptCharset($headerAcceptCharset)
    {
        $this->headerAcceptCharset = $headerAcceptCharset;
        return $this;
    }

    public function setHeaderCacheControl($headerCacheControl)
    {
        $this->headerCacheControl = $headerCacheControl;
        return $this;
    }

    public function setHeaderConnection($headerConnection)
    {
        $this->headerConnection = $headerConnection;
        return $this;
    }

    public function setHeaderCookie($headerCookie)
    {
        $this->headerCookie = $headerCookie;
        return $this;
    }

    public function setHeaderContentLength($headerContentLength)
    {
        $this->headerContentLength = $headerContentLength;
        return $this;
    }

    public function setHeaderContentType($headerContentType)
    {
        $this->headerContentType = $headerContentType;
        return $this;
    }

    public function setHeaderHost($headerHost)
    {
        $this->headerHost = $headerHost;
        return $this;
    }

    public function setHeaderIfMatch($headerIfMatch)
    {
        $this->headerIfMatch = $headerIfMatch;
        return $this;
    }

    public function setHeaderIfModifiedSince($headerIfModifiedSince)
    {
        $this->headerIfModifiedSince = $headerIfModifiedSince;
        return $this;
    }

    public function setHeaderIfNoneMatch($headerIfNoneMatch)
    {
        $this->headerIfNoneMatch = $headerIfNoneMatch;
        return $this;
    }

    public function setHeaderMaxForwards($headerMaxForwards)
    {
        $this->headerMaxForwards = $headerMaxForwards;
        return $this;
    }

    public function setHeaderOrigin($headerOrigin)
    {
        $this->headerOrigin = $headerOrigin;
        return $this;
    }

    public function setHeaderPragma($headerPragma)
    {
        $this->headerPragma = $headerPragma;
        return $this;
    }

    public function setHeaderReferer($headerReferer)
    {
        $this->headerReferer = $headerReferer;
        return $this;
    }

    public function setHeaderUserAgent($headerUserAgent)
    {
        $this->headerUserAgent = $headerUserAgent;
        return $this;
    }

    public function setHeaderVia($headerVia)
    {
        $this->headerVia = $headerVia;
        return $this;
    }

    public function setHeaderDnt($headerDnt)
    {
        $this->headerDnt = $headerDnt;
        return $this;
    }

    public function setHeaderForwardedFor($headerForwardedFor)
    {
        $this->headerForwardedFor = $headerForwardedFor;
        return $this;
    }

    public function setHeaderForwardedHost($headerForwardedHost)
    {
        $this->headerForwardedHost = $headerForwardedHost;
        return $this;
    }

    public function setHeaderForwardedProto($headerForwardedProto)
    {
        $this->headerForwardedProto = $headerForwardedProto;
        return $this;
    }

    public function setHeaderRequestedWith($headerRequestedWith)
    {
        $this->headerRequestedWith = $headerRequestedWith;
        return $this;
    }

    public function setHeaderProxyConnection($headerProxyConnection)
    {
        $this->headerProxyConnection = $headerProxyConnection;
        return $this;
    }


}
