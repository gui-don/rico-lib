<?php

namespace Rico\Lib\Crawler;

use Rico\Lib\Crawler\HttpHeader;
use Rico\Lib\Crawler\Interfaces\HttpRequestHeaderInterface;

class HttpRequestHeader extends HttpHeader implements HttpRequestHeaderInterface
{
    protected $headers = array(
        'accept' => array('Accept', 'application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5'),
        'accept-charset' => array('Accept-Charset', 'utf-8'),
        'accept-encoding' => array('Accept-Encoding', 'gzip, deflate'),
        'accept-language' => array('Accept-Language', 'en'),
        'accept-datetime' => array('Accept-Datetime', ''),
        'authorization' => array('Authorization', ''),
        'cache-control' => array('Cache-Control', 'max-age=0'),
        'connection' => array('Connection', 'keep-alive'),
        'cookie' => array('Cookie', ''),
        'content-length' => array('Content-Length', ''),
        'content-md5' => array('Content-MD5', ''),
        'content-type' => array('Content-Type', ''),
        'date' => array('Date', ''),
        'expect' => array('Expect', ''),
        'from' => array('From', ''),
        'host' => array('Host', ''),
        'if-match' => array('If-Match', ''),
        'if-modified-since' => array('If-Modified-Since', ''),
        'if-none-match' => array('If-None-Match', ''),
        'if-range' => array('If-Range', ''),
        'if-unmodified-since' => array('If-Unmodified-Since', ''),
        'max-forwards' => array('Max-Forwards', ''),
        'origin' => array('Origin', ''),
        'pragma' => array('Pragma', ''),
        'proxy-authorization' => array('Proxy-Authorization', ''),
        'range' => array('Range', ''),
        'referer' => array('Referer', ''),
        'te' => array('TE', ''),
        'user-agent' => array('User-Agent', 'Mozilla/5.0 (Windows NT 6.1; rv:41.0) Gecko/20100101 Firefox/41.0'),
        'upgrade' => array('Upgrade', ''),
        'via' => array('Via', ''),
        'warning' => array('Warning', ''),
        'x-requested-with' => array('X-Requested-With', ''),
        'dnt' => array('DNT', ''),
        'x-forwarded-for' => array('X-Forwarded-For', ''),
        'x-forwarded-host' => array('X-Forwarded-Host', ''),
        'x-forwarded-proto' => array('X-Forwarded-Proto', ''),
        'x-http-method-override' => array('X-Http-Method-Override', ''),
        'x-wap-profile' => array('X-Wap-Profile', ''),
        'proxy-connection' => array('Proxy-Connection', ''),
        'x-csrf-token' => array('X-Csrf-Token', ''),
    );

    protected $accept;
    protected $acceptCharset;
    protected $acceptEncoding;
    protected $acceptLanguage;
    protected $acceptDatetime;
    protected $authorization;
    protected $cacheControl;
    protected $connection;
    protected $cookie;
    protected $contentLength;
    protected $contentMd5;
    protected $contentType;
    protected $date;
    protected $expect;
    protected $from;
    protected $host;
    protected $ifMatch;
    protected $ifModifiedSince;
    protected $ifNoneMatch;
    protected $ifRange;
    protected $ifUnmodifiedSince;
    protected $maxForwards;
    protected $origin;
    protected $pragma;
    protected $proxyAuthorization;
    protected $range;
    protected $referer;
    protected $te;
    protected $userAgent;
    protected $upgrade;
    protected $via;
    protected $warning;
    protected $xRequestedWith;
    protected $dnt;
    protected $xForwardedFor;
    protected $xForwardedHost;
    protected $xForwardedProto;
    protected $xHttpMethodOverride;
    protected $xWapProfile;
    protected $proxyConnection;
    protected $xCsrfToken;

    public function getAccept()
    {
        return $this->headers['accept'][1];
    }

    public function getAcceptCharset()
    {
        return $this->headers['accept-charset'][1];
    }

    public function getAcceptEncoding()
    {
        return $this->headers['accept-encoding'][1];
    }

    public function getAcceptLanguage()
    {
        return $this->headers['accept-language'][1];
    }

    public function getAcceptDatetime()
    {
        return $this->headers['accept-datetime'][1];
    }

