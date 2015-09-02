<?php

namespace Rico\Test\HttpHeaderTest;

use Rico\Lib\Crawler\HttpHeader;

class HttpHeaderTest extends \PHPUnit_Framework_TestCase
{
    public function providerAddHeader()
    {
        return array(
            array(0, 'test', false),
            array(new \DateTime('now'), 'test', false),
            array('ok', 0.73, false),
            array('ok', 'test', true),
            array('X-Firefox-Spdy', '3.1', true)
        );
    }

    /**
     * @covers HttpHeader::addHeader()
     * @dataProvider providerAddHeader
     */
    public function testAddHeader($header, $value, $expected)
    {
        $httpResponseHeader = new HttpHeader();

        if ($expected) {
            $httpResponseHeader->addHeader($header, $value);
            $this->assertArrayHasKey(strtolower($header), $httpResponseHeader->getHeaders());
            $this->assertSame($header, $httpResponseHeader->getHeaders()[strtolower($header)][0]);
            $this->assertSame($value, $httpResponseHeader->getHeaders()[strtolower($header)][1]);
        } else {
            $this->assertFalse($httpResponseHeader->addHeader($header, $value));
        }
    }
}
