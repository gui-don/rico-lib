<?php

namespace Rico\Test\HttpHandlerTest;

use Rico\Lib\Crawler\HttpHandler;
use Rico\Lib\Crawler\HttpRequest;
use Rico\Lib\Crawler\HttpResponse;
use Rico\Lib\Crawler\DomParser;

/**
 * Test class for HttpHandler
 */
class HttpHandlerTest extends \PHPUnit_Framework_TestCase
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
     * @covers HttpHandler::__construct()
     */
    public function testContructorSuccess()
    {
        $classname = 'Rico\Lib\Crawler\HttpHandler';
        // Get mock, without the constructor being called
        $mock = $this->getMockBuilder($classname)->disableOriginalConstructor()->getMock();

        // Create http request object
        $httpRequestMock = new HttpRequest('http://127.0.0.1:8888/server.php');

        // Expect setUrl method to trigger once
        $mock->expects($this->once())->method('setRequest')->with($this->equalTo($httpRequestMock))->will($this->returnSelf());

        // Test it
        $reflectedClass = new \ReflectionClass($classname);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($mock, 'http://127.0.0.1:8888/server.php');
    }

    /**
     * @covers HttpHandler::get
     */
    public function testGet()
    {
        //Get mocks, without the constructor being called
        $httpRequestMock = $this->getMockBuilder('Rico\Lib\Crawler\HttpRequest')->setMethods(array('send'))->setConstructorArgs(array('http://127.0.0.1:8888/server.php'))->getMock();
        $mock = $this->getMockBuilder('Rico\Lib\Crawler\HttpHandler')->setMethods(array('setRequest', 'setResponse', 'setParser', 'getRequest', 'getResponse', 'getParser'))->setConstructorArgs(array('http://127.0.0.1:8888/server.php'))->getMock();

        // Set httpRequest mock
        $httpResponse = new HttpResponse('OK', 200, 'headers...', 'text/html');
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
     * @covers HttpHandler::get
     */
    public function testTrueGet()
    {
        $httpHandler = new HttpHandler('http://127.0.0.1:8888/server.php');

        $begin = microtime(true);
        $httpHandler->get();
        $end = microtime(true);
        $crawlingTime = $end - $begin;

        // Check request time
        $this->assertLessThan(1, $crawlingTime);
        // Check response
        $this->assertNotEmpty($httpHandler->getResponse()->getContent());
        $this->assertNotEmpty($httpHandler->getResponse()->getHeaders());
        $this->assertNotEmpty($httpHandler->getResponse()->getMime());
        $this->assertSame(200, $httpHandler->getResponse()->getCode());
        // Check request
        $this->assertSame('MagicCookie=YUMMY; another=one', $httpHandler->getRequest()->getHeaderCookie());
        $this->assertSame('http://127.0.0.1:8888/server.php', $httpHandler->getRequest()->getHeaderReferer());

        return $httpHandler;
    }

    /**
     * @covers HttpHandler::get
     * @depends testTrueGet
     */
    public function testGetWithoutStrictMode(HttpHandler $httpHandler)
    {
        $httpHandler->setStrictMode(false);
        $httpHandler->getRequest()->setUrl('http://127.0.0.1:8888/404.php');

        // This works because strict mode is disabled
        $begin = microtime(true);
        $httpHandler->get();
        $end = microtime(true);
        $crawlingTime = $end - $begin;

        // Check request time
        $this->assertLessThan(1, $crawlingTime);
        // Check response
        $this->assertNotEmpty($httpHandler->getResponse()->getContent());
        $this->assertNotEmpty($httpHandler->getResponse()->getHeaders());
        $this->assertNotEmpty($httpHandler->getResponse()->getMime());
        $this->assertSame(404, $httpHandler->getResponse()->getCode());
        // Check request
        $this->assertSame('MagicCookie=YUMMY; another=one', $httpHandler->getRequest()->getHeaderCookie());
        $this->assertSame('http://127.0.0.1:8888/404.php', $httpHandler->getRequest()->getHeaderReferer());

        return $httpHandler;
    }

    /**
     * @covers HttpHandler::get
     * @depends testGetWithoutStrictMode
     */
    public function testGetNewCookie(HttpHandler $httpHandler)
    {
        $httpHandler->getRequest()->setUrl('http://127.0.0.1:8888/newCookie.php');

        $begin = microtime(true);
        $httpHandler->get();
        $end = microtime(true);
        $crawlingTime = $end - $begin;

        // Check request time
        $this->assertLessThan(1, $crawlingTime);
        // Check response
        $this->assertSame('http://127.0.0.1:8888/404.php', $httpHandler->getResponse()->getContent()); // Check sent referer
        $this->assertNotEmpty($httpHandler->getResponse()->getHeaders());
        $this->assertNotEmpty($httpHandler->getResponse()->getMime());
        $this->assertSame(200, $httpHandler->getResponse()->getCode());
        // Check request
        $this->assertSame('MagicCookie=NEW; another=one', $httpHandler->getRequest()->getHeaderCookie());
        $this->assertSame('http://127.0.0.1:8888/newCookie.php', $httpHandler->getRequest()->getHeaderReferer());

        return $httpHandler;
    }

    /**
     * @covers HttpHandler::get
     * @depends testGetNewCookie
     */
    public function testGetWithoutReferer(HttpHandler $httpHandler)
    {
        $httpHandler->setDisableReferer(true);

        $begin = microtime(true);
        $httpHandler->get();
        $end = microtime(true);
        $crawlingTime = $end - $begin;

        // Check request time
        $this->assertLessThan(1, $crawlingTime);
        // Check response
        $this->assertSame('', $httpHandler->getResponse()->getContent());
        $this->assertNotEmpty($httpHandler->getResponse()->getHeaders());
        $this->assertNotEmpty($httpHandler->getResponse()->getMime());
        $this->assertSame(200, $httpHandler->getResponse()->getCode());
        // Check request
        $this->assertSame('MagicCookie=NEW; another=one', $httpHandler->getRequest()->getHeaderCookie());
        $this->assertSame('', $httpHandler->getRequest()->getHeaderReferer());

        return $httpHandler;
    }

    /**
     * @covers HttpHandler::get
     * @depends testGetWithoutReferer
     */
    public function testGetWithDelay(HttpHandler $httpHandler)
    {
        $httpHandler->setDelay(2);
        $httpHandler->setDelayMargin(1);

        $begin = microtime(true);
        $httpHandler->get();
        $end = microtime(true);
        $crawlingTime = $end - $begin;

        // Check request time
        $this->assertLessThan(2 + 1 + 1, $crawlingTime);
        $this->assertGreaterThan(2 - 1, $crawlingTime);
        // Check response
        $this->assertSame('', $httpHandler->getResponse()->getContent());
        $this->assertNotEmpty($httpHandler->getResponse()->getHeaders());
        $this->assertNotEmpty($httpHandler->getResponse()->getMime());
        $this->assertSame(200, $httpHandler->getResponse()->getCode());
        // Check request
        $this->assertSame('MagicCookie=NEW; another=one', $httpHandler->getRequest()->getHeaderCookie());
        $this->assertSame('', $httpHandler->getRequest()->getHeaderReferer());

        return $httpHandler;
    }

    /**
     * @covers HttpHandler::get
     * @depends testGetWithDelay
     * @expectedException Rico\Lib\Crawler\Exception\ResponseException
     */
    public function testGetFail(HttpHandler $httpHandler)
    {
        $httpHandler->setStrictMode(true);
        $httpHandler->setDelay(0);
        $httpHandler->setDelayMargin(0);
        $httpHandler->getRequest()->setUrl('http://127.0.0.1:8888/404.php');

        // This fails, because strict mode is enabled and URL returns a 404
        $httpHandler->get();
    }

    /**
     * @covers HttpHandler::clickTo
     */
    public function testClickTo()
    {
        $httpHandler = new HttpHandler('http://127.0.0.1:8888/server.php');

        // Previous URL is totally ignored because it wasn’t downloaded
        $begin = microtime(true);
        $httpHandler->clickTo('http://127.0.0.1:8888/newCookie.php');
        $end = microtime(true);
        $crawlingTime = $end - $begin;

        // Check request time
        $this->assertLessThan(1, $crawlingTime);
        // Check response
        $this->assertSame('', $httpHandler->getResponse()->getContent());
        $this->assertNotEmpty($httpHandler->getResponse()->getHeaders());
        $this->assertNotEmpty($httpHandler->getResponse()->getMime());
        $this->assertSame(200, $httpHandler->getResponse()->getCode());
        // Check request
        $this->assertSame('MagicCookie=NEW', $httpHandler->getRequest()->getHeaderCookie());
        $this->assertSame('http://127.0.0.1:8888/newCookie.php', $httpHandler->getRequest()->getHeaderReferer());

        return $httpHandler;
    }

    /**
     * @covers HttpHandler::clickTo
     * @depends testClickTo
     */
    public function testClickToWithPreserveHost($httpHandler)
    {
        $httpHandler->getRequest()->setHeaderHost('fantasy-host.com');

        // Even if a new URL is loaded, host must not changed because of the preserveHost parameter
        $begin = microtime(true);
        $httpHandler->clickTo('http://127.0.0.1:8888/server.php', true);
        $end = microtime(true);
        $crawlingTime = $end - $begin;

        // Check request time
        $this->assertLessThan(1, $crawlingTime);
        // Check response
        $this->assertNotEmpty($httpHandler->getResponse()->getContent());
        $this->assertNotEmpty($httpHandler->getResponse()->getHeaders());
        $this->assertNotEmpty($httpHandler->getResponse()->getMime());
        $this->assertSame(200, $httpHandler->getResponse()->getCode());
        // Check host
        $this->assertRegExp('#\[HTTP_HOST\] => fantasy\-host\.com#', $httpHandler->getResponse()->getContent());
        // Check referer
        $this->assertRegExp('#\[HTTP_REFERER\] => http\:\/\/127\.0\.0\.1\:8888\/newCookie\.php#', $httpHandler->getResponse()->getContent());
        // Check request
        $this->assertSame('MagicCookie=YUMMY; another=one', $httpHandler->getRequest()->getHeaderCookie());
        $this->assertSame('http://127.0.0.1:8888/server.php', $httpHandler->getRequest()->getHeaderReferer());
    }
}