    public function getAuthorization()
    {
        return $this->headers['authorization'][1];
    }

    public function getCacheControl()
    {
        return $this->headers['cache-control'][1];
    }

    public function getConnection()
    {
        return $this->headers['connection'][1];
    }

    public function getCookie()
    {
        return $this->headers['cookie'][1];
    }

    public function getContentLength()
    {
        return $this->headers['content-length'][1];
    }

    public function getContentMd5()
    {
        return $this->headers['content-md5'][1];
    }

    public function getContentType()
    {
        return $this->headers['content-type'][1];
    }

    public function getDate()
    {
        return $this->headers['date'][1];
    }

    public function getExpect()
    {
        return $this->headers['expect'][1];
    }

    public function getFrom()
    {
        return $this->headers['from'][1];
    }

    public function getHost()
    {
        return $this->headers['host'][1];
    }

    public function getIfMatch()
    {
        return $this->headers['if-match'][1];
    }

    public function getIfModifiedSince()
    {
        return $this->headers['if-modified-since'][1];
    }

    public function getIfNoneMatch()
    {
        return $this->headers['if-none-match'][1];
    }

    public function getIfRange()
    {
        return $this->headers['if-range'][1];
    }

    public function getIfUnmodifiedSince()
    {
        return $this->headers['if-unmodified-since'][1];
    }

    public function getMaxForwards()
    {
        return $this->headers['max-forwards'][1];
    }

    public function getOrigin()
    {
        return $this->headers['origin'][1];
    }

    public function getPragma()
    {
        return $this->headers['pragma'][1];
    }

    public function getProxyAuthorization()
    {
        return $this->headers['proxy-authorization'][1];
    }

    public function getRange()
    {
        return $this->headers['range'][1];
    }

    public function getReferer()
    {
        return $this->headers['referer'][1];
    }

    public function getTe()
    {
        return $this->headers['te'][1];
    }

    public function getUserAgent()
    {
        return $this->headers['user-agent'][1];
    }

    public function getUpgrade()
    {
        return $this->headers['upgrade'][1];
    }

    public function getVia()
    {
        return $this->headers['via'][1];
    }

    public function getWarning()
    {
        return $this->headers['warning'][1];
    }

    public function getXRequestedWith()
    {
        return $this->headers['x-requested-with'][1];
    }

    public function getDnt()
    {
        return $this->headers['dnt'][1];
    }

    public function getXForwardedFor()
    {
        return $this->headers['x-forwarded-for'][1];
    }

    public function getXForwardedHost()
    {
        return $this->headers['x-forwarded-host'][1];
    }

    public function getXForwardedProto()
    {
        return $this->headers['x-forwarded-proto'][1];
    }

    public function getXHttpMethodOverride()
    {
        return $this->headers['x-http-method-override'][1];
    }

    public function getXWapProfile()
    {
        return $this->headers['x-wap-profile'][1];
    }

    public function getProxyConnection()
    {
        return $this->headers['proxy-connection'][1];
    }

    public function getXCsrfToken()
    {
        return $this->headers['x-csrf-token'][1];
    }

    public function setAccept($accept)
    {
        $this->headers['accept'][1] = $accept;
        return $this;
    }

    public function setAcceptCharset($acceptCharset)
    {
        $this->headers['accept-charset'][1] = $acceptCharset;
        return $this;
    }

    public function setAcceptEncoding($acceptEncoding)
    {
        $this->headers['accept-encoding'][1] = $acceptEncoding;
        return $this;
    }

    public function setAcceptLanguage($acceptLanguage)
    {
        $this->headers['accept-language'][1] = $acceptLanguage;
        return $this;
    }

    public function setAcceptDatetime($acceptDatetime)
    {
        $this->headers['accept-datetime'][1] = $acceptDatetime;
        return $this;
    }

    public function setAuthorization($authorization)
    {
        $this->headers['authorization'][1] = $authorization;
        return $this;
    }

    public function setCacheControl($cacheControl)
    {
        $this->headers['cache-control'][1] = $cacheControl;
        return $this;
    }

    public function setConnection($connection)
    {
        $this->headers['connection'][1] = $connection;
        return $this;
    }

    public function setCookie($cookie)
    {
        $this->headers['cookie'][1] = $cookie;
        return $this;
    }

