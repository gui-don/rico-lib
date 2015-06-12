<?php

namespace Rico\Test\HttpRequestTest;

use Rico\Lib\Crawler\HttpRequest;
use Rico\Lib\Crawler\Exception\InvalidUrlException;

/**
 * Test class for HttpRequest
 */
class HttpRequestTest extends \PHPUnit_Framework_TestCase
{
    public static $webServerProcess;
    public static $pipes;

    public static function setUpBeforeClass()
    {
        $descriptorspec = array(
            0 => array("pipe", "r"),
            1 => array("pipe", "w"),
        );

        self::$webServerProcess = proc_open('php -S 127.0.0.1:5555', $descriptorspec, self::$pipes, __DIR__.'/../files');
    }

    public static function tearDownAfterClass()
    {
        fclose(self::$pipes[0]);
        fclose(self::$pipes[1]);

        proc_terminate(self::$webServerProcess);
    }

    /**
     * @covers HttpRequest::__construct
     */
    public function testContructorSuccess()
    {
        $classname = 'Rico\Lib\Crawler\HttpRequest';

        // Get mock, without the constructor being called
        $mock = $this->getMockBuilder($classname)->disableOriginalConstructor()->getMock();

        // Expectations
        $mock->expects($this->once())->method('setUrl')->with($this->equalTo('https://php.net/manual/fr/book.fileinfo.php'))->willReturn($mock);

        // Test it
        $reflectedClass = new \ReflectionClass($classname);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($mock, 'https://php.net/manual/fr/book.fileinfo.php');
    }

    /**
     * @covers HttpRequest::__construct
     * @expectedException Rico\Lib\Crawler\Exception\InvalidUrlException
     */
    public function testConstructorFail()
    {
        new HttpRequest('hhhtp://google.com');
    }

    /**
     * @covers HttpRequest::send
     */
    public function testSend()
    {
        $httpRequest = new HttpRequest('http://127.0.0.1:5555/httpAnswer.php');
        $httpResponse = $httpRequest->send();

        // Check response
        $this->assertSame('TOUT EST BON', $httpResponse->getContent());
        $this->assertRegExp('#HTTP\/1\.1 200 OK#', $httpResponse->getHeaders());
        $this->assertRegExp('#Host\: 127\.0\.0\.1#', $httpResponse->getHeaders());
        $this->assertRegExp('#Set\-Cookie\: whatwhat\=ok#', $httpResponse->getHeaders());
        $this->assertRegExp('#Content\-Language\: fr#', $httpResponse->getHeaders());
        $this->assertFalse((bool) preg_match('#Content\-Encoding#', $httpResponse->getHeaders()));
        $this->assertSame(200, $httpResponse->getCode());
        // Check request
        $this->assertSame(HttpRequest::STATUS_SEND, $httpRequest->getStatus());

        return $httpRequest;
    }

    /**
     * @covers HttpRequest::setUrl
     * @depends testSend
     */
    public function testUrlSuccess($httpRequest)
    {
        $httpRequest->setUrl('http://127.0.0.1:5555/server.php');

        $this->assertSame('http://127.0.0.1:5555/server.php', $httpRequest->getUrl());
        $this->assertSame('127.0.0.1', $httpRequest->getHeaderHost());
        $this->assertSame(HttpRequest::STATUS_NOT_SEND, $httpRequest->getStatus());

        return $httpRequest;
    }

    /**
     * @covers HttpRequest::setUrl
     * @depends testUrlSuccess
     */
    public function testUrlFail($httpRequest)
    {
        $this->assertFalse($httpRequest->setUrl('WRONG URL'));

        $this->assertSame('http://127.0.0.1:5555/server.php', $httpRequest->getUrl());
        $this->assertSame('127.0.0.1', $httpRequest->getHeaderHost());
        $this->assertSame(HttpRequest::STATUS_NOT_SEND, $httpRequest->getStatus());

        return $httpRequest;
    }

