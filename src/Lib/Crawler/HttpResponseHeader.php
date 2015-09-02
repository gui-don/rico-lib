<?php

namespace Rico\Lib\Crawler;

use Rico\Lib\Crawler\HttpHeader;
use Rico\Lib\Crawler\Interfaces\HttpResponseHeaderInterface;

class HttpResponseHeader extends HttpHeader implements HttpResponseHeaderInterface
{
    protected $headers = array(
        'access-control-allow-origin' => array('Access-Control-Allow-Origin', ''),
        'accept-patch' => array('Accept-Patch', ''),
        'accept-ranges' => array('Accept-Ranges', ''),
        'age' => array('Age', ''),
        'allow' => array('Allow', ''),
        'cache-control' => array('Cache-Control', ''),
        'connection' => array( 'Connection', ''),
        'content-disposition' => array('Content-Disposition', ''),
        'content-encoding' => array('Content-Encoding', ''),
        'content-language' => array('Content-Language', ''),
        'content-length' => array('Content-Length', ''),
        'content-location' => array('Content-Location', ''),
        'content-md5' => array('Content-MD5', ''),
        'content-range' => array('Content-Range', ''),
        'content-type' => array('Content-Type', ''),
        'date' => array('Date', ''),
        'etag' => array('ETag', ''),
        'expires' => array('Expires', ''),
        'last-modified' => array('Last-Modified', ''),
        'link' => array('Link', ''),
        'location' => array('Location', ''),
        'p3p' => array('P3P', ''),
        'pragma' => array('Pragma', ''),
        'proxy-authenticate' => array('Proxy-Authenticate', ''),
        'public-key-pins' => array('Public-Key-Pins', ''),
        'refresh' => array('Refresh', ''),
        'retry-after' => array('Retry-After', ''),
        'server' => array('Server', ''),
        'set-cookie' => array('Set-Cookie', ''),
        'status' => array('Status', ''),
        'strict-transport-security' => array('Strict-Transport-Security', ''),
        'trailer' => array('Trailer', ''),
        'transfer-encoding' => array('Transfer-Encoding', ''),
        'upgrade' => array('Upgrade', ''),
        'vary' => array('Vary', ''),
        'via' => array('Via', ''),
        'warning' => array('Warning', ''),
        'www-authenticate' => array('WWW-Authenticate', ''),
        'x-frame-options' => array('X-Frame-Options', ''),
        'x-xss-protection' => array('X-XSS-Protection', ''),
        'content-security-policy' => array('Content-Security-Policy', ''),
        'x-content-type-options' => array('X-Content-Type-Options', ''),
        'x-powered-by' => array('X-Powered-By', ''),
        'x-ua-compatible' => array('X-UA-Compatible', ''),
        'x-content-duration' => array('X-Content-Duration', ''),
    );

    public function getAccessControlAllowOrigin()
    {
        return $this->headers['access-control-allow-origin'][1];
    }

    public function getAcceptPatch()
    {
        return $this->headers['accept-patch'][1];
    }

    public function getAcceptRanges()
    {
        return $this->headers['accept-ranges'][1];
    }

    public function getAge()
    {
        return $this->headers['age'][1];
    }

    public function getAllow()
    {
        return $this->headers['allow'][1];
    }

    public function getCacheControl()
    {
        return $this->headers['cache-control'][1];
    }

    public function getConnection()
    {
        return $this->headers['connection'][1];
    }

    public function getContentDisposition()
    {
        return $this->headers['content-disposition'][1];
    }

    public function getContentEncoding()
    {
        return $this->headers['content-encoding'][1];
    }

    public function getContentLanguage()
    {
        return $this->headers['content-language'][1];
    }

    public function getContentLength()
    {
        return $this->headers['content-length'][1];
    }

    public function getContentLocation()
    {
        return $this->headers['location'][1];
    }

    public function getContentMd5()
    {
        return $this->headers['content-md5'][1];
    }

    public function getContentRange()
    {
        return $this->headers['content-range'][1];
    }

