<?php

namespace Rico\Test\HttpResponseTest;

use Rico\Lib\Crawler\Exception\FileException;
use Rico\Lib\Crawler\HttpResponse;

/**
 * Test class for HttpResponse
 */
class HttpResponseTest extends \PHPUnit_Framework_TestCase
{
    const TEST_DIR = 'test/';
    public static $strFile = 'file.txt';


    public static function setUpBeforeClass()
    {
        mkdir(self::TEST_DIR);
        file_put_contents(self::TEST_DIR.self::$strFile, str_repeat(mt_rand(0,9), 5000));
    }

    public static function tearDownAfterClass()
    {
        unlink(self::TEST_DIR.self::$strFile);
        rmdir(self::TEST_DIR);
    }

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
        $mock->expects($this->once())->method('setHeaders')->with($this->equalTo(new \Rico\Lib\Crawler\HttpResponseHeader('Status: 200 OK')))->willReturn($mock);
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
        $savePath = 'Test/files/Très~SpécialÿChar .txt';

        $httpResponse = new HttpResponse('Some content', 200, 'Status: 200 OK', 'text/plain');


        $this->assertTrue($httpResponse->save($savePath));
        $this->assertSame('Some content', file_get_contents($savePath));

        unlink($savePath);
    }

    /**
     * @covers HttpResponse::save
     */
    public function testSaveWithoutFilename()
    {
        $savePath = 'Test/files/';

        $httpResponse = new HttpResponse(file_get_contents('Test/files/rico.png'), 200, 'Status: 200 OK', 'image/png');

        $this->assertTrue($httpResponse->save($savePath));
        $this->assertSame(file_get_contents('Test/files/rico.png'), file_get_contents($savePath.'b6e71489a0695908a3c3926444674db1.png'));

        unlink($savePath.'b6e71489a0695908a3c3926444674db1.png');
    }

    /**
     * @covers HttpResponse::save
     */
    public function testSaveFail()
    {
        $httpResponse = new HttpResponse('Some content', 200, 'Status: 200 OK', 'text/plain');

        // Not a string tests
        $this->assertFalse($httpResponse->save(450));
        $this->assertFalse($httpResponse->save(array('notgood')));
        $this->assertFalse($httpResponse->save(new \stdClass()));
        $this->assertFalse($httpResponse->save(true));
    }

    /**
     * @covers HttpResponse::save
     */
    public function testSaveWithNewPath()
    {
        $savePath = '../files/new/path/test.txt';

        $httpResponse = new HttpResponse('New content', 200, 'Status: 200 OK', 'text/plain');

        $this->assertTrue($httpResponse->save($savePath));
        $this->assertSame('New content', file_get_contents($savePath));

        unlink($savePath);
        rmdir('../files/new/path/');
        rmdir('../files/new/');
    }

    /**
     * @covers HttpResponse::save
     */
    public function testSaveWithOverride()
    {
        $savePath = '../files/over.txt';

        $httpResponse = new HttpResponse('This is the 1st content.', 200, 'Status: 200 OK', 'text/plain');

        $this->assertTrue($httpResponse->save($savePath));
        $this->assertSame('This is the 1st content.', file_get_contents($savePath));

        $httpResponse->setContent('SECOND CONTENT');

        $this->assertTrue($httpResponse->save($savePath));
        $this->assertSame('SECOND CONTENT', file_get_contents($savePath));

        unlink($savePath);
    }

    /**
     * @covers HttpResponse::save
     * @expectedException Rico\Lib\Crawler\Exception\FileException
     */
    public function testSaveWithoutOverride()
    {
        $httpResponse = new HttpResponse('Override content', 200, 'Status: 200 OK', 'text/plain');

        $httpResponse->save(self::TEST_DIR.self::$strFile, false);
    }
}
