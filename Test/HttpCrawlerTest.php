<?php

namespace Rico\Test\HttpCrawlerTest;

use \Rico\Lib\HttpCrawler;

/**
 * Test class for HttpCrawler
 */
class HttpCrawlerTest extends \PHPUnit_Framework_TestCase
{
    public static $webServerProcess;
    public static $pipes;

    public static function setUpBeforeClass()
    {
        $descriptorspec = array(
            0 => array("pipe", "r"),
            1 => array("pipe", "w"),
        );

        self::$webServerProcess = proc_open('php -S 127.0.0.1:8888', $descriptorspec, self::$pipes, __DIR__.'/files');
    }

    public static function tearDownAfterClass()
    {
        fclose(self::$pipes[0]);
        fclose(self::$pipes[1]);

        proc_terminate(self::$webServerProcess);
    }

    /**
     * @covers HttpCrawler::__construct()
     */
    public function testContructorMock()
    {
        $classname = 'Rico\Lib\HttpCrawler';

        // Get mock, without the constructor being called
        $mock = $this->getMockBuilder($classname)->disableOriginalConstructor()->getMock();

        // Expect setUrl method to trigger once
        $mock->expects($this->once())
            ->method('setUrl')
            ->with($this->equalTo('http://127.0.0.1:8888/server.php'))
            ->willReturn($mock);

        // Test it
        $reflectedClass = new \ReflectionClass($classname);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($mock, 'http://127.0.0.1:8888/server.php');
    }

    /**
     * @covers HttpCrawler::__construct()
     * @expectedException Rico\Lib\Exception\InvalidUrlException
     */
    public function testConstructorFail()
    {
        new HttpCrawler('THIS IS NOT AN URL');
    }

    /**
     * @covers HttpCrawler::loadCurrentPage
     */
    public function testLoadCurrentPage()
    {
        $httpCrawler1 = new HttpCrawler('http://127.0.0.1:8888/server.php');

        $begin = microtime(true);
        $httpCrawler1->loadCurrentPage();
        $end = microtime(true);
        $crawlingTime = $end - $begin;

        $this->assertLessThan(1, $crawlingTime);
        $this->assertNotEmpty($httpCrawler1->getRawBody());
        $this->assertSame('http://127.0.0.1:8888/server.php', $httpCrawler1->getReferer());
        $this->assertInstanceOf('\Rico\Lib\DomParser', $httpCrawler1->getParser());
        $this->assertRegExp('#\[HTTP_USER_AGENT\] => Mozilla\/5\.0 \(Windows NT 6\.1\; rv\:40\.0\) Gecko\/20100101 Firefox\/40\.0#', $httpCrawler1->getRawBody());
        $this->assertRegExp('#\[HTTP_DNT\] => 1#', $httpCrawler1->getRawBody());
        $this->assertRegExp('#\[HTTP_ACCEPT_CHARSET\] => utf\-8#', $httpCrawler1->getRawBody());
        $this->assertRegExp('#\[HTTP_CONNECTION\] => Keep\-Alive#', $httpCrawler1->getRawBody());
        $this->assertRegExp('#\[HTTP_ACCEPT_ENCODING\] => gzip\, deflate#', $httpCrawler1->getRawBody());
        $this->assertRegExp('#\[HTTP_ACCEPT_LANGUAGE\] => en#', $httpCrawler1->getRawBody());
        $this->assertRegExp('#\[HTTP_CACHE_CONTROL\] => max\-age=0#', $httpCrawler1->getRawBody());
        $this->assertRegExp('#\[SERVER_PORT\] => 8888#', $httpCrawler1->getRawBody());

        return $httpCrawler1;
    }

    /**
     * @covers HttpCrawler::loadCurrentPage
     * @depends testLoadCurrentPage
     */
    public function testLoadCurrentPageAlternative(HttpCrawler $httpCrawler1)
    {
        // Load with advanced options
        $httpCrawler1->setHost('test.local.com');
        $httpCrawler1->setUserAgent('Opera/9.80 (X11; Linux i686; Ubuntu/14.10) Presto/2.12.388 Version/12.16');
        $httpCrawler1->setHeaderAcceptEncoding(null);

        $begin = microtime(true);
        $httpCrawler1->loadCurrentPage();
        $end = microtime(true);
        $crawlingTime = $end - $begin;

        $this->assertLessThan(1, $crawlingTime);
        $this->assertRegExp('#\[HTTP_USER_AGENT\] => Opera\/9\.80 \(X11\; Linux i686\; Ubuntu\/14\.10\) Presto\/2\.12\.388 Version\/12\.16#', $httpCrawler1->getRawBody());
        $this->assertRegExp('#\[HTTP_HOST\] => test\.local\.com#', $httpCrawler1->getRawBody());
        $this->assertRegExp('#\[HTTP_REFERER\] => http\:\/\/127\.0\.0\.1\:8888\/server\.php#', $httpCrawler1->getRawBody());
        $this->assertFalse((bool) preg_match('#\[HTTP_ACCEPT_ENCODING\]#', $httpCrawler1->getRawBody()));

        return $httpCrawler1;
    }

    /**
     * @covers HttpCrawler::clickTo
     * @depends testLoadCurrentPageAlternative
     */
    public function testClickToSuccess(HttpCrawler $httpCrawler1)
    {
        $begin = microtime(true);
        $httpCrawler1->clickTo('http://127.0.0.1:8888/server.php');
        $end = microtime(true);
        $crawlingTime = $end - $begin;

        $this->assertLessThan(1, $crawlingTime);
        $this->assertRegExp('#\[HTTP_HOST\] => 127.0.0.1#', $httpCrawler1->getRawBody());

        // Test delays
        $httpCrawler1->setSleep(2);
        $httpCrawler1->setSleepMargin(1);

        $begin = microtime(true);
        $httpCrawler1->clickTo('http://127.0.0.1:8888/page.html');
        $end = microtime(true);
        $crawlingTime = $end - $begin;

        $this->assertLessThan((2 + 1 + 1), $crawlingTime);
        $this->assertGreaterThan((2 - 1), $crawlingTime);

        return $httpCrawler1;
    }

    /**
     * @covers HttpCrawler::clickTo
     * @expectedException Rico\Lib\Exception\InvalidUrlException
     * @depends testClickToSuccess
     */
    public function testClickToFail(HttpCrawler $httpCrawler1)
    {
        $httpCrawler1->clickTo('fake URL');
    }

    /**
     * @covers HttpCrawler::loadCurrentPage
     * @expectedException Rico\Lib\Exception\DownloadException
     */
    public function testLoadCurrentPageFail()
    {
        $httpCrawler = new HttpCrawler('http://127.0.0.1:8888/404.php');
        // This should deliver a 404, thus the exception
        $httpCrawler->loadCurrentPage();
    }

    /**
     * @covers HttpCrawler::setUrl
     */
    public function testSetUrlSuccess()
    {
        $httpCrawler = new HttpCrawler('http://amazon.com/');
        $this->assertSame('amazon.com', $httpCrawler->getHost());
        $return = $httpCrawler->setUrl('https://phpunit.de/manual/current/en/test-doubles.html');
        $this->assertInstanceOf('Rico\Lib\HttpCrawler', $return);
        $this->assertSame('phpunit.de', $httpCrawler->getHost());
    }
}
