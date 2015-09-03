<?php

namespace Rico\Test\HttpBrowserTest;

use Rico\Lib\Crawler\HttpBrowser;
use Rico\Lib\Crawler\HttpRequest;
use Rico\Lib\Crawler\HttpResponse;
use Rico\Lib\Crawler\DomParser;

/**
 * Test class for HttpBrowser
 */
class HttpBrowserTest extends \PHPUnit_Framework_TestCase
{
    public static $webServerProcess;
    public static $pipes;

    public static function setUpBeforeClass()
    {
        $descriptorspec = array(
            0 => array("pipe", "r"),
            1 => array("pipe", "w"),
        );

        self::$webServerProcess = proc_open('php -S 127.0.0.1:8888', $descriptorspec, self::$pipes, __DIR__.'/../files');
        sleep(2);
    }

    public static function tearDownAfterClass()
    {
        fclose(self::$pipes[0]);
        fclose(self::$pipes[1]);

        proc_terminate(self::$webServerProcess);
    }

    /**
     * @covers HttpBrowser::__construct()
     */
    public function testContructorSuccess()
    {
        $classname = 'Rico\Lib\Crawler\HttpBrowser';
        // Get mock, without the constructor being called
        $mock = $this->getMockBuilder($classname)->disableOriginalConstructor()->getMock();

        // Create http request object
        $httpRequestMock = new HttpRequest('http://127.0.0.1:8888/server.php');

        // Expect setRequest method to trigger once
        $mock->expects($this->once())->method('setRequest')->with($this->equalTo($httpRequestMock))->will($this->returnSelf());

        // Test it
        $reflectedClass = new \ReflectionClass($classname);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($mock, $httpRequestMock);
    }

    /**
     * @covers HttpBrowser::get
     */
    public function testGet()
    {
        //Get mocks, without the constructor being called
        $httpRequestMock = $this->getMockBuilder('Rico\Lib\Crawler\HttpRequest')->setMethods(array('send'))->setConstructorArgs(array('http://127.0.0.1:8888/server.php'))->getMock();
        $mock = $this->getMockBuilder('Rico\Lib\Crawler\HttpBrowser')->setMethods(array('setRequest', 'setResponse', 'setParser', 'getRequest', 'getResponse', 'getParser'))->setConstructorArgs(array($httpRequestMock))->getMock();

        // Set httpRequest mock
        $httpResponse = new HttpResponse('OK', 200, 'Header: OK', 'text/html');
        $httpDomParser = new DomParser('OK');
        $httpRequestMock->method('send')->willReturn($httpResponse);

        $mock->method('getResponse')->willReturn($httpResponse);
        $mock->method('getRequest')->willReturn($httpRequestMock);
        $mock->method('getParser')->willReturn($httpDomParser);

        // Expect setUrl method to trigger once
        $httpRequestMock->expects($this->once())->method('send')->will($this->returnSelf());
        $mock->expects($this->once())->method('setResponse')->with($this->equalTo($httpResponse))->will($this->returnSelf());
        $mock->expects($this->once())->method('setParser')->will($this->returnSelf());

        $mock->get();
    }

    /**
     * @covers HttpBrowser::get
     */
    public function testTrueGet()
    {
        $httpBrowser = new HttpBrowser(new HttpRequest('http://127.0.0.1:8888/server.php'));

        $begin = microtime(true);
        $httpBrowser->get();
        $end = microtime(true);
        $crawlingTime = $end - $begin;

        // Check request time
        $this->assertLessThan(1, $crawlingTime);
        // Check response
        $this->assertNotEmpty($httpBrowser->getResponse()->getContent());
        $this->assertNotEmpty($httpBrowser->getResponse()->getHeaders());
        $this->assertNotEmpty($httpBrowser->getResponse()->getMime());
        $this->assertSame(200, $httpBrowser->getResponse()->getCode());
        // Check request
        $this->assertSame('MagicCookie=YUMMY; another=one', $httpBrowser->getRequest()->getHeaders()->getCookie());
        $this->assertSame('http://127.0.0.1:8888/server.php', $httpBrowser->getRequest()->getHeaders()->getReferer());

        return $httpBrowser;
    }

