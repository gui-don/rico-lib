<?php

namespace Rico\Test\CheckerTest;

use \Rico\Lib\Checker;

class CheckerTest extends \PHPUnit_Framework_TestCase
{
    public function providerIsPositiveInt()
    {
        return array(
            array('test', false), // 0
            array(true, false),
            array('2e4', false),
            array(-47.12, false),
            array(0, false),
            array(12.5, false), // 5
            array('471845', true),
            array(7484, true),
            array(899, true),
            array(125, true)
        );
    }

    public function providerIsNumber()
    {
        return array(
            array('test', false), // 0
            array(false, false),
            array(null, false),
            array('2e4', false),
            array('a35', false),
            array('-187417840', true), // 5
            array('471845', true),
            array(-47.12, true),
            array(7484, true),
            array(0, true),
            array(0.1818, true), // 10
            array('0.14.3', false),
        );
    }

    public function providerIsHexadecimal()
    {
        return array(
            array('000000', true), // 0
            array(000000, false),
            array('111111', true),
            array('eAf0e6', true),
            array('eAf0e656', false),
            array('AGCDEA', false), // 5
            array('EEE654', true),
            array(222222, false)
        );
    }

    public function providerIsURL()
    {
        return array(
            array('', false), // 0
            array('work', false),
            array('array(5)', false),
            array('326a', false),
            array('http://google.com', true),
            array('97/:google.com', false), // 5
            array('les-marchands.fr', false),
            array('www.yahoo.co.jp', false),
            array('http://fr.wikipedia.org/', true),
            array('https://fr.wikipedia.org/wiki/Uniform_Resource_Locator', true),
            array('https://www.youtube.com/watch?v=TjkVXzmsP78&feature=related', true), // 10
            array('http://bit.ly/xZolh7', true),
            array('ftp://ftp.rfc-editor.org/in-notes/rfc2396.txt', true),
            array('http://local.mysmartaudit.net/vokto/815/secretaire-delegue-general-etes-vous-satisfait-de-vos-outils-numeriques-dans-l-exercice-de-vos-fonctions/?cpid=test&reset=2', true),
            array('http://mysmartaudit.net/link4lead/316/que-vaut-votre-generation-de-leads/', true),
            array('http://mysmartaudit.net/link4lead/316/que-vaut-votre-generation-de-leads/', true),
            array('http://local.mysmartaudit.net/dell-sonicwall/888/ファイアウォールに不満ですか/', true), // 15
            array('http://local.mysmartaudit.net/ファイアウ/888/ォールに不満ですか/', true),
            array('http://test.comune url?', false),
            array('http://उदाहरण.परीक्षा', true),
            array('///a', false),
            array('http://foo.bar?q=Spaces should be encoded', false), // 20
            array('http://foo.com/blah_blah_(wikipedia)_(again)', true),
            array('http://10.1.1.255/', false),
            array('http://.www.foo.bar/', false)
        );
    }

    public function providerIsEmail()
    {
        return array(
            array('', false), // 0
            array('notok', false),
            array('test@con', false),
            array('abc.@example.com', false),
            array('A@b@c@example.com', false),
            array('john.doe@example.bullshit', false), // 5
            array('john.doe@truc.fr', true),
            array('niceandsimple@example.com', true),
            array('simplewith+symbol@example.info', true),
            array('less.common@www.example.net', true),
            array('a.little.more.unusual@dept.example.pro', true), // 10
            array('jmb@link4lead.com', true),
            array('Marc.Pol@plm.com', true),
            array('MARC.POL@PLM.COM', false),
            array('jojo_admin@augure.com', true)
        );
    }

    public function providerIsPhoneNumber()
    {
        return array(
            array('', false), // 0
            array('0125', false),
            array('12458', false),
            array('work', false),
            array('+ 33 06 14 03 77 35', true),
            array('+ 33 0614037735', true), // 5
            array('+ 330614037735', true),
            array('+212 (64)) 0568132', false),
            array('+33 2 45 60 12 46', true),
            array('1-510-495-1428', true),
            array('0415487631', true), // 10
            array('(11)1234-5678', true),
            array('01.46.70.89.12', true),
            array('+212 640568132', true),
            array('+86.145.899.1024', true),
            array('(+ 33)0598745123', true), // 15
            array('(+3300)0598745123', true)
        );
    }

    /**
     * @covers Checker::isPositiveInt
     * @dataProvider providerIsPositiveInt
     */
    public function testIsPositiveInt($value, $expected)
    {
        $this->assertSame($expected, Checker::isPositiveInt($value));
    }

    /**
     * @covers Checker::isNumber
     * @dataProvider providerIsNumber
     */
    public function testIsNumber($strValue, $expected)
    {
        $this->assertSame($expected, Checker::isNumber($strValue));
    }

    /**
     * @covers Checker::isHexadecimal
     * @dataProvider providerIsHexadecimal
     */
    public function testIsHexadecimal($value, $expected)
    {
        $this->assertSame($expected, Checker::isHexadecimal($value));
    }

    /**
     * @covers Checker::isUrl
     * @dataProvider providerIsURL
     */
    public function testIsURL($value, $expected)
    {
        $this->assertSame($expected, Checker::isURL($value));
    }

    /**
     * @covers Checker::isEmail
     * @dataProvider providerIsEmail
     */
    public function testIsEmail($value, $expected)
    {
        $this->assertSame($expected, Checker::isEmail($value));
    }

    /**
     * @covers Checker::isPhoneNumber
     * @dataProvider providerIsPhoneNumber
     */
    public function testIsPhoneNumber($value, $expected)
    {
        $this->assertSame($expected, Checker::isPhoneNumber($value));
    }
}
