<?php

namespace Rico\Test\HttpRequestHeaderTest;

use Rico\Lib\Crawler\HttpRequestHeader;

class HttpRequestHeaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers HttpRequestHeader::__construct()
     */
    public function testConstruct()
    {
        $headers = 'Host: github.com
User-Agent: Mozilla/5.0 (Windows NT 6.1; rv:41.0) Gecko/20100101 Firefox/41.0
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8
Accept-Encoding: gzip, deflate
DNT: 1
Cookie: logged_in=no; _gh_sess=ayJzZXNzaW9uX2lkIjoiYWM1NWQzMjI3MGMyODQ5MDgxZjI1YmI5ZjI5YjczMmQiLCJzcHlfcmVwbyI6Imp2ZW5hbnQvcGFzc2ZmIiwic3B5X3JlcG9fYXQiOjE0NDEwOTg5MzAsIl9jc3JmX3Rva2VuIjoidTVnWVdOZW1YODhaME5pWnd1WDE5RjZFaldpWHNGbXdET2Fkam9yNEI3Yz0ifQ%3D%3D--db867c7d77462b40ceb82cde299647177dfd1681; tz=Europe%2FBerlin; _ga=GA1.2.2106377444.1441098776; _octo=GH1.1.815123075.1441098776
Connection: keep-alive';

        $httpRequestHeader = new HttpRequestHeader($headers);

        $this->assertSame('github.com', $httpRequestHeader->getHost());
        $this->assertSame('Mozilla/5.0 (Windows NT 6.1; rv:41.0) Gecko/20100101 Firefox/41.0', $httpRequestHeader->getUserAgent());
        $this->assertSame('text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8', $httpRequestHeader->getAccept());
        $this->assertSame('gzip, deflate', $httpRequestHeader->getAcceptEncoding());
        $this->assertSame('1', $httpRequestHeader->getDNT());
        $this->assertSame('logged_in=no; _gh_sess=ayJzZXNzaW9uX2lkIjoiYWM1NWQzMjI3MGMyODQ5MDgxZjI1YmI5ZjI5YjczMmQiLCJzcHlfcmVwbyI6Imp2ZW5hbnQvcGFzc2ZmIiwic3B5X3JlcG9fYXQiOjE0NDEwOTg5MzAsIl9jc3JmX3Rva2VuIjoidTVnWVdOZW1YODhaME5pWnd1WDE5RjZFaldpWHNGbXdET2Fkam9yNEI3Yz0ifQ%3D%3D--db867c7d77462b40ceb82cde299647177dfd1681; tz=Europe%2FBerlin; _ga=GA1.2.2106377444.1441098776; _octo=GH1.1.815123075.1441098776', $httpRequestHeader->getCookie());
        $this->assertSame('keep-alive', $httpRequestHeader->getConnection());
    }

    /**
     * @covers HttpRequestHeader::hydrate()
     */
    public function testHydrate()
    {
        $headers = 'Host: www.gog.com
Connection: keep-alive
Cache-Control: max-age=0
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8
Upgrade-Insecure-Requests: 1
User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.157 Safari/537.36
DNT: 1
Expect: 100-continue
Date: Tue, 15 Nov 1994 08:12:31 GMT
Accept-Encoding: gzip, deflate, sdch
Accept-Charset: utf-8
Accept-Language: fr-FR,fr;q=0.8,en-US;q=0.6,en;q=0.4
Accept-Datetime: Thu, 31 May 2007 20:35:00 GMT
Content-Length: 3483
Content-MD5: Q2hlY2sgSW50ZWdyaXR5IQ==
From: user@example.com
if-Match: "737060cd8c284d8af7ad3082f209582d"
iX-Csrf-Token: i8XNjC4b8KVok4uw5RftR38Wgp2BFwqlf-None-Match: "737060cd8c284d8af7ad3082f209582d"
if-Range: "737060cd8c284d8af7ad3082f209582d"
if-Unmodified-Since: Sat, 29 Oct 1994 19:43:31 GMT
if-Modified-Since: Sat, 29 Oct 1994 19:43:31 GMT
Max-Forwards: 10
Pragma: no-cache
Range: bytes=500-999
TE: trailers, deflate
Upgrade: HTTP/2.0, SHTTP/1.3, IRC/6.9, RTA/x11
Via: 1.0 fred, 1.1 example.com (Apache/1.1)
WARNING: 199 Miscellaneous warning
x-Requested-With: XMLHttpRequest
x-Forwarded-For: 129.78.138.66, 129.78.64.103
x-Forwarded-Host: en.wikipedia.org
X-Forwarded-Proto: https
Front-End-Https: on
X-HTTP-Method-Override: GET
x-wap-profile: http://wap.samsungmobile.com/uaprof/SGH-I777.xml
X-Csrf-Token: i8XNjC4b8KVok4uw5RftR38Wgp2BFwql
Proxy-Connection: keep-alive
Referer: http://en.wikipedia.org/wiki/Main_Page
Origin: http://www.example-social-network.com
Cookie: gog_lc=FR_EUR_fr; cart_token=69a3f0ca4bd091e5; _ga=GA1.2.1789784146.1441146621; _gat=1';

        $httpRequestHeader = new HttpRequestHeader();
        $httpRequestHeader->hydrate($headers);

        $this->assertSame('www.gog.com', $httpRequestHeader->getHost());
        $this->assertSame('keep-alive', $httpRequestHeader->getConnection());
        $this->assertSame('max-age=0', $httpRequestHeader->getCacheControl());
        $this->assertSame('text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8', $httpRequestHeader->getAccept());
        $this->assertSame('Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.157 Safari/537.36', $httpRequestHeader->getUserAgent());
        $this->assertSame('1', $httpRequestHeader->getDnt());
        $this->assertSame('100-continue', $httpRequestHeader->getExpect());
        $this->assertSame('Tue, 15 Nov 1994 08:12:31 GMT', $httpRequestHeader->getDate());
        $this->assertSame('gzip, deflate, sdch', $httpRequestHeader->getAcceptEncoding());
        $this->assertSame('utf-8', $httpRequestHeader->getAcceptCharset());
        $this->assertSame('fr-FR,fr;q=0.8,en-US;q=0.6,en;q=0.4', $httpRequestHeader->getAcceptLanguage());
        $this->assertSame('Thu, 31 May 2007 20:35:00 GMT', $httpRequestHeader->getAcceptDatetime());
        $this->assertSame('3483', $httpRequestHeader->getContentLength());
        $this->assertSame('Q2hlY2sgSW50ZWdyaXR5IQ==', $httpRequestHeader->getContentMd5());
        $this->assertSame('user@example.com', $httpRequestHeader->getFrom());
        $this->assertSame('"737060cd8c284d8af7ad3082f209582d"', $httpRequestHeader->getIfMatch());
        $this->assertSame('i8XNjC4b8KVok4uw5RftR38Wgp2BFwql', $httpRequestHeader->getXCsrfToken());
        $this->assertSame('"737060cd8c284d8af7ad3082f209582d"', $httpRequestHeader->getIfRange());
        $this->assertSame('Sat, 29 Oct 1994 19:43:31 GMT', $httpRequestHeader->getIfUnmodifiedSince());
        $this->assertSame('Sat, 29 Oct 1994 19:43:31 GMT', $httpRequestHeader->getIfModifiedSince());
        $this->assertSame('10', $httpRequestHeader->getMaxForwards());
        $this->assertSame('no-cache', $httpRequestHeader->getPragma());
        $this->assertSame('bytes=500-999', $httpRequestHeader->getRange());
        $this->assertSame('trailers, deflate', $httpRequestHeader->getTe());
        $this->assertSame('HTTP/2.0, SHTTP/1.3, IRC/6.9, RTA/x11', $httpRequestHeader->getUpgrade());
        $this->assertSame('1.0 fred, 1.1 example.com (Apache/1.1)', $httpRequestHeader->getVia());
        $this->assertSame('199 Miscellaneous warning', $httpRequestHeader->getWarning());
        $this->assertSame('XMLHttpRequest', $httpRequestHeader->getXRequestedWith());
        $this->assertSame('129.78.138.66, 129.78.64.103', $httpRequestHeader->getXForwardedFor());
        $this->assertSame('en.wikipedia.org', $httpRequestHeader->getXForwardedHost());
        $this->assertSame('https', $httpRequestHeader->getXForwardedProto());
        $this->assertSame('GET', $httpRequestHeader->getXHttpMethodOverride());
        $this->assertSame('http://wap.samsungmobile.com/uaprof/SGH-I777.xml', $httpRequestHeader->getXWapProfile());
        $this->assertSame('keep-alive', $httpRequestHeader->getProxyConnection());
        $this->assertSame('http://en.wikipedia.org/wiki/Main_Page', $httpRequestHeader->getReferer());
        $this->assertSame('http://www.example-social-network.com', $httpRequestHeader->getOrigin());
        $this->assertSame('gog_lc=FR_EUR_fr; cart_token=69a3f0ca4bd091e5; _ga=GA1.2.1789784146.1441146621; _gat=1', $httpRequestHeader->getCookie());

        $this->assertSame('Upgrade-Insecure-Requests', $httpRequestHeader->getHeaders()['upgrade-insecure-requests'][0]);
        $this->assertSame('1', $httpRequestHeader->getHeaders()['upgrade-insecure-requests'][1]);
    }

    /**
     * @covers HttpRequestHeader::convert()
     */
    public function testConvert()
    {
        $headers = 'Host: www.pcre.org
User-Agent: Mozilla/5.0 (Windows NT 6.1; rv:41.0) Gecko/20100101 Firefox/41.0
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8
Accept-Encoding: gzip, deflate
DNT: 1
Connection: keep-alive
If-Modified-Since: Tue, 28 Apr 2015 11:37:57 GMT
If-None-Match: "553f7115-22195"
Cache-Control: max-age=0';

        $expected = array(
            'Host' => 'www.pcre.org',
            'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1; rv:41.0) Gecko/20100101 Firefox/41.0',
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Encoding' => 'gzip, deflate',
            'DNT' => '1',
            'Connection' => 'keep-alive',
            'If-Modified-Since' => 'Tue, 28 Apr 2015 11:37:57 GMT',
            'If-None-Match' => '"553f7115-22195"',
            'Cache-Control' => 'max-age=0',
            'Accept-Charset' => 'utf-8',
            'Accept-Language' => 'en',
        );

        $httpRequestHeader = new HttpRequestHeader($headers);

        $this->assertEquals($expected, $httpRequestHeader->convert());
    }
}
