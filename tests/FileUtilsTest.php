<?php

declare(strict_types=1);

namespace Rico\Test;

use Rico\Slib\FileUtils as StaticFileUtils;
use Rico\Lib\FileUtils;

class FileUtilsTest extends RicoTestCase
{
    /**
     * @var FileUtils
     */
    private $fileUtils;

    public function setUp()
    {
        $this->fileUtils = new FileUtils();
    }

    public function tearDown()
    {
      file_put_contents(__DIR__.'/testFiles/empty.list', '');
    }

    public function providerAddLineTypeErrors()
    {
        return [
            [new \stdClass(), 'test'],
            [-4.45, 'test'],
            [3465, 'test'],
            [false, 'test'],
            [null, 'test'],
            [['ok'], 'test'],
            [__DIR__.'/testFiles/empty.list', false],
            [__DIR__.'/testFiles/empty.list', true],
            [__DIR__.'/testFiles/empty.list', 32432],
            [__DIR__.'/testFiles/empty.list', 3.3],
            [__DIR__.'/testFiles/empty.list', new \stdClass()],
            [__DIR__.'/testFiles/empty.list', ['test']],
            [73.4, false],
            [true, null],
        ];
    }

    public function providerExtractExtension()
    {
        return [
            [0, null],
            [true, null],
            [null, null],
            [-4.45, null],
            [3465, null],
            [['ok'], null],
            [__DIR__.'/testFiles/empty.list', 'list'],
            ['myfile.extension', 'extension'],
            ['test.double.ext', 'ext'],
            ['/multiple/dir/noext', ''],
            ['https://somesite.com/my_file.pdf', 'pdf'],
        ];
    }

    //--- TESTS

    /**
     * @covers \StaticFileUtils::addLine
     */
    public function testAddLineErrors()
    {
        // Null tests
        $this->assertSame(false, StaticFileUtils::addLine('nonexistingfile.txt', 'test'));
        $this->assertSame(false, $this->fileUtils->addLine('nonexistingfile.txt', 'test'));
        $this->assertSame(false, StaticFileUtils::addLine('nonfile', '23432'));
        $this->assertSame(false, $this->fileUtils->addLine('nonfile', '23432'));
    }

    /**
     * @covers \StaticFileUtils::addLine
     * @dataProvider providerAddLineTypeErrors
     * @expectedException \TypeError
     */
    public function testAddLineTypeErrors($file, $line)
    {
        StaticFileUtils::addLine($file, $line);
        $this->fileUtils->addLine($file, $line);
    }

    /**
     * @covers \StaticFileUtils::addLine
     */
    public function testAddLine()
    {
        // Test new line
        $this->assertSame(true, $this->fileUtils->addLine(__DIR__.'/testFiles/empty.list', 'Coucou'));
        $this->assertSame(1, StaticFileUtils::count(__DIR__.'/testFiles/empty.list'));
        $this->assertSame(1, $this->fileUtils->count(__DIR__.'/testFiles/empty.list'));
        $this->assertSame('Coucou'.PHP_EOL, file_get_contents(__DIR__.'/testFiles/empty.list'));

        // Test second line
        $this->assertSame(true, $this->fileUtils->addLine(__DIR__.'/testFiles/empty.list', 'Another'));
        $this->assertSame(2, StaticFileUtils::count(__DIR__.'/testFiles/empty.list'));
        $this->assertSame(2, $this->fileUtils->count(__DIR__.'/testFiles/empty.list'));
        $this->assertSame('Coucou'.PHP_EOL.'Another'.PHP_EOL, file_get_contents(__DIR__.'/testFiles/empty.list'));
    }

    /**
     * @covers \StaticFileUtils::addLine
     */
    public function testAddLineDuplicate()
    {
        // Test duplicate line
        $this->assertSame(11, StaticFileUtils::count(__DIR__.'/testFiles/short.list'));
        $this->assertSame(11, $this->fileUtils->count(__DIR__.'/testFiles/short.list'));
        $this->assertSame(false, StaticFileUtils::addLine(__DIR__.'/testFiles/short.list', '295837'));
        $this->assertSame(false, $this->fileUtils->addLine(__DIR__.'/testFiles/short.list', '295837'));
        $this->assertSame(11, StaticFileUtils::count(__DIR__.'/testFiles/short.list'));
        $this->assertSame(11, $this->fileUtils->count(__DIR__.'/testFiles/short.list'));
    }

    /**
     * @covers \StaticFileUtils::count
     */
    public function testCount()
    {
        $this->assertSame(0, StaticFileUtils::count('nonexistingfile.txt'));
        $this->assertSame(0, StaticFileUtils::count(__DIR__.'/testFiles/empty.list'));
        $this->assertSame(0, StaticFileUtils::count(__DIR__.'/testFiles/empty.list', true));
        $this->assertSame(48508, StaticFileUtils::count(__DIR__.'/testFiles/long.list'));
        $this->assertSame(48508, StaticFileUtils::count(__DIR__.'/testFiles/long.list', true));
        $this->assertSame(84, StaticFileUtils::count(__DIR__.'/testFiles/large.list'));
        $this->assertSame(87, StaticFileUtils::count(__DIR__.'/testFiles/large.list', true));
    }

    /**
     * @covers \StringUtils::extractExtension
     * @dataProvider providerExtractExtension
     */
    public function testExtractExtension($value, $expected)
    {
        $this->standardStaticTest(StaticFileUtils::class, 'extractExtension', [$value], $expected);
        $this->standardTest($this->fileUtils, 'extractExtension', [$value], $expected);
    }
}
