<?php

declare(strict_types=1);

namespace Rico\Test;

use Rico\Slib\FileUtils;

class FileUtilsTest extends \PHPUnit_Framework_TestCase
{
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

    /**
     * @covers FileUtils::count
     */
    public function testCount()
    {
        $this->assertSame(false, FileUtils::count('nonexistingfile.txt'));
        $this->assertSame(0, FileUtils::count(__DIR__.'/testFiles/empty.list'));
        $this->assertSame(0, FileUtils::count(__DIR__.'/testFiles/empty.list', true));
        $this->assertSame(48508, FileUtils::count(__DIR__.'/testFiles/long.list'));
        $this->assertSame(48508, FileUtils::count(__DIR__.'/testFiles/long.list', true));
        $this->assertSame(84, FileUtils::count(__DIR__.'/testFiles/large.list'));
        $this->assertSame(87, FileUtils::count(__DIR__.'/testFiles/large.list', true));
    }

    /**
     * @covers FileUtils::addLine
     */
    public function testAddLineErrors()
    {
        // Null tests
        $this->assertSame(null, FileUtils::addLine('nonexistingfile.txt', 'test'));
        $this->assertSame(null, FileUtils::addLine('nonfile', '23432'));
    }

    /**
     * @covers FileUtils::addLine
     * @dataProvider providerAddLineTypeErrors
     * @expectedException TypeError
     */
    public function testAddLineTypeErrors($file, $line)
    {
        FileUtils::addLine($file, $line);
    }

    /**
     * @covers FileUtils::addLine
     */
    public function testAddLine()
    {
        // Test new line
        $this->assertSame(true, FileUtils::addLine(__DIR__.'/testFiles/empty.list', 'Coucou'));
        $this->assertSame(1, FileUtils::count(__DIR__.'/testFiles/empty.list'));
        $this->assertSame('Coucou'.PHP_EOL, file_get_contents(__DIR__.'/testFiles/empty.list'));

        // Test second line
        $this->assertSame(true, FileUtils::addLine(__DIR__.'/testFiles/empty.list', 'Another'));
        $this->assertSame(2, FileUtils::count(__DIR__.'/testFiles/empty.list'));
        $this->assertSame('Coucou'.PHP_EOL.'Another'.PHP_EOL, file_get_contents(__DIR__.'/testFiles/empty.list'));

        fclose(fopen(__DIR__.'/testFiles/empty.list', 'w+'));
    }

    /**
     * @covers FileUtils::addLine
     */
    public function testAddLineDuplicate()
    {
        // Test duplicate line
        $this->assertSame(11, FileUtils::count(__DIR__.'/testFiles/short.list'));
        $this->assertSame(false, FileUtils::addLine(__DIR__.'/testFiles/short.list', '295837'));
        $this->assertSame(11, FileUtils::count(__DIR__.'/testFiles/short.list'));
    }
}