    /**
     * @covers HttpBrowser::get
     * @depends testTrueGet
     */
    public function testGetWithoutStrictMode(HttpBrowser $httpBrowser)
    {
        $httpBrowser->setStrictMode(false);
        $httpBrowser->getRequest()->setUrl('http://127.0.0.1:8888/404.php');

        // This works because strict mode is disabled
        $begin = microtime(true);
        $httpBrowser->get();
        $end = microtime(true);
        $crawlingTime = $end - $begin;

        // Check request time
        $this->assertLessThan(1, $crawlingTime);
        // Check response
        $this->assertNotEmpty($httpBrowser->getResponse()->getContent());
        $this->assertNotEmpty($httpBrowser->getResponse()->getHeaders());
        $this->assertNotEmpty($httpBrowser->getResponse()->getMime());
        $this->assertSame(404, $httpBrowser->getResponse()->getCode());
        // Check request
        $this->assertSame('MagicCookie=YUMMY; another=one', $httpBrowser->getRequest()->getHeaders()->getCookie());
        $this->assertSame('http://127.0.0.1:8888/404.php', $httpBrowser->getRequest()->getHeaders()->getReferer());

        return $httpBrowser;
    }

    /**
     * @covers HttpBrowser::get
     * @depends testGetWithoutStrictMode
     */
    public function testGetNewCookie(HttpBrowser $httpBrowser)
    {
        $httpBrowser->getRequest()->setUrl('http://127.0.0.1:8888/newCookie.php');

        $begin = microtime(true);
        $httpBrowser->get();
        $end = microtime(true);
        $crawlingTime = $end - $begin;

        // Check request time
        $this->assertLessThan(1, $crawlingTime);
        // Check response
        $this->assertSame('http://127.0.0.1:8888/404.php', $httpBrowser->getResponse()->getContent()); // Check sent referer
        $this->assertNotEmpty($httpBrowser->getResponse()->getHeaders());
        $this->assertNotEmpty($httpBrowser->getResponse()->getMime());
        $this->assertSame(200, $httpBrowser->getResponse()->getCode());
        // Check request
        $this->assertSame('MagicCookie=NEW; another=one', $httpBrowser->getRequest()->getHeaders()->getCookie());
        $this->assertSame('http://127.0.0.1:8888/newCookie.php', $httpBrowser->getRequest()->getHeaders()->getReferer());

        return $httpBrowser;
    }

    /**
     * @covers HttpBrowser::get
     * @depends testGetNewCookie
     */
    public function testGetWithoutReferer(HttpBrowser $httpBrowser)
    {
        $httpBrowser->setDisableReferer(true);

        $begin = microtime(true);
        $httpBrowser->get();
        $end = microtime(true);
        $crawlingTime = $end - $begin;

        // Check request time
        $this->assertLessThan(1, $crawlingTime);
        // Check response
        $this->assertSame('', $httpBrowser->getResponse()->getContent());
        $this->assertNotEmpty($httpBrowser->getResponse()->getHeaders());
        $this->assertNotEmpty($httpBrowser->getResponse()->getMime());
        $this->assertSame(200, $httpBrowser->getResponse()->getCode());
        // Check request
        $this->assertSame('MagicCookie=NEW; another=one', $httpBrowser->getRequest()->getHeaders()->getCookie());
        $this->assertSame('', $httpBrowser->getRequest()->getHeaders()->getReferer());

        return $httpBrowser;
    }

