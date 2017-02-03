<?php

declare(strict_types=1);

namespace Rico\Test;

use Rico\Slib\FilesystemUtils;

class FilesystemUtilsTest extends RicoTestCase
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
        mkdir('another');
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
     * @covers FilesystemUtils::createSymlink
     */
    public function testCreateSymlinkAbsolute()
    {
        // Test absolute symlink
        $md5 = md5(file_get_contents(self::TEST_DIR.self::$strFile1));
        $link = getcwd().'/'.self::TEST_DIR.self::$strFile1;
        $this->assertTrue(FilesystemUtils::createSymlink($link, 'another/absolute.txt'));
        $this->assertTrue(file_exists('another/absolute.txt'));
        $this->assertSame($md5, md5(file_get_contents('another/absolute.txt')));
    }

    /**
     * @covers FilesystemUtils::createSymlink
     */
    public function testCreateSymlinkRelative()
    {
        // Test relative symlink
        $md5 = md5(file_get_contents(self::TEST_DIR.self::$strFile1));
        $link = '../'.self::TEST_DIR.self::$strFile1;
        $this->assertTrue(FilesystemUtils::createSymlink($link, 'another/relative.txt'));
        $this->assertTrue(file_exists('another/relative.txt'));
        $this->assertSame($md5, md5(file_get_contents('another/relative.txt')));
    }

    /**
     * @covers FilesystemUtils::createSymlink
     */
    public function testCreateSymlinkOverride()
    {
        // Test symlink creation over a file that already exists
        $md5 = md5(file_get_contents(self::TEST_DIR.self::$strFile2));
        $link = '../'.self::TEST_DIR.self::$strFile2;
        $this->assertTrue(FilesystemUtils::createSymlink($link, 'another/relative.txt'));
        $this->assertTrue(file_exists('another/relative.txt'));
        $this->assertSame($md5, md5(file_get_contents('another/relative.txt')));
    }

    /**
     * @covers FilesystemUtils::createSymlink
     */
    public function testCreateSymlinkNotExist()
    {
        $link = 'dontexist.file';
        $this->assertFalse(FilesystemUtils::createSymlink($link, self::TEST_DIR.self::$strFile2));
        $this->assertFalse(file_exists($link));
    }

    /**
     * @covers FilesystemUtils::listDirectory
     */
    public function testListDirectory()
    {
        $this->assertEquals(['directory' => [self::$strDir, self::$strDir2], 'file' => [self::$strFile1, self::$strFile2, self::$strFile3]], FilesystemUtils::listDirectory(self::TEST_DIR));
        $this->assertEquals(['directory' => [self::$strDir, self::$strDir2], 'file' => [self::$strFile1, self::$strFile2, self::$strFile3]], FilesystemUtils::listDirectory(self::TEST_DIR, FilesystemUtils::LIST_DIRECTORY_BOTH));
        $this->assertEquals(['file' => [self::$strFile4]], FilesystemUtils::listDirectory(self::TEST_DIR.self::$strDir2));
        $this->assertEquals([self::$strDir, self::$strDir2], FilesystemUtils::listDirectory(self::TEST_DIR, FilesystemUtils::LIST_DIRECTORY_DIR_ONLY));
        $this->assertEquals([self::$strFile1, self::$strFile2, self::$strFile3], FilesystemUtils::listDirectory(self::TEST_DIR, FilesystemUtils::LIST_DIRECTORY_FILE_ONLY));
    }

    /**
     * @covers FilesystemUtils::createPath
     */
    public function testCreatePath()
    {
        // Test simple path creation
        $this->assertTrue(FilesystemUtils::createPath(self::TEST_DIR.'new/path'));
        $this->assertTrue(file_exists(self::TEST_DIR.'new/path'));
        $this->assertSame('0755', substr(sprintf('%o', fileperms(self::TEST_DIR.'new/path')), -4));

        // Test path creation over a file that already exists
        $this->assertFalse(FilesystemUtils::createPath(self::TEST_DIR.self::$strFile2));
        $this->assertFalse(is_dir(self::TEST_DIR.self::$strFile2));
    }
}
