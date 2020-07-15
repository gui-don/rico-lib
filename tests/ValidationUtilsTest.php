<?php

declare(strict_types=1);

namespace Rico\Test;

use Rico\Slib\ValidationUtils as StaticValidationUtils;
use Rico\Lib\ValidationUtils;

class ValidationUtilsTest extends RicoTestCase
{
    /**
     * @var ValidationUtils
     */
    private $validationUtils;

    public function setUp(): void
    {
        $this->validationUtils = new ValidationUtils();
    }

    public function providerIsEmail()
    {
        return [
            ['', false], // 0
            ['notok', false],
            ['test@con', false],
            ['abc.@example.com', false],
            ['A@b@c@example.com', false],
            ['john.doe@example.bullshit', false], // 5
            ['john.doe@truc.fr', true],
            ['niceandsimple@example.com', true],
            ['simplewith+symbol@example.info', true],
            ['less.common@www.example.net', true],
            ['a.little.more.unusual@dept.example.pro', true], // 10
            ['jmb@link4lead.com', true],
            ['Marc.Pol@plm.com', true],
            ['MARC.POL@PLM.COM', false],
            ['jojo_admin@augure.com', true],
            [23, false], // 15
            [new \stdClass(), false],
        ];
    }

    public function providerIsHexadecimal()
    {
        return [
            ['000000', true], // 0
            [000000, false],
            ['111111', true],
            ['eAf0e6', true],
            ['eAf0e656', false],
            ['AGCDEA', false], // 5
            ['EEE654', true],
            [222222, false],
        ];
    }

    public function providerIsIp()
    {
        return [
            [-4.5, false], // 0
            [22, false],
            [[5], false],
            [new \stdClass(), false],
            [true, false],
            ['97/:google.com', false], // 5
            ['les-marchands.fr', false],
            ['3.3.3.', false],
            ['10.0.0.a', false],
            ['random', false],
            ['22.2222.22.2', false], // 10
            ['4.5.6.7.8', false],
            ['5..6.123', false],
            ['5.12.34', false],
            ['260.0.0.0', false],
            ['192.168.23.15/24', false], // 15
            ['2001:db8:3:4:5::192.0.2.33', false],
            ['1:2:3:4:5:6:7:8::', false],
            [':1:2:3:4:5:6:7:8', false],
            ['1:2:3:4:5:6:7:8:', false],
            ['1:2:3:4:5:6:7:8:9', false], // 20
            ['::_', false],
            ['0.0.0.0', false],
            ['0.42.42.42', false],
            ['000.30.23.56', false],
            ['127.0.0.1', true], // 25
            ['127.000.00.001', true],
            ['192.168.255.255', true],
            ['10.0.0.1', true],
            ['75.253.133.52', true],
            ['21DA:D3:0:2F3B:2AA:FF:FE28:9C5A', true], // 30
            ['21DA:00D3:0000:2F3B:02AA:00FF:FE28:9C5A', true],
            ['ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', true],
            ['1:2:3:4:5:6:7:8', true],
            ['1::5:6:7:8', true],
            ['fe80::7:8%eth0', true], // 35
            ['::ffff:255.255.255.255', true],
            ['255.255.255.255', true],
            ['050.000.001.200', true],
        ];
    }

    public function providerIsNumber()
    {
        return [
            ['test', false], // 0
            [false, false],
            [null, false],
            ['2e4', false],
            ['a35', false],
            ['-187417840', true], // 5
            ['471845', true],
            [-47.12, true],
            [7484, true],
            [0, true],
            [0.1818, true], // 10
            ['0.14.3', false],
        ];
    }

    public function providerIsPhoneNumber()
    {
        return [
            ['', false], // 0
            ['0125', false],
            ['12458', false],
            ['work', false],
            ['+ 33 06 14 03 77 35', true],
            ['+ 33 0614037735', true], // 5
            ['+ 330614037735', true],
            ['+212 (64)) 0568132', false],
            ['+33 2 45 60 12 46', true],
            ['1-510-495-1428', true],
            ['0415487631', true], // 10
            ['(11)1234-5678', true],
            ['01.46.70.89.12', true],
            ['+212 640568132', true],
            ['+86.145.899.1024', true],
            ['(+ 33)0598745123', true], // 15
            ['(+3300)0598745123', true],
            [435.3, null],
            [200, null],
            [new \stdClass(), null],
            [false, null], //20
        ];
    }

    public function providerIsPositiveInt()
    {
        return [
            ['test', false], // 0
            [true, false],
            ['2e4', false],
            [-47.12, false],
            [0, false],
            [12.5, false], // 5
            ['471845', true],
            [7484, true],
            [899, true],
            [125, true],
        ];
    }

