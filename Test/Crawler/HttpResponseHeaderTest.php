<?php

namespace Rico\Test\HttpResponseHeaderTest;

use Rico\Lib\Crawler\HttpResponseHeader;

class HttpResponseHeaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers HttpResponseHeader::__construct()
     */
    public function testConstruct()
    {
        $headers = 'Cache-Control: max-age=2592000
content-encoding: gzip
content-type: image/x-icon
DATE: Tue, 01 Sep 2015 19:50:29 GMT
Expires: Thu, 01 Oct 2015 19:50:29 GMT
Server : nginx
Strict-Transport-Security: max-age=0, max-age=0
location: http://www.w3.org/pub/WWW/People.html
X-Firefox-Spdy: 3.1
x-duckduckgo-locale: en_US, en_US';

        $httpResponseHeader = new HttpResponseHeader($headers);

        $this->assertSame('max-age=2592000', $httpResponseHeader->getCacheControl());
        $this->assertSame('gzip', $httpResponseHeader->getContentEncoding());
        $this->assertSame('image/x-icon', $httpResponseHeader->getContentType());
        $this->assertSame('Tue, 01 Sep 2015 19:50:29 GMT', $httpResponseHeader->getDate());
        $this->assertSame('Thu, 01 Oct 2015 19:50:29 GMT', $httpResponseHeader->getExpires());
        $this->assertSame('nginx', $httpResponseHeader->getServer());
        $this->assertSame('max-age=0, max-age=0', $httpResponseHeader->getStrictTransportSecurity());
        $this->assertSame('http://www.w3.org/pub/WWW/People.html', $httpResponseHeader->getLocation());
    }

    /**
     * @covers HttpResponseHeader::hydrate()
     */
    public function testHydrate()
    {
        $headers = 'Access-Control-Allow-Origin: *
Accept-Patch: text/example;charset=utf-8
Accept-Ranges: bytes
Age: 12
Allow: GET, HEAD
Cache-Control: max-age=0, no-cache, no-store, must-revalidate, post-check=0, pre-check=0
Connection: Keep-Alive
Content-MD5: Q2hlY2sgSW50ZWdyaXR5IQ==
Content-Encoding: gzip
Content-Length: 5607
Content-Type: text/html; charset=utf-8
Date: Tue, 01 Sep 2015 18:40:55 GMT
ETag: "737060cd8c284d8af7ad3082f209582d"
Expires: Thu, 01 Dec 1994 16:00:00 GMT
Last-Modified: Tue, 15 Nov 1994 12:45:26 GMT
Link: </feed>; rel="alternate"
Refresh: 5; url=http://www.w3.org/pub/WWW/People.html
Retry-After: 120
Keep-Alive: timeout=5, max=100
Pragma: no-cache
Server: Apache
Status: 200 OK
Set-Cookie: REGEX101=s6qrt4tou55uqgjkhfa3nhoca0; path=/
Strict-Transport-Security: max-age=16070400;
Trailer: Max-Forwards
Transfer-Encoding: chunked
Upgrade: HTTP/2.0, SHTTP/1.3, IRC/6.9, RTA/x11
Vary: Accept-Encoding
Via: 1.0 fred, 1.1 example.com (Apache/1.1)
Warning: 199 Miscellaneous warning
X-Frame-Options: deny
Content-Security-Policy: default-src \'self\'
X-UA-Compatible: IE=edge
X-Mod-Pagespeed: 1.9.32.4-7251
x-Powered-By: PHP/5.4.41-0+deb7u1
X-Content-Duration: 42.666';

        $httpResponseHeader = new HttpResponseHeader();
        $httpResponseHeader->hydrate($headers);

        $this->assertSame('*', $httpResponseHeader->getAccessControlAllowOrigin());
        $this->assertSame('text/example;charset=utf-8', $httpResponseHeader->getAcceptPatch());
        $this->assertSame('bytes', $httpResponseHeader->getAcceptRanges());
        $this->assertSame('12', $httpResponseHeader->getAge());
        $this->assertSame('GET, HEAD', $httpResponseHeader->getAllow());
        $this->assertSame('max-age=0, no-cache, no-store, must-revalidate, post-check=0, pre-check=0', $httpResponseHeader->getCacheControl());
        $this->assertSame('Keep-Alive', $httpResponseHeader->getConnection());
        $this->assertSame('Q2hlY2sgSW50ZWdyaXR5IQ==', $httpResponseHeader->getContentMd5());
        $this->assertSame('5607', $httpResponseHeader->getContentLength());
        $this->assertSame('text/html; charset=utf-8', $httpResponseHeader->getContentType());
        $this->assertSame('Tue, 01 Sep 2015 18:40:55 GMT', $httpResponseHeader->getDate());
        $this->assertSame('"737060cd8c284d8af7ad3082f209582d"', $httpResponseHeader->getETag());
        $this->assertSame('Thu, 01 Dec 1994 16:00:00 GMT', $httpResponseHeader->getExpires());
        $this->assertSame('Tue, 15 Nov 1994 12:45:26 GMT', $httpResponseHeader->getLastModified());
        $this->assertSame('</feed>; rel="alternate"', $httpResponseHeader->getLink());
        $this->assertSame('5; url=http://www.w3.org/pub/WWW/People.html', $httpResponseHeader->getRefresh());
        $this->assertSame('120', $httpResponseHeader->getRetryAfter());
        $this->assertSame('no-cache', $httpResponseHeader->getPragma());
        $this->assertSame('Apache', $httpResponseHeader->getServer());
        $this->assertSame('200 OK', $httpResponseHeader->getStatus());
        $this->assertSame('REGEX101=s6qrt4tou55uqgjkhfa3nhoca0; path=/', $httpResponseHeader->getSetCookie());
        $this->assertSame('max-age=16070400;', $httpResponseHeader->getStrictTransportSecurity());
        $this->assertSame('Max-Forwards', $httpResponseHeader->getTrailer());
        $this->assertSame('chunked', $httpResponseHeader->getTransferEncoding());
        $this->assertSame('HTTP/2.0, SHTTP/1.3, IRC/6.9, RTA/x11', $httpResponseHeader->getUpdrade());
        $this->assertSame('Accept-Encoding', $httpResponseHeader->getVary());
        $this->assertSame('1.0 fred, 1.1 example.com (Apache/1.1)', $httpResponseHeader->getVia());
        $this->assertSame('199 Miscellaneous warning', $httpResponseHeader->getWarning());
        $this->assertSame('deny', $httpResponseHeader->getXFrameOptions());
        $this->assertSame('default-src \'self\'', $httpResponseHeader->getContentSecurityPolicy());
        $this->assertSame('IE=edge', $httpResponseHeader->getXUACompatible());
        $this->assertSame('PHP/5.4.41-0+deb7u1', $httpResponseHeader->getXPoweredBy());
        $this->assertSame('42.666', $httpResponseHeader->getContentDuration());

        $this->assertSame('X-Mod-Pagespeed', $httpResponseHeader->getHeaders()['x-mod-pagespeed'][0]);
        $this->assertSame('1.9.32.4-7251', $httpResponseHeader->getHeaders()['x-mod-pagespeed'][1]);
    }

    /**
     * @covers HttpResponseHeader::convert()
     */
    public function testConvert()
    {
        $headers = 'Cache-Control: no-cache
Content-Encoding: gzip
Content-Type: text/html; charset=utf-8
Date: Tue, 01 Sep 2015 21:29:22 GMT
Public-Key-Pins: max-age=300; pin-sha256="WoiWRyIOVNa9ihaBciRSC7XHjliYS9VwUGOIud4PB18="; pin-sha256="JbQbUG5JMJUoI6brnx0x3vZF6jilxsapbXGVfjhN8Fg="; includeSubDomains
Server: GitHub.com
Set-Cookie: _gh_sess=eyJzZXNzaW9uX2lkIjoiYWM1NWQzMjI3MGMyODQ5MDgxZjI1YmI5ZjI5YjczMmQiLCJzcHlfcmVwbyI6Imp2ZW5hbnQvcGFzc2ZmIiwic3B5X3JlcG9fYXQiOjE0NDExNDI5NjIsIl9jc3JmX3Rva2VuIjoidTVnWVdOZW1YODhaME5pWnd1WDE5RjZFaldpWHNGbXdET2Fkam9yNEI3Yz0ifQ%3D%3D--2f4a6b5e28d4f604c5dd084adea7f96e025771f8; path=/; secure; HttpOnly
Status: 200 OK
Strict-Transport-Security: max-age=31536000; includeSubdomains; preload
Transfer-Encoding: chunked
Vary: X-PJAX, Accept-Encoding
X-Content-Type-Options: nosniff
X-Frame-Options: deny
X-GitHub-Request-Id: 6DBE001B:16D5:26887B1:55E618B2
X-Request-Id: e2a0e97ff79eec69b6069d4fa94360f5
X-Runtime: 0.040326
X-Served-By: 7d2a2d05162492046d9960cdbc326796
X-UA-Compatible: IE=Edge,chrome=1
X-XSS-Protection: 1; mode=block';

        $expected = array(
            'Cache-Control' => 'no-cache',
            'Content-Encoding' => 'gzip',
            'Content-Type' => 'text/html; charset=utf-8',
            'Date' => 'Tue, 01 Sep 2015 21:29:22 GMT',
            'Public-Key-Pins' => 'max-age=300; pin-sha256="WoiWRyIOVNa9ihaBciRSC7XHjliYS9VwUGOIud4PB18="; pin-sha256="JbQbUG5JMJUoI6brnx0x3vZF6jilxsapbXGVfjhN8Fg="; includeSubDomains',
            'Server' => 'GitHub.com',
            'Set-Cookie' => '_gh_sess=eyJzZXNzaW9uX2lkIjoiYWM1NWQzMjI3MGMyODQ5MDgxZjI1YmI5ZjI5YjczMmQiLCJzcHlfcmVwbyI6Imp2ZW5hbnQvcGFzc2ZmIiwic3B5X3JlcG9fYXQiOjE0NDExNDI5NjIsIl9jc3JmX3Rva2VuIjoidTVnWVdOZW1YODhaME5pWnd1WDE5RjZFaldpWHNGbXdET2Fkam9yNEI3Yz0ifQ%3D%3D--2f4a6b5e28d4f604c5dd084adea7f96e025771f8; path=/; secure; HttpOnly',
            'Status' => '200 OK',
            'Strict-Transport-Security' => 'max-age=31536000; includeSubdomains; preload',
            'Transfer-Encoding' => 'chunked',
            'Vary' => 'X-PJAX, Accept-Encoding',
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'deny',
            'X-GitHub-Request-Id' => '6DBE001B:16D5:26887B1:55E618B2',
            'X-Request-Id' => 'e2a0e97ff79eec69b6069d4fa94360f5',
            'X-Runtime' => '0.040326',
            'X-Served-By' => '7d2a2d05162492046d9960cdbc326796',
            'X-UA-Compatible' => 'IE=Edge,chrome=1',
            'X-XSS-Protection' => '1; mode=block'
        );

        $httpResponseHeader = new HttpResponseHeader($headers);

        $this->assertEquals($expected, $httpResponseHeader->convert());
    }
}
