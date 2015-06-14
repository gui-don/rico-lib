<?php

namespace Rico\Test\HttpResponseTest;


/**
 * Test class for HttpResponse
 */
class HttpResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers HttpResponse::__construct()
     */
    public function testContructorSuccess()
    {
        $classname = 'Rico\Lib\Crawler\HttpResponse';

        // Get mock, without the constructor being called
        $mock = $this->getMockBuilder($classname)->disableOriginalConstructor()->getMock();

        // Expectations
        $mock->expects($this->once())->method('setContent')->with($this->equalTo('Some content'))->willReturn($mock);
        $mock->expects($this->once())->method('setCode')->with($this->equalTo(200))->willReturn($mock);
        $mock->expects($this->once())->method('setHeaders')->with($this->equalTo('Status: 200 OK'))->willReturn($mock);
        $mock->expects($this->once())->method('setMime')->with($this->equalTo('application/x-www-form-urlencoded'))->willReturn($mock);

        // Test it
        $reflectedClass = new \ReflectionClass($classname);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($mock, 'Some content', 200, 'Status: 200 OK', 'application/x-www-form-urlencoded');
    }

    /**
     * @covers HttpResponse::save
     */
    public function testSave()
    {

    }

    /**
     * @covers HttpResponse::save
     */
    public function testSaveWithoutFilename()
    {

    }

    /**
     * @covers HttpResponse::save
     */
    public function testSaveWithoutOverride()
    {

    }
}