    public function providerIsURL()
    {
        return [
            ['', false], // 0
            ['work', false],
            ['array(5)', false],
            ['326a', false],
            ['http://google.com', true],
            ['97/:google.com', false], // 5
            ['les-marchands.fr', false],
            ['www.yahoo.co.jp', false],
            ['http://fr.wikipedia.org/', true],
            ['https://fr.wikipedia.org/wiki/Uniform_Resource_Locator', true],
            ['https://www.youtube.com/watch?v=TjkVXzmsP78&feature=related', true], // 10
            ['http://bit.ly/xZolh7', true],
            ['ftp://ftp.rfc-editor.org/in-notes/rfc2396.txt', true],
            ['http://local.mysmartaudit.net/vokto/815/secretaire-delegue-general-etes-vous-satisfait-de-vos-outils-numeriques-dans-l-exercice-de-vos-fonctions/?cpid=test&reset=2', true],
            ['http://mysmartaudit.net/link4lead/316/que-vaut-votre-generation-de-leads/', true],
            ['http://mysmartaudit.net/link4lead/316/que-vaut-votre-generation-de-leads/', true], // 15
            ['http://local.mysmartaudit.net/dell-sonicwall/888/ファイアウォールに不満ですか/', true],
            ['http://local.mysmartaudit.net/ファイアウ/888/ォールに不満ですか/', true],
            ['http://test.comune url?', false],
            ['http://उदाहरण.परीक्षा', true],
            ['///a', false], // 20
            ['http://foo.bar?q=Spaces should be encoded', false],
            ['http://foo.com/blah_blah_(wikipedia)_(again)', true],
            ['http://10.1.1.255/', false],
            ['http://.www.foo.bar/', false],
            ['http://a.b-c.club', true], // 25
            ['http://127.0.0.1/server.php', true],
            ['https://phpunit.de/manual/current/en/test-doubles.html', true],
        ];
    }

    public function providerIsURLMagnet()
    {
        return [
            ['test', false], // 0
            [true, false],
            ['2e4', false],
            [-47.12, false],
            [0, false],
            ['jojo_admin@augure.com', false], // 5
            ['http://fr.wikipedia.org/', false],
            ['magnet:?xt=urn:btih:331c7fac2e13c251d77521d2dc61976b6fc4a033&dn=archlinux-2015.06.01-x86_64.iso&tr=udp://tracker.archlinux.org:6969&tr=http://tracker.archlinux.org:6969/announce', true],
            ['magnet:?xt=urn:ed2k:31D6CFE0D16AE931B73C59D7E0C089C0&xl=0&dn=zero_len.fil', true],
        ];
    }

    //--- TESTS

    /**
     * @covers Rico\Lib\ValidationUtils
     * @covers Rico\SLib\ValidationUtils
     * @dataProvider providerIsEmail
     */
    public function testIsEmail($value, $expected)
    {
        $this->standardStaticTest(StaticValidationUtils::class, 'isEmail', [$value], $expected);
        $this->standardTest($this->validationUtils, 'isEmail', [$value], $expected);
    }

    /**
     * @covers Rico\Lib\ValidationUtils
     * @covers Rico\SLib\ValidationUtils
     * @dataProvider providerIsHexadecimal
     */
    public function testIsHexadecimal($value, $expected)
    {
        $this->standardStaticTest(StaticValidationUtils::class, 'isHexadecimal', [$value], $expected);
        $this->standardTest($this->validationUtils, 'isHexadecimal', [$value], $expected);
    }

    /**
     * @covers Rico\Lib\ValidationUtils
     * @covers Rico\SLib\ValidationUtils
     * @dataProvider providerIsIp
     */
    public function testIsIp($value, $expected)
    {
        $this->standardStaticTest(StaticValidationUtils::class, 'isIp', [$value], $expected);
        $this->standardTest($this->validationUtils, 'isIp', [$value], $expected);
    }

    /**
     * @covers Rico\Lib\ValidationUtils
     * @covers Rico\SLib\ValidationUtils
     * @dataProvider providerIsNumber
     */
    public function testIsNumber($value, $expected)
    {
        $this->standardStaticTest(StaticValidationUtils::class, 'isNumber', [$value], $expected);
        $this->standardTest($this->validationUtils, 'isNumber', [$value], $expected);
    }

    /**
     * @covers Rico\Lib\ValidationUtils
     * @covers Rico\SLib\ValidationUtils
     * @dataProvider providerIsPhoneNumber
     */
    public function testIsPhoneNumber($value, $expected)
    {
        $this->standardStaticTest(StaticValidationUtils::class, 'isPhoneNumber', [$value], $expected);
        $this->standardTest($this->validationUtils, 'isPhoneNumber', [$value], $expected);
    }

    /**
     * @covers Rico\Lib\ValidationUtils
     * @covers Rico\SLib\ValidationUtils
     * @dataProvider providerIsPositiveInt
     */
    public function testIsPositiveInt($value, $expected)
    {
        $this->standardStaticTest(StaticValidationUtils::class, 'isPositiveInt', [$value], $expected);
        $this->standardTest($this->validationUtils, 'isPositiveInt', [$value], $expected);
    }

    /**
     * @covers Rico\Lib\ValidationUtils
     * @covers Rico\SLib\ValidationUtils
     * @dataProvider providerIsURL
     */
    public function testIsURL($value, $expected)
    {
        $this->standardStaticTest(StaticValidationUtils::class, 'isUrl', [$value], $expected);
        $this->standardTest($this->validationUtils, 'isUrl', [$value], $expected);
    }

    /**
     * @covers Rico\Lib\ValidationUtils
     * @covers Rico\SLib\ValidationUtils
     * @dataProvider providerIsURLMagnet
     */
    public function testIsURLMagnet($value, $expected)
    {
        $this->standardStaticTest(StaticValidationUtils::class, 'isURLMagnet', [$value], $expected);
        $this->standardTest($this->validationUtils, 'isURLMagnet', [$value], $expected);
    }
}
