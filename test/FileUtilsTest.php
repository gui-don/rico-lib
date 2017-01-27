<?php

declare(strict_types=1);

namespace Rico\Test;

use Rico\Slib\FileUtils;

class FileUtilsTest extends \PHPUnit_Framework_TestCase
{
    const TEST_DIR = 'current_test/';
    public static $strFile1 = 'file1.test';
    public static $strFile2 = 'file2';
    public static $strFile3 = 'Spéci@l.rtf';
    public static $strFile4 = 'file4.txt';
    public static $strDir = 'dir1';
    public static $strDir2 = 'dir2';

    public static function setUpBeforeClass()
    {
        mkdir(self::TEST_DIR);
        mkdir(self::TEST_DIR.self::$strDir);
        mkdir(self::TEST_DIR.self::$strDir2);
        $str = '';
        for ($i = 1; $i <= 1000; $i++, $str .= mt_rand(0, 9));
        file_put_contents(self::TEST_DIR.self::$strFile1, $str);
        file_put_contents(self::TEST_DIR.self::$strFile2, str_repeat((string) mt_rand(0, 9), 5000));
        file_put_contents(self::TEST_DIR.self::$strFile3, str_repeat((string) mt_rand(0, 9), 2048576));
        file_put_contents(self::TEST_DIR.self::$strDir2.'/'.self::$strFile4, str_repeat((string) mt_rand(0, 9), 288576));
    }

    public static function tearDownAfterClass()
    {
        unlink(self::TEST_DIR.self::$strFile1);
        unlink(self::TEST_DIR.self::$strFile2);
        unlink(self::TEST_DIR.self::$strFile3);
        unlink(self::TEST_DIR.self::$strDir2.'/'.self::$strFile4);
        unlink('another/relative.txt');
        unlink('another/absolute.txt');
        fclose(fopen(__DIR__.'/testFiles/empty.list', 'w+'));
        rmdir(self::TEST_DIR.'new/path');
        rmdir(self::TEST_DIR.'new');
        rmdir(self::TEST_DIR.self::$strDir);
        rmdir(self::TEST_DIR.self::$strDir2);
        rmdir(self::TEST_DIR);
        rmdir('another');
    }

    /**
     * @covers FileUtils::createSymlink
     */
    public function testCreateSymlink()
    {
        $md5 = md5(file_get_contents(self::TEST_DIR.self::$strFile1));
        mkdir('another');

        // Test absolute symlink
        $link = getcwd().'/'.self::TEST_DIR.self::$strFile1;
        $this->assertTrue(FileUtils::createSymlink($link, 'another/absolute.txt'));
        $this->assertTrue(file_exists('another/absolute.txt'));
        $this->assertSame($md5, md5(file_get_contents('another/absolute.txt')));

        // Test relative symlink
        $link = '../'.self::TEST_DIR.self::$strFile1;
        $this->assertTrue(FileUtils::createSymlink($link, 'another/relative.txt'));
        $this->assertTrue(file_exists('another/relative.txt'));
        $this->assertSame($md5, md5(file_get_contents('another/relative.txt')));

        // Test symlink creation over a file that already exists
        $md5 = md5(file_get_contents(self::TEST_DIR.self::$strFile2));
        $link = '../'.self::TEST_DIR.self::$strFile2;
        $this->assertTrue(FileUtils::createSymlink($link, 'another/relative.txt'));
        $this->assertTrue(file_exists('another/relative.txt'));
        $this->assertSame($md5, md5(file_get_contents('another/relative.txt')));

        // Test symlink creation with a non-existing file
        $link = 'dontexist.file';
        $this->assertFalse(FileUtils::createSymlink($link, self::TEST_DIR.self::$strFile2));
        $this->assertFalse(file_exists($link));
    }

    /**
     * @covers FileUtils::listDirectory
     */
    public function testListDirectory()
    {
        $this->assertEquals(['directory' => [self::$strDir, self::$strDir2], 'file' => [self::$strFile1, self::$strFile2, self::$strFile3]], FileUtils::listDirectory(self::TEST_DIR));
        $this->assertEquals(['directory' => [self::$strDir, self::$strDir2], 'file' => [self::$strFile1, self::$strFile2, self::$strFile3]], FileUtils::listDirectory(self::TEST_DIR, FileUtils::LIST_DIRECTORY_BOTH));
        $this->assertEquals(['file' => [self::$strFile4]], FileUtils::listDirectory(self::TEST_DIR.self::$strDir2));
        $this->assertEquals([self::$strDir, self::$strDir2], FileUtils::listDirectory(self::TEST_DIR, FileUtils::LIST_DIRECTORY_DIR_ONLY));
        $this->assertEquals([self::$strFile1, self::$strFile2, self::$strFile3], FileUtils::listDirectory(self::TEST_DIR, FileUtils::LIST_DIRECTORY_FILE_ONLY));
    }

    /**
     * @covers FileUtils::createPath
     */
    public function testCreatePath()
    {
        // Test simple path creation
        $this->assertTrue(FileUtils::createPath(self::TEST_DIR.'new/path'));
        $this->assertTrue(file_exists(self::TEST_DIR.'new/path'));
        $this->assertSame('0755', substr(sprintf('%o', fileperms(self::TEST_DIR.'new/path')), -4));

        // Test path creation over a file that already exists
        $this->assertFalse(FileUtils::createPath(self::TEST_DIR.self::$strFile2));
        $this->assertFalse(is_dir(self::TEST_DIR.self::$strFile2));
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
