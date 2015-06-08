<?php

namespace Rico\Test\DomParserTest;

use \Rico\Lib\DomParser;

/**
 * Test class for DomParser
 */
class DomParserTest extends \PHPUnit_Framework_TestCase
{
    protected $page1;
    protected $page2;

    protected function setUp()
    {
        $this->page1 = new DomParser(file_get_contents(__DIR__.'/files/page.html'));
        $this->page2 = new DomParser(file_get_contents(__DIR__.'/files/page2.html'));
    }

    public function providerGetFirstValueByTagName()
    {
        return array(
            array(12, false, false), // 0
            array(new \Datetime(), false, false),
            array(array('not ok'), false, false),
            array('dontexist', null, false),
            array('h2', 'Pré-commandez le passe extensions pour The Witcher 3: Wild Hunt, recevez Witcher 1&2', false),
            array('i', '', false), // 5
            array('a', 'First a', false),
            array('*', 'PHP: DOMDocument::loadHTMLFile', '#(PHP\:\ [^:]+\:\:[^\ ]+)#i'),
        );
    }

    public function providerGetFirstValueById()
    {
        return array(
            array(12, false, false), // 0
            array(new \Datetime(), false, false),
            array(array('not ok'), false, false),
            array('dontexist', null, false),
            array('hidden_stuff', 'Are you “Ûtf-8” Àwäre?', false),
            array('big-spot-blur--1', '', false), // 5
            array('example-5335', 'Creating a Document', '#\#1\ (.*)#'),
        );
    }

    public function providerGetFirstValueByClass()
    {
        return array(
            array(123, false, false), // 0
            array(new \stdClass(), false, false),
            array(array('bad'), false, false),
            array('inexistent', null, false),
            array('top-nav-off-cart-count', '{{ cartCount }}', false),
            array('search', '', '#^$#'), // 5
            array('change-language', 'EnglishBrazilian PortugueseChinese (Simplified)FrenchGermanJapaneseKoreanRomanianRussianSpanishTurkishOther', '#Change language: (.*)#'),
        );
    }

    public function providersSlimDomParserById()
    {
        return array(
            array(123, false), // 0
            array(new \stdClass(), false),
            array(array('bad'), false),
            array('inexistent', null),
            array('footer', 'Copyright © 2001-2015 The PHP Group
        My PHP.net
        Contact
        Other PHP.net sites
        Mirror sites
        Privacy policy'),
            array('V51738', '-9'), // 5
            array('empty', ''),
        );
    }

    public function providersSlimDomParserByClass()
    {
        return array(
            array(123, false), // 0
            array(new \stdClass(), false),
            array(array('bad'), false),
            array('inexistent', null),
            array('tbody', '5.4.0
       
        Added options parameter.'),
            array('title', 'Descrição'), // 5
            array('empty', ''),
        );
    }

    /**
	 * @covers DomParser::getFirstValueByTagName()
     * @dataProvider providerGetFirstValueByTagName
	 */
	public function testGetFirstValueByTagName($tagName, $expected, $regexp)
    {
        if ($regexp === false) {
            $this->assertSame($expected, $this->page1->getFirstValueByTagName($tagName));
        } else {
            $this->assertSame($expected, $this->page2->getFirstValueByTagName($tagName, $regexp));
        }
	}

    /**
	 * @covers DomParser::getFirstValueById()
     * @dataProvider providerGetFirstValueById
	 */
	public function testGetFirstValueById($id, $expected, $regexp)
    {
        if ($regexp === false) {
            $this->assertSame($expected, $this->page1->getFirstValueById($id));
        } else {
            $this->assertSame($expected, $this->page2->getFirstValueById($id, $regexp));
        }
	}

    /**
	 * @covers DomParser::getFirstValueByClass()
     * @dataProvider providerGetFirstValueByClass
	 */
	public function testGetFirstValueByClass($class, $expected, $regexp)
    {
        if ($regexp === false) {
            $this->assertSame($expected, $this->page1->getFirstValueByClass($class));
        } else {
            $this->assertSame($expected, $this->page2->getFirstValueByClass($class, $regexp));
        }
	}

    /**
	 * @covers DomParser::slimNodeParserById()
     * @dataProvider providersSlimDomParserById
	 */
	public function testSlimNodeParserById($id, $expected)
    {
        $sharpenedDomParser = $this->page2->slimDomParserById($id);

        if ($expected || $expected === '') {
            $this->assertInstanceOf('\Rico\Lib\DomParser', $sharpenedDomParser);
            $this->assertSame(trim($expected), trim($sharpenedDomParser->getDomBody()->textContent));
        } else {
            $this->assertSame($expected, $sharpenedDomParser);
        }
	}

    /**
	 * @covers DomParser::slimNodeParserByClass()
     * @dataProvider providersSlimDomParserByClass
	 */
	public function testSlimNodeParserByClass($class, $expected)
    {
        $sharpenedDomParser = $this->page2->slimDomParserByClass($class);

        if ($expected || $expected === '') {
            $this->assertInstanceOf('\Rico\Lib\DomParser', $sharpenedDomParser);
            $this->assertSame(trim($expected), trim($sharpenedDomParser->getDomBody()->textContent));
        } else {
            $this->assertSame($expected, $sharpenedDomParser);
        }
	}

    /**
     * @covers DomParser::getDomBody()
     */
    public function testGetDomBody()
    {
        $this->assertInstanceOf('\DomDocument', $this->page1->getDomBody());
        $this->assertInstanceOf('\DomDocument', $this->page2->getDomBody());
    }

    /**
     * @covers DomParser::getDomXPath()
     */
    public function testGetDomXPath()
    {
        $this->assertInstanceOf('\DomXPath', $this->page1->getDomXPath());
        $this->assertInstanceOf('\DomXPath', $this->page2->getDomXPath());
    }
}
