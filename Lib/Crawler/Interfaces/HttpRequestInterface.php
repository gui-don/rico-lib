<?php

namespace Rico\Lib\Crawler\Interfaces;

/**
 * HttpRequest Interface
 */
interface HttpRequestInterface
{
    public function __construct($url);

    /**
     * Send a http request and get the response
     * @return HttpResponse
     * @throws DownloadException
     */
    public function send();

    public function getStatus();

    public function getUrl();

    public function getSockProxy();

    public function getHeaderAccept();

    public function getHeaderAcceptLanguage();

    public function getHeaderAcceptEncoding();

    public function getHeaderAcceptCharset();

    public function getHeaderCacheControl();

    public function getHeaderConnection();

    public function getHeaderCookie();

    public function getHeaderContentLength();

    public function getHeaderContentType();

    public function getHeaderHost();

    public function getHeaderIfMatch();

    public function getHeaderIfModifiedSince();

    public function getHeaderIfNoneMatch();

    public function getHeaderMaxForwards();

    public function getHeaderOrigin();

    public function getHeaderPragma();

    public function getHeaderReferer();

    public function getHeaderUserAgent();

    public function getHeaderVia();

    public function getHeaderDnt();

    public function getHeaderForwardedFor();

    public function getHeaderForwardedHost();

    public function getHeaderForwardedProto();

    public function getHeaderRequestedWith();

    public function getHeaderProxyConnection();

    public function setStatus($status);

    public function setUrl($url);

    public function setSockProxy($sockProxy);

    public function setHeaderAccept($headerAccept);

    public function setHeaderAcceptLanguage($headerAcceptLanguage);

    public function setHeaderAcceptEncoding($headerAcceptEncoding);

    public function setHeaderAcceptCharset($headerAcceptCharset);

    public function setHeaderCacheControl($headerCacheControl);

    public function setHeaderConnection($headerConnection);

    public function setHeaderCookie($headerCookie);

    public function setHeaderContentLength($headerContentLength);

    public function setHeaderContentType($headerContentType);

    public function setHeaderHost($headerHost);

    public function setHeaderIfMatch($headerIfMatch);

    public function setHeaderIfModifiedSince($headerIfModifiedSince);

    public function setHeaderIfNoneMatch($headerIfNoneMatch);

    public function setHeaderMaxForwards($headerMaxForwards);

    public function setHeaderOrigin($headerOrigin);

    public function setHeaderPragma($headerPragma);

    public function setHeaderReferer($headerReferer);

    public function setHeaderUserAgent($headerUserAgent);

    public function setHeaderVia($headerVia);

    public function setHeaderDnt($headerDnt);

    public function setHeaderForwardedFor($headerForwardedFor);

    public function setHeaderForwardedHost($headerForwardedHost);

    public function setHeaderForwardedProto($headerForwardedProto);

    public function setHeaderRequestedWith($headerRequestedWith);

    public function setHeaderProxyConnection($headerProxyConnection);
}
