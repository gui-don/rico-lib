<?php

declare(strict_types=1);

namespace Rico\Test;

use Rico\Slib\FilesystemUtils as StaticFilesystemUtils;
use Rico\Lib\FilesystemUtils;

class FilesystemUtilsTest extends RicoTestCase
{
    const TEST_DIR = 'current_test/';
    public static $strFile1 = 'file1.test';
    public static $strFile2 = 'file2';
    public static $strFile3 = 'Spéci@l.rtf';
    public static $strFile4 = 'file4.txt';
    public static $strDir = 'dir1';
    public static $strDir2 = 'dir2';

    /**
     * @var FilesystemUtils
     */
    private $filesystemUtils;

    public function setUp(): void
    {
        $this->filesystemUtils = new FilesystemUtils();
    }

    public static function setUpBeforeClass(): void
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

    public static function tearDownAfterClass(): void
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
     * @covers Rico\Lib\FilesystemUtils
     * @covers Rico\SLib\FilesystemUtils
     */
    public function testCreateSymlinkAbsolute()
    {
        // Test absolute symlink
        $md5 = md5(file_get_contents(self::TEST_DIR.self::$strFile1));
        $link = getcwd().'/'.self::TEST_DIR.self::$strFile1;
        $this->assertTrue($this->filesystemUtils->createSymlink($link, 'another/absolute.txt'));
        $this->assertTrue(file_exists('another/absolute.txt'));
        $this->assertSame($md5, md5(file_get_contents('another/absolute.txt')));
    }

    /**
     * @covers Rico\Lib\FilesystemUtils
     * @covers Rico\SLib\FilesystemUtils
     */
    public function testCreateSymlinkRelative()
    {
        // Test relative symlink
        $md5 = md5(file_get_contents(self::TEST_DIR.self::$strFile1));
        $link = '../'.self::TEST_DIR.self::$strFile1;
        $this->assertTrue($this->filesystemUtils->createSymlink($link, 'another/relative.txt'));
        $this->assertTrue(file_exists('another/relative.txt'));
        $this->assertSame($md5, md5(file_get_contents('another/relative.txt')));
    }

    /**
     * @covers Rico\Lib\FilesystemUtils
     * @covers Rico\SLib\FilesystemUtils
     */
    public function testCreateSymlinkOverride()
    {
        // Test symlink creation over a file that already exists
        $md5 = md5(file_get_contents(self::TEST_DIR.self::$strFile2));
        $link = '../'.self::TEST_DIR.self::$strFile2;
        $this->assertTrue($this->filesystemUtils->createSymlink($link, 'another/relative.txt'));
        $this->assertTrue(file_exists('another/relative.txt'));
        $this->assertSame($md5, md5(file_get_contents('another/relative.txt')));
    }

    /**
     * @covers Rico\Lib\FilesystemUtils
     * @covers Rico\SLib\FilesystemUtils
     */
    public function testCreateSymlinkNotExist()
    {
        $link = 'dontexist.file';
        $this->assertFalse($this->filesystemUtils->createSymlink($link, self::TEST_DIR.self::$strFile2));
        $this->assertFalse(file_exists($link));
    }

    /**
     * @covers Rico\Lib\FilesystemUtils
     * @covers Rico\SLib\FilesystemUtils
     * @TODO This test depends the other -> BAD | to refactor so it does not anymore.
     */
    public function testListDirectory()
    {
        $this->assertNull($this->filesystemUtils->listDirectory("does_not_exist"));
        $this->assertNull($this->filesystemUtils->listDirectory(self::TEST_DIR, 5));
        $this->assertEqualsCanonicalizing([self::$strDir, self::$strDir2, self::$strFile1, self::$strFile2, self::$strFile3], $this->filesystemUtils->listDirectory(self::TEST_DIR));
        $this->assertEqualsCanonicalizing([self::$strDir, self::$strDir2, self::$strFile1, self::$strFile2, self::$strFile3], $this->filesystemUtils->listDirectory(self::TEST_DIR, StaticFilesystemUtils::LIST_DIRECTORY_BOTH));
        $this->assertEqualsCanonicalizing([self::$strFile4], $this->filesystemUtils->listDirectory(self::TEST_DIR.self::$strDir2));
        $this->assertEqualsCanonicalizing([self::$strDir, self::$strDir2], $this->filesystemUtils->listDirectory(self::TEST_DIR, StaticFilesystemUtils::LIST_DIRECTORY_DIR_ONLY));
        $this->assertEqualsCanonicalizing([self::$strFile1, self::$strFile2, self::$strFile3], $this->filesystemUtils->listDirectory(self::TEST_DIR, StaticFilesystemUtils::LIST_DIRECTORY_FILE_ONLY));
    }

    /**
     * @covers Rico\Lib\FilesystemUtils
     * @covers Rico\SLib\FilesystemUtils
     */
    public function testCreatePath()
    {
        // Test simple path creation
        $this->assertTrue($this->filesystemUtils->createPath(self::TEST_DIR.'new/path'));
        $this->assertTrue(file_exists(self::TEST_DIR.'new/path'));
        $this->assertSame('0755', mb_substr(sprintf('%o', fileperms(self::TEST_DIR.'new/path')), -4));

        // Test path creation over a file that already exists
        $this->assertFalse($this->filesystemUtils->createPath(self::TEST_DIR.self::$strFile2));
        $this->assertFalse(is_dir(self::TEST_DIR.self::$strFile2));
    }
}