    /**
     * @covers HttpBrowser::get
     * @depends testGetWithoutReferer
     */
    public function testGetWithDelay(HttpBrowser $httpBrowser)
    {
        $httpBrowser->setDelay(2);
        $httpBrowser->setDelayMargin(1);

        $begin = microtime(true);
        $httpBrowser->get();
        $end = microtime(true);
        $crawlingTime = $end - $begin;

        // Check request time
        $this->assertLessThan(2 + 1 + 1, $crawlingTime);
        $this->assertGreaterThan(2 - 1, $crawlingTime);
        // Check response
        $this->assertSame('', $httpBrowser->getResponse()->getContent());
        $this->assertNotEmpty($httpBrowser->getResponse()->getHeaders());
        $this->assertNotEmpty($httpBrowser->getResponse()->getMime());
        $this->assertSame(200, $httpBrowser->getResponse()->getCode());
        // Check request
        $this->assertSame('MagicCookie=NEW; another=one', $httpBrowser->getRequest()->getHeaders()->getCookie());
        $this->assertSame('', $httpBrowser->getRequest()->getHeaders()->getReferer());

        return $httpBrowser;
    }

    /**
     * @covers HttpBrowser::get
     * @depends testGetWithDelay
     * @expectedException Rico\Lib\Crawler\Exception\ResponseException
     */
    public function testGetFail(HttpBrowser $httpBrowser)
    {
        $httpBrowser->setStrictMode(true);
        $httpBrowser->setDelay(0);
        $httpBrowser->setDelayMargin(0);
        $httpBrowser->getRequest()->setUrl('http://127.0.0.1:8888/404.php');

        // This fails, because strict mode is enabled and URL returns a 404
        $httpBrowser->get();
    }

    /**
     * @covers HttpBrowser::clickTo
     */
    public function testClickTo()
    {
        $httpBrowser = new HttpBrowser(new HttpRequest('http://127.0.0.1:8888/server.php'));

        // Previous URL is totally ignored because it wasn’t downloaded
        $begin = microtime(true);
        $httpBrowser->clickTo('http://127.0.0.1:8888/newCookie.php');
        $end = microtime(true);
        $crawlingTime = $end - $begin;

        // Check request time
        $this->assertLessThan(1, $crawlingTime);
        // Check response
        $this->assertSame('', $httpBrowser->getResponse()->getContent());
        $this->assertNotEmpty($httpBrowser->getResponse()->getHeaders());
        $this->assertNotEmpty($httpBrowser->getResponse()->getMime());
        $this->assertSame(200, $httpBrowser->getResponse()->getCode());
        // Check request
        $this->assertSame('MagicCookie=NEW', $httpBrowser->getRequest()->getHeaders()->getCookie());
        $this->assertSame('http://127.0.0.1:8888/newCookie.php', $httpBrowser->getRequest()->getHeaders()->getReferer());

        return $httpBrowser;
    }

    /**
     * @covers HttpBrowser::clickTo
     * @depends testClickTo
     */
    public function testClickToWithPreserveHost($httpBrowser)
    {
        $httpBrowser->getRequest()->getHeaders()->setHost('fantasy-host.com');

        // Even if a new URL is loaded, host must not changed because of the preserveHost parameter
        $begin = microtime(true);
        $httpBrowser->clickTo('http://127.0.0.1:8888/server.php', true);
        $end = microtime(true);
        $crawlingTime = $end - $begin;

        // Check request time
        $this->assertLessThan(1, $crawlingTime);
        // Check response
        $this->assertNotEmpty($httpBrowser->getResponse()->getContent());
        $this->assertNotEmpty($httpBrowser->getResponse()->getHeaders());
        $this->assertNotEmpty($httpBrowser->getResponse()->getMime());
        $this->assertSame(200, $httpBrowser->getResponse()->getCode());
        // Check host
        $this->assertRegExp('#\[HTTP_HOST\] => fantasy\-host\.com#', $httpBrowser->getResponse()->getContent());
        // Check referer
        $this->assertRegExp('#\[HTTP_REFERER\] => http\:\/\/127\.0\.0\.1\:8888\/newCookie\.php#', $httpBrowser->getResponse()->getContent());
        // Check request
        $this->assertSame('MagicCookie=YUMMY; another=one', $httpBrowser->getRequest()->getHeaders()->getCookie());
        $this->assertSame('http://127.0.0.1:8888/server.php', $httpBrowser->getRequest()->getHeaders()->getReferer());
    }
}