    public function getContentType()
    {
        return $this->headers['content-type'][1];
    }

    public function getDate()
    {
        return $this->headers['date'][1];
    }

    public function getETag()
    {
        return $this->headers['etag'][1];
    }

    public function getExpires()
    {
        return $this->headers['expires'][1];
    }

    public function getLastModified()
    {
        return $this->headers['last-modified'][1];
    }

    public function getLink()
    {
        return $this->headers['link'][1];
    }

    public function getLocation()
    {
        return $this->headers['location'][1];
    }

    public function getP3p()
    {
        return $this->headers['p3p'][1];
    }

    public function getPragma()
    {
        return $this->headers['pragma'][1];
    }

    public function getProxyAuthenticate()
    {
        return $this->headers['proxy-authenticate'][1];
    }

    public function getPublicKeyPins()
    {
        return $this->headers['public-key-pins'][1];
    }

    public function getRefresh()
    {
        return $this->headers['refresh'][1];
    }

    public function getRetryAfter()
    {
        return $this->headers['retry-after'][1];
    }

    public function getServer()
    {
        return $this->headers['server'][1];
    }

    public function getSetCookie()
    {
        return $this->headers['set-cookie'][1];
    }

    public function getStatus()
    {
        return $this->headers['status'][1];
    }

    public function getStrictTransportSecurity()
    {
        return $this->headers['strict-transport-security'][1];
    }

    public function getTrailer()
    {
        return $this->headers['trailer'][1];
    }

    public function getTransferEncoding()
    {
        return $this->headers['transfer-encoding'][1];
    }

    public function getUpdrade()
    {
        return $this->headers['upgrade'][1];
    }

    public function getVary()
    {
        return $this->headers['vary'][1];
    }

    public function getVia()
    {
        return $this->headers['via'][1];
    }

    public function getWarning()
    {
        return $this->headers['warning'][1];
    }

    public function getWWWAutenticate()
    {
        return $this->headers['www-authenticate'][1];
    }

    public function getXFrameOptions()
    {
        return $this->headers['x-frame-options'][1];
    }

    public function getXXSSProtection()
    {
        return $this->headers['x-xss-protection'][1];
    }

    public function getContentSecurityPolicy()
    {
        return $this->headers['content-security-policy'][1];
    }

    public function getContentTypeOptions()
    {
        return $this->headers['content-type-options'][1];
    }

    public function getXPoweredBy()
    {
        return $this->headers['x-powered-by'][1];
    }

    public function getXUACompatible()
    {
        return $this->headers['x-ua-compatible'][1];
    }

    public function getContentDuration()
    {
        return $this->headers['x-content-duration'][1];
    }

    public function setAccessControlAllowOrigin($accessControlAllowOrigin)
    {
        $this->headers['access-control-allow-origin'][1] = $accessControlAllowOrigin;
        return $this;
    }

    public function setAcceptPatch($acceptPatch)
    {
        $this->headers['accept-path'][1] = $acceptPatch;
        return $this;
    }

    public function setAcceptRanges($acceptRanges)
    {
        $this->headers['accept-ranges'][1] = $acceptRanges;
        return $this;
    }

    public function setAge($age)
    {
        $this->headers['age'][1] = $age;
        return $this;
    }

