<?php

namespace Rico\Test\FileTest;

use \Rico\Lib\File;

/**
 * Test class for File.
 * Generated by PHPUnit on 2012-02-02 at 16:17:36.
 */
class FileTest extends \PHPUnit_Framework_TestCase {
    const TEST_DIR = 'test/';
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
        for ($i = 1; $i <= 1000; $i++, $str .= mt_rand(0,9));
        file_put_contents(self::TEST_DIR.self::$strFile1, $str);
        file_put_contents(self::TEST_DIR.self::$strFile2, str_repeat(mt_rand(0,9), 5000));
        file_put_contents(self::TEST_DIR.self::$strFile3, str_repeat(mt_rand(0,9), 2048576));
        file_put_contents(self::TEST_DIR.self::$strDir2.'/'.self::$strFile4, str_repeat(mt_rand(0,9), 288576));
    }

    public static function tearDownAfterClass()
    {
        unlink(self::TEST_DIR.self::$strFile1);
        unlink(self::TEST_DIR.self::$strFile2);
        unlink(self::TEST_DIR.self::$strFile3);
        unlink(self::TEST_DIR.self::$strDir2.'/'.self::$strFile4);
        unlink('another/relative.txt');
        unlink('another/absolute.txt');
        rmdir(self::TEST_DIR.'new/path');
        rmdir(self::TEST_DIR.'new');
        rmdir(self::TEST_DIR.self::$strDir);
        rmdir(self::TEST_DIR.self::$strDir2);
        rmdir(self::TEST_DIR);
        rmdir('another');
    }

    /**
     * @covers File::createSymlink
     */
    public function testCreateSymlink()
    {
        $md5 = md5(file_get_contents(self::TEST_DIR.self::$strFile1));
        mkdir('another');

        // Test absolute symlink
        $link = getcwd().'/'.self::TEST_DIR.self::$strFile1;
        $this->assertTrue(File::createSymlink($link, 'another/absolute.txt'));
        $this->assertTrue(file_exists('another/absolute.txt'));
        $this->assertSame('0644', substr(sprintf('%o', fileperms('another/absolute.txt')), -4));
        $this->assertSame($md5, md5(file_get_contents('another/absolute.txt')));

        // Test relative symlink
        $link = '../'.self::TEST_DIR.self::$strFile1;
        $this->assertTrue(File::createSymlink($link, 'another/relative.txt'));
        $this->assertTrue(file_exists('another/relative.txt'));
        $this->assertSame('0644', substr(sprintf('%o', fileperms('another/relative.txt')), -4));
        $this->assertSame($md5, md5(file_get_contents('another/relative.txt')));

        // Test symlink creation over a file that already exists
        $md5 = md5(file_get_contents(self::TEST_DIR.self::$strFile2));
        $link = '../'.self::TEST_DIR.self::$strFile2;
        $this->assertTrue(File::createSymlink($link, 'another/relative.txt'));
        $this->assertTrue(file_exists('another/relative.txt'));
        $this->assertSame('0644', substr(sprintf('%o', fileperms('another/relative.txt')), -4));
        $this->assertSame($md5, md5(file_get_contents('another/relative.txt')));

        // Test symlink creation with a non-existing file
        $link = 'dontexist.file';
        $this->assertFalse(File::createSymlink($link, self::TEST_DIR.self::$strFile2));
        $this->assertFalse(file_exists($link));
    }

    /**
     * @covers File::listDirectory
     */
    public function testListDirectory()
    {
        $this->assertEquals(array('directory' => array(self::$strDir, self::$strDir2), 'file' => array(self::$strFile1, self::$strFile2, self::$strFile3)), File::listDirectory(self::TEST_DIR));
        $this->assertEquals(array('directory' => array(self::$strDir, self::$strDir2), 'file' => array(self::$strFile1, self::$strFile2, self::$strFile3)), File::listDirectory(self::TEST_DIR, File::LIST_DIRECTORY_BOTH));
        $this->assertEquals(array('file' => array(self::$strFile4)), File::listDirectory(self::TEST_DIR.self::$strDir2));
        $this->assertEquals(array(self::$strDir, self::$strDir2), File::listDirectory(self::TEST_DIR, File::LIST_DIRECTORY_DIR_ONLY));
        $this->assertEquals(array(self::$strFile1, self::$strFile2, self::$strFile3), File::listDirectory(self::TEST_DIR, File::LIST_DIRECTORY_FILE_ONLY));
    }

    /**
     * @covers File::createPath
     */
    public function testCreatePath()
    {
        // Test simple path creation
        $this->assertTrue(File::createPath(self::TEST_DIR.'new/path'));
        $this->assertTrue(file_exists(self::TEST_DIR.'new/path'));
        $this->assertSame('0755', substr(sprintf('%o', fileperms(self::TEST_DIR.'new/path')), -4));

        // Test path creation over a file that already exists
        $this->assertFalse(File::createPath(self::TEST_DIR.self::$strFile2));
        $this->assertFalse(is_dir(self::TEST_DIR.self::$strFile2));
    }

    /**
     * @covers File::count
     */
    public function testCount()
    {
        $this->assertSame(File::count('nonexistingfile.txt'), false);
        $this->assertSame(File::count(__DIR__.'/testFiles/empty.list'), 0);
        $this->assertSame(File::count(__DIR__.'/testFiles/empty.list', true), 0);
        $this->assertSame(File::count(__DIR__.'/testFiles/long.list'), 48508);
        $this->assertSame(File::count(__DIR__.'/testFiles/long.list', true), 48508);
        $this->assertSame(File::count(__DIR__.'/testFiles/large.list'), 84);
        $this->assertSame(File::count(__DIR__.'/testFiles/large.list', true), 87);
    }

}
