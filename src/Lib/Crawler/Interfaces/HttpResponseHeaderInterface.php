<?php

namespace Rico\Lib\Crawler\Interfaces;

/**
 * HttpResponseHeader Interface
 */
interface HttpResponseHeaderInterface
{
    public function getAccessControlAllowOrigin();
    public function getAcceptPatch();
    public function getAcceptRanges();
    public function getAge();
    public function getAllow();
    public function getCacheControl();
    public function getConnection();
    public function getContentDisposition();
    public function getContentEncoding();
    public function getContentLanguage();
    public function getContentLength();
    public function getContentLocation();
    public function getContentMd5();
    public function getContentRange();
    public function getContentType();
    public function getDate();
    public function getETag();
    public function getExpires();
    public function getLastModified();
    public function getLink();
    public function getLocation();
    public function getP3P();
    public function getPragma();
    public function getProxyAuthenticate();
    public function getPublicKeyPins();
    public function getRefresh();
    public function getRetryAfter();
    public function getServer();
    public function getSetCookie();
    public function getStatus();
    public function getStrictTransportSecurity();
    public function getTrailer();
    public function getTransferEncoding();
    public function getUpdrade();
    public function getVary();
    public function getVia();
    public function getWarning();
    public function getWWWAutenticate();
    public function getXFrameOptions();
    public function getXXSSProtection();
    public function getContentSecurityPolicy();
    public function getContentTypeOptions();
    public function getXPoweredBy();
    public function getXUACompatible();
    public function getContentDuration();

    public function setAccessControlAllowOrigin($value);
    public function setAcceptPatch($value);
    public function setAcceptRanges($value);
    public function setAge($value);
    public function setAllow($value);
    public function setCacheControl($value);
    public function setConnection($value);
    public function setContentDisposition($value);
    public function setContentEncoding($value);
    public function setContentLanguage($value);
    public function setContentLength($value);
    public function setContentLocation($value);
    public function setContentMd5($value);
    public function setContentRange($value);
    public function setContentType($value);
    public function setDate($value);
    public function setETag($value);
    public function setExpires($value);
    public function setLastModified($value);
    public function setLink($value);
    public function setLocation($value);
    public function setP3P($value);
    public function setPragma($value);
    public function setProxyAuthenticate($value);
    public function setPublicKeyPins($value);
    public function setRefresh($value);
    public function setRetryAfter($value);
    public function setServer($value);
    public function setSetCookie($value);
    public function setStatus($value);
    public function setStrictTransportSecurity($value);
    public function setTrailer($value);
    public function setTransferEncoding($value);
    public function setUpdrade($value);
    public function setVary($value);
    public function setVia($value);
    public function setWarning($value);
    public function setWWWAutenticate($value);
    public function setXFrameOptions($value);
    public function setXXSSProtection($value);
    public function setContentSecurityPolicy($value);
    public function setContentTypeOptions($value);
    public function setXPoweredBy($value);
    public function setXUACompatible($value);
    public function setContentDuration($value);
}