    public function setAllow($allow)
    {
        $this->headers['allow'][1] = $allow;
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

    public function setContentDisposition($contentDisposition)
    {
        $this->headers['content-disposition'][1] = $contentDisposition;
        return $this;
    }

    public function setContentEncoding($contentEncoding)
    {
        $this->headers['content-encoding'][1] = $contentEncoding;
        return $this;
    }

    public function setContentLanguage($contentLanguage)
    {
        $this->headers['content-language'][1] = $contentLanguage;
        return $this;
    }

    public function setContentLength($contentLength)
    {
        $this->headers['content-length'][1] = $contentLength;
        return $this;
    }

    public function setContentLocation($contentLocation)
    {
        $this->headers['location'][1] = $contentLocation;
        return $this;
    }

    public function setContentMd5($contentMd5)
    {
        $this->headers['content-md5'][1] = $contentMd5;
        return $this;
    }

    public function setContentRange($contentRange)
    {
        $this->headers['content-range'][1] = $contentRange;
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

    public function setETag($eTag)
    {
        $this->headers['etag'][1] = $eTag;
        return $this;
    }

    public function setExpires($expires)
    {
        $this->headers['expires'][1] = $expires;
        return $this;
    }

    public function setLastModified($lastModified)
    {
        $this->headers['last-modified'][1] = $lastModified;
        return $this;
    }

    public function setLink($link)
    {
        $this->headers['link'][1] = $link;
        return $this;
    }

    public function setLocation($location)
    {
        $this->headers['location'][1] = $location;
        return $this;
    }

    public function setP3p($p3p)
    {
        $this->headers['p3p'][1] = $p3p;
        return $this;
    }

    public function setPragma($pragma)
    {
        $this->headers['pragma'][1] = $pragma;
        return $this;
    }

    public function setProxyAuthenticate($proxyAuthetiate)
    {
        $this->headers['proxy-authenticate'][1] = $proxyAuthetiate;
        return $this;
    }

    public function setPublicKeyPins($publicKeyPins)
    {
        $this->headers['public-key-pins'][1] = $publicKeyPins;
        return $this;
    }

    public function setRefresh($refresh)
    {
        $this->headers['refresh'][1] = $refresh;
        return $this;
    }

    public function setRetryAfter($retryAfter)
    {
        $this->headers['retry-after'][1] = $retryAfter;
        return $this;
    }

    public function setServer($server)
    {
        $this->headers['server'][1] = $server;
        return $this;
    }

    public function setSetCookie($setCookie)
    {
        $this->headers['set-cookie'][1] = $setCookie;
        return $this;
    }

    public function setStatus($status)
    {
        $this->headers['status'][1] = $status;
        return $this;
    }

    public function setStrictTransportSecurity($strictTransportSecurity)
    {
        $this->headers['strict-transport-security'][1] = $strictTransportSecurity;
        return $this;
    }

    public function setTrailer($trailer)
    {
        $this->headers['trailer'][1] = $trailer;
        return $this;
    }

    public function setTransferEncoding($transferEncoding)
    {
        $this->headers['transfer-encoding'][1] = $transferEncoding;
        return $this;
    }

    public function setUpdrade($updrade)
    {
        $this->headers['upgrade'][1] = $updrade;
        return $this;
    }

    public function setVary($vary)
    {
        $this->headers['vary'][1] = $vary;
        return $this;
    }

    public function setVia($via)
    {
        $this->headers['vary'][1] = $via;
        return $this;
    }

    public function setWarning($warning)
    {
        $this->headers['warning'][1] = $warning;
        return $this;
    }

    public function setWWWAutenticate($wWWAutenticate)
    {
        $this->headers['www-authenticate'][1] = $wWWAutenticate;
        return $this;
    }

    public function setXFrameOptions($xFrameOptions)
    {
        $this->headers['x-frame-options'][1] = $xFrameOptions;
        return $this;
    }

    public function setXXSSProtection($xXSSProtection)
    {
        $this->headers['x-xss-protection'][1] = $xXSSProtection;
        return $this;
    }

    public function setContentSecurityPolicy($contentSecurityPolicy)
    {
        $this->headers['content-security-policy'][1] = $contentSecurityPolicy;
        return $this;
    }

    public function setContentTypeOptions($contentTypeOptions)
    {
        $this->headers['content-type-options'][1] = $contentTypeOptions;
        return $this;
    }

    public function setXPoweredBy($xPoweredBy)
    {
        $this->headers['x-powered-by'][1] = $xPoweredBy;
        return $this;
    }

    public function setXUACompatible($xUACompatible)
    {
        $this->headers['x-ua-compatible'][1] = $xUACompatible;
        return $this;
    }

    public function setContentDuration($contentDuration)
    {
        $this->headers['x-content-duration'][1] = $contentDuration;
        return $this;
    }
}