    /**
     * @covers HttpRequest::send
     * @depends testUrlFail
     */
    public function testSendWithDefaultHeaders($httpRequest)
    {
        // It gets http://127.0.0.1:5555/server.php, which return the $_SERVER supervar to check request headers
        $httpResponse = $httpRequest->send();

        // Check response
        $this->assertNotEmpty($httpResponse->getContent());
        $this->assertRegExp('#HTTP\/1\.1 200 OK#', $httpResponse->getHeaders());
        $this->assertRegExp('#Host\: 127\.0\.0\.1#', $httpResponse->getHeaders());
        $this->assertRegExp('#Set\-Cookie\: MagicCookie\=YUMMY\; another=one#', $httpResponse->getHeaders());
        $this->assertRegExp('#Content\-Encoding\: gzip#', $httpResponse->getHeaders());
        $this->assertFalse((bool) preg_match('#Content\-Language#', $httpResponse->getHeaders()));
        $this->assertSame(200, $httpResponse->getCode());
        $this->assertSame('text/html', $httpResponse->getMime());
        // Check the request headers
        $this->assertRegExp('#\[HTTP_USER_AGENT\] => Mozilla\/5\.0 \(Windows NT 6\.1\; rv\:40\.0\) Gecko\/20100101 Firefox\/40\.0#', $httpResponse->getContent());
        $this->assertRegExp('#\[HTTP_ACCEPT_CHARSET\] => utf\-8#', $httpResponse->getContent());
        $this->assertRegExp('#\[HTTP_CONNECTION\] => Keep\-Alive#', $httpResponse->getContent());
        $this->assertRegExp('#\[HTTP_ACCEPT_ENCODING\] => gzip\, deflate#', $httpResponse->getContent());
        $this->assertRegExp('#\[HTTP_ACCEPT_LANGUAGE\] => en#', $httpResponse->getContent());
        $this->assertRegExp('#\[HTTP_CACHE_CONTROL\] => max\-age=0#', $httpResponse->getContent());
        $this->assertRegExp('#\[SERVER_PORT\] => 5555#', $httpResponse->getContent());
        // Check request
        $this->assertSame(HttpRequest::STATUS_SEND, $httpRequest->getStatus());

        return $httpRequest;
    }

    /**
     * @covers HttpRequest::send
     * @depends testSendWithDefaultHeaders
     */
    public function testSendWithAlternativeHeaders($httpRequest)
    {
        $httpRequest->setHeaderHost('test.local.com');
        $httpRequest->setHeaderUserAgent('Opera/9.80 (X11; Linux i686; Ubuntu/14.10) Presto/2.12.388 Version/12.16');
        $httpRequest->setHeaderAcceptEncoding(null);
        $httpRequest->setHeaderCacheControl('no-cache');
        $httpRequest->setHeaderCookie('somecookie=yeah!');
        $httpRequest->setHeaderIfMatch('"737060cd8c284d8af7ad3082f209582d"');
        $httpRequest->setHeaderIfModifiedSince('Sat, 29 Oct 1994 19:43:31 GMT');
        $httpRequest->setHeaderIfNoneMatch('"847060cd8c284d8af7ad3082f209584a"');
        $httpRequest->setHeaderMaxForwards('10');
        $httpRequest->setHeaderOrigin('http://www.example-social-network.com');
        $httpRequest->setHeaderPragma('no-cache');
        $httpRequest->setHeaderVia('1.0 fred, 1.1 example.com (Apache/1.1)');
        $httpRequest->setHeaderRequestedWith('XMLHttpRequest');
        $httpRequest->setHeaderForwardedFor('129.78.138.66, 129.78.64.103');
        $httpRequest->setHeaderForwardedHost('en.wikipedia.org:80');
        $httpRequest->setHeaderForwardedProto('https');
        $httpRequest->setHeaderProxyConnection('keep-alive');
        $httpRequest->setHeaderReferer('https://es.yahoo.com/');

        $httpResponse = $httpRequest->send();

        // Check response
        $this->assertNotEmpty($httpResponse->getContent());
        $this->assertRegExp('#HTTP\/1\.1 200 OK#', $httpResponse->getHeaders());
        $this->assertRegExp('#Host\: test.local.com#', $httpResponse->getHeaders());
        $this->assertRegExp('#Set\-Cookie\: MagicCookie\=YUMMY\; another=one#', $httpResponse->getHeaders());
        $this->assertFalse((bool) preg_match('#Content\-Language#', $httpResponse->getHeaders()));
        $this->assertSame(200, $httpResponse->getCode());
        $this->assertSame('text/html', $httpResponse->getMime());
        // Check the request headers
        $this->assertRegExp('#\[HTTP_USER_AGENT\] => Opera\/9\.80 \(X11\; Linux i686\; Ubuntu\/14\.10\) Presto\/2\.12\.388 Version\/12\.16#', $httpResponse->getContent());
        $this->assertRegExp('#\[HTTP_HOST\] => test.local.com#', $httpResponse->getContent());
        $this->assertRegExp('#\[HTTP_ACCEPT_CHARSET\] => utf\-8#', $httpResponse->getContent());
        $this->assertRegExp('#\[HTTP_CONNECTION\] => Keep\-Alive#', $httpResponse->getContent());
        $this->assertFalse((bool) preg_match('#\[HTTP_ACCEPT_ENCODING\]#', $httpResponse->getContent()));
        $this->assertRegExp('#\[HTTP_ACCEPT_LANGUAGE\] => en#', $httpResponse->getContent());
        $this->assertRegExp('#\[HTTP_CACHE_CONTROL\] => no\-cache#', $httpResponse->getContent());
        $this->assertRegExp('#\[HTTP_COOKIE\] => somecookie=yeah!#', $httpResponse->getContent());
        $this->assertRegExp('#\[HTTP_IF_MATCH\] => "737060cd8c284d8af7ad3082f209582d"#', $httpResponse->getContent());
        $this->assertRegExp('#\[HTTP_IF_MODIFIED_SINCE\] => Sat\, 29 Oct 1994 19\:43\:31 GMT#', $httpResponse->getContent());
        $this->assertRegExp('#\[HTTP_IF_NONE_MATCH\] => "847060cd8c284d8af7ad3082f209584a"#', $httpResponse->getContent());
        $this->assertRegExp('#\[HTTP_MAX_FORWARDS\] => 10#', $httpResponse->getContent());
        $this->assertRegExp('#\[HTTP_ORIGIN\] => http\:\/\/www\.example\-social\-network\.com#', $httpResponse->getContent());
        $this->assertRegExp('#\[HTTP_PRAGMA\] => no\-cache#', $httpResponse->getContent());
        $this->assertRegExp('#\[HTTP_VIA\] => 1\.0 fred\, 1\.1 example\.com \(Apache\/1\.1\)#', $httpResponse->getContent());
        $this->assertRegExp('#\[HTTP_X_REQUESTED_WITH\] => XMLHttpRequest#', $httpResponse->getContent());
        $this->assertRegExp('#\[HTTP_X_FORWARDED_FOR\] => 129\.78\.138\.66\, 129\.78\.64\.103#', $httpResponse->getContent());
        $this->assertRegExp('#\[HTTP_X_FORWARDED_HOST\] => en\.wikipedia\.org\:80#', $httpResponse->getContent());
        $this->assertRegExp('#\[HTTP_X_FORWARDED_PROTO\] => https#', $httpResponse->getContent());
        $this->assertRegExp('#\[HTTP_PROXY_CONNECTION\] => keep\-alive#', $httpResponse->getContent());
        $this->assertRegExp('#\[HTTP_REFERER\] => https\:\/\/es\.yahoo\.com\/#', $httpResponse->getContent());
        $this->assertRegExp('#\[SERVER_PORT\] => 5555#', $httpResponse->getContent());
        // Check request
        $this->assertSame(HttpRequest::STATUS_SEND, $httpRequest->getStatus());

        return $httpRequest;
    }

