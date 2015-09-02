<?php

namespace Rico\Lib\Crawler\Interfaces;

/**
 * HttpRequestHeader Interface
 */
interface HttpRequestHeaderInterface
{
    public function getAccept();
    public function getAcceptCharset();
    public function getAcceptEncoding();
    public function getAcceptLanguage();
    public function getAcceptDatetime();
    public function getAuthorization();
    public function getCacheControl();
    public function getConnection();
    public function getCookie();
    public function getContentLength();
    public function getContentMd5();
    public function getContentType();
    public function getDate();
    public function getExpect();
    public function getFrom();
    public function getHost();
    public function getIfMatch();
    public function getIfModifiedSince();
    public function getIfNoneMatch();
    public function getIfRange();
    public function getIfUnmodifiedSince();
    public function getMaxForwards();
    public function getOrigin();
    public function getPragma();
    public function getProxyAuthorization();
    public function getRange();
    public function getReferer();
    public function getTe();
    public function getUserAgent();
    public function getUpgrade();
    public function getVia();
    public function getWarning();
    public function getXRequestedWith();
    public function getDnt();
    public function getXForwardedFor();
    public function getXForwardedHost();
    public function getXForwardedProto();
    public function getXHttpMethodOverride();
    public function getXWapProfile();
    public function getProxyConnection();
    public function getXCsrfToken();

    public function setAccept($accept);
    public function setAcceptCharset($acceptCharset);
    public function setAcceptEncoding($acceptEncoding);
    public function setAcceptLanguage($acceptLanguage);
    public function setAcceptDatetime($acceptDatetime);
    public function setAuthorization($authorization);
    public function setCacheControl($cacheControl);
    public function setConnection($connection);
    public function setCookie($cookie);
    public function setContentLength($contentLength);
    public function setContentType($contentType);
    public function setDate($date);
    public function setExpect($expect);
    public function setFrom($from);
    public function setHost($host);
    public function setIfMatch($ifMatch);
    public function setIfModifiedSince($ifModifiedSince);
    public function setIfNoneMatch($ifNoneMatch);
    public function setIfRange($ifRange);
    public function setIfUnmodifiedSince($ifUnmodifiedSince);
    public function setMaxForwards($maxForwards);
    public function setOrigin($origin);
    public function setPragma($pragma);
    public function setProxyAuthorization($proxyAuthorization);
    public function setRange($range);
    public function setReferer($referer);
    public function setTE($te);
    public function setUserAgent($userAgent);
    public function setUpgrade($upgrade);
    public function setVia($via);
    public function setWarning($warning);
    public function setXRequestedWith($xRequestedWith);
    public function setDnt($dnt);
    public function setXForwardedFor($xForwardedFor);
    public function setXForwardedHost($xForwardedHost);
    public function setXForwardedProto($xForwardedProto);
    public function setXHttpMethodOverride($xHttpMethodOverride);
    public function setXWapProfile($xWapProfile);
    public function setProxyConnection($proxyConnection);
    public function setXCsrfToken($xCsrfToken);
}