    public function setContentLength($contentLength)
    {
        $this->headers['content-length'][1] = $contentLength;
        return $this;
    }

    public function setContentMd5($contentMd5)
    {
        $this->headers['content-md5'][1] = $contentMd5;
        return $this;
    }

    public function setContentType($contentType)
    {
        $this->headers['content-type'][1] = $contentType;
        return $this;
    }

    public function setDate($date)
    {
        $this->headers['date'][1] = $date;
        return $this;
    }

    public function setExpect($expect)
    {
        $this->headers['expect'][1] = $expect;
        return $this;
    }

    public function setFrom($from)
    {
        $this->headers['from'][1] = $from;
        return $this;
    }

    public function setHost($host)
    {
        $this->headers['host'][1] = $host;
        return $this;
    }

    public function setIfMatch($ifMatch)
    {
        $this->headers['if-match'][1] = $ifMatch;
        return $this;
    }

    public function setIfModifiedSince($ifModifiedSince)
    {
        $this->headers['if-modified-since'][1] = $ifModifiedSince;
        return $this;
    }

    public function setIfNoneMatch($ifNoneMatch)
    {
        $this->headers['if-none-match'][1] = $ifNoneMatch;
        return $this;
    }

    public function setIfRange($ifRange)
    {
        $this->headers['if-range'][1] = $ifRange;
        return $this;
    }

    public function setIfUnmodifiedSince($ifUnmodifiedSince)
    {
        $this->headers['if-unmodified-since'][1] = $ifUnmodifiedSince;
        return $this;
    }

    public function setMaxForwards($maxForwards)
    {
        $this->headers['max-forwards'][1] = $maxForwards;
        return $this;
    }

    public function setOrigin($origin)
    {
        $this->headers['origin'][1] = $origin;
        return $this;
    }

    public function setPragma($pragma)
    {
        $this->headers['pragma'][1] = $pragma;
        return $this;
    }

    public function setProxyAuthorization($proxyAuthorization)
    {
        $this->headers['proxy-authorization'][1] = $proxyAuthorization;
        return $this;
    }

    public function setRange($range)
    {
        $this->headers['range'][1] = $range;
        return $this;
    }

    public function setReferer($referer)
    {
        $this->headers['referer'][1] = $referer;
        return $this;
    }

    public function setTe($te)
    {
        $this->headers['te'][1] = $te;
        return $this;
    }

    public function setUserAgent($userAgent)
    {
        $this->headers['user-agent'][1] = $userAgent;
        return $this;
    }

    public function setUpgrade($upgrade)
    {
        $this->headers['upgrade'][1] = $upgrade;
        return $this;
    }

    public function setVia($via)
    {
        $this->headers['via'][1] = $via;
        return $this;
    }

    public function setWarning($warning)
    {
        $this->headers['warning'][1] = $warning;
        return $this;
    }

    public function setXRequestedWith($xRequestedWith)
    {
        $this->headers['x-requested-with'][1] = $xRequestedWith;
        return $this;
    }

    public function setDnt($dnt)
    {
        $this->headers['dnt'][1] = $dnt;
        return $this;
    }

    public function setXForwardedFor($xForwardedFor)
    {
        $this->headers['x-forwarded-for'][1] = $xForwardedFor;
        return $this;
    }

    public function setXForwardedHost($xForwardedHost)
    {
        $this->headers['x-forwarded-host'][1] = $xForwardedHost;
        return $this;
    }

    public function setXForwardedProto($xForwardedProto)
    {
        $this->headers['x-forwarded-proto'][1] = $xForwardedProto;
        return $this;
    }

    public function setXHttpMethodOverride($xHttpMethodOverride)
    {
        $this->headers['x-http-method-override'][1] = $xHttpMethodOverride;
        return $this;
    }

    public function setXWapProfile($xWapProfile)
    {
        $this->headers['x-wap-profile'][1] = $xWapProfile;
        return $this;
    }

    public function setProxyConnection($proxyConnection)
    {
        $this->headers['proxy-connection'][1] = $proxyConnection;
        return $this;
    }

    public function setXCsrfToken($xCsrfToken)
    {
        $this->headers['x-csrf-token'][1] = $xCsrfToken;
        return $this;
    }
}