    /**
     * @covers HttpRequest::send
     * @depends testSendWithAlternativeHeaders
     */
    public function testSendWithAnImage($httpRequest)
    {
        $httpRequest->setUrl('http://127.0.0.1:5555/rico.png');
        $httpResponse = $httpRequest->send();

        // Check response
        $this->assertNotEmpty($httpResponse->getContent());
        $this->assertRegExp('#HTTP\/1\.1 200 OK#', $httpResponse->getHeaders());
        $this->assertRegExp('#Host\: 127.0.0.1#', $httpResponse->getHeaders());
        $this->assertRegExp('#Content\-Length\: 32645#', $httpResponse->getHeaders());
        $this->assertSame(200, $httpResponse->getCode());
        $this->assertSame('image/png', $httpResponse->getMime());

        return $httpRequest;
    }

    /**
     * @covers HttpRequest::send
     * @depends testSendWithAnImage
     */
    public function testSendWith404($httpRequest)
    {
        $httpRequest->setUrl('http://127.0.0.1:5555/404.php');
        $httpResponse = $httpRequest->send();

        // Check response
        $this->assertSame('The page don’t exist.', $httpResponse->getContent());
        $this->assertRegExp('#HTTP\/1\.1 404 Not Found#', $httpResponse->getHeaders());
        $this->assertRegExp('#Host\: 127.0.0.1#', $httpResponse->getHeaders());
        $this->assertSame(404, $httpResponse->getCode());
        $this->assertSame('text/html', $httpResponse->getMime());
    }

    /**
     * @covers HttpRequest::send
     * @depends testSendWithAnImage
     * @expectedException Rico\Lib\Crawler\Exception\DownloadException
     */
    public function testSendFail($httpRequest)
    {
        $httpRequest->setUrl('http://127.127.127.127:6666/thisLINKDONTEXIT');
        $httpRequest->send();

        return $httpRequest;
    }

}
