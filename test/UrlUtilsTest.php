<?php

declare(strict_types=1);

namespace Rico\Test;

use Rico\Slib\UrlUtils as StaticUrlUtils;
use Rico\Lib\UrlUtils;

class UrlUtilsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UrlUtils
     */
    private $urlUtils;

    public function setUp()
    {
        $this->urlUtils = new UrlUtils();
    }

    public function providerGetResourceName()
    {
        return [
            [null, null], // 0
            [true, null],
            [0, null],
            [34.6, null],
            [new \stdClass(), null],
            ['nope', ''], // 5
            ['https://www.gog.com/game/prince_of_persia_warrior_within', 'prince_of_persia_warrior_within'],
            ['http://i3.kym-cdn.com/photos/images/original/000/976/353/cca.png', 'cca.png'],
            ['https://en.wikipedia.org/wiki/Portable_Document_Format', 'Portable_Document_Format'],
            ['http://docs.sfr.fr/guide/Vos_chaines_TV_box_de_SFR.pdf?#zoom=81&statusbar=0&navpanes=0&messages=0', 'Vos_chaines_TV_box_de_SFR.pdf'],
            ['/home/test/Vidéos/Best_vid_ever.mp4', 'Best_vid_ever.mp4'], // 10
            ['ftp://some_code.py', 'some_code.py'],
            ['https://', ''],
            ['http://some.tricky.url.es/trap/my%20document.php?url=http://this.is.com/another/url/doc', 'my%20document.php'],
        ];
    }

    public function providerGetUrlWithoutResourceName()
    {
        return [
            [null, null], // 0
            [true, null],
            [0, null],
            [34.6, null],
            [new \stdClass(), null],
            ['nope', ''], // 5
            ['https://www.gog.com/game/prince_of_persia_warrior_within', 'https://www.gog.com/game/'],
            ['http://i3.kym-cdn.com/photos/images/original/000/976/353/cca.png', 'http://i3.kym-cdn.com/photos/images/original/000/976/353/'],
            ['https://en.wikipedia.org/wiki/Portable_Document_Format', 'https://en.wikipedia.org/wiki/'],
            ['http://docs.sfr.fr/guide/Vos_chaines_TV_box_de_SFR.pdf?#zoom=81&statusbar=0&navpanes=0&messages=0', 'http://docs.sfr.fr/guide/'],
            ['/home/test/Vidéos/Best_vid_ever.mp4', '/home/test/Vidéos/'], // 10
            ['ftp://some_code.py', 'ftp://'],
            ['https://', 'https://'],
            ['http://some.tricky.url.es/trap/my%20document.php?url=http://this.is.com/another/url/doc', 'http://some.tricky.url.es/trap/'],
        ];
    }

    /**
     * @covers StaticUrlUtils::getResourceName
     * @dataProvider providerGetResourceName
     */
    public function testGetResourceNameInUrlStatic($value, $expected)
    {
        if ($expected !== null) {
            $this->assertSame($expected, StaticUrlUtils::getResourceName($value));
        } else {
            $this->setExpectedException('TypeError');
            StaticUrlUtils::getResourceName($value);
        }
    }

    /**
     * @covers UrlUtils::getResourceName
     * @dataProvider providerGetResourceName
     */
    public function testGetResourceNameInUrl($value, $expected)
    {
        if ($expected !== null) {
            $this->assertSame($expected, $this->urlUtils->getResourceName($value));
        } else {
            $this->setExpectedException('TypeError');
            $this->urlUtils->getResourceName($value);
        }
    }

    /**
     * @covers StaticUrlUtils::getUrlWithoutResourceName
     * @dataProvider providerGetUrlWithoutResourceName
     */
    public function testGetUrlWithoutResourceNameStatic($value, $expected)
    {
        if ($expected !== null) {
            $this->assertSame($expected, StaticUrlUtils::getUrlWithoutResourceName($value));
        } else {
            $this->setExpectedException('TypeError');
            StaticUrlUtils::getUrlWithoutResourceName($value);
        }
    }

    /**
     * @covers UrlUtils::getUrlWithoutResourceName
     * @dataProvider providerGetUrlWithoutResourceName
     */
    public function testGetUrlWithoutResourceName($value, $expected)
    {
        if ($expected !== null) {
            $this->assertSame($expected, $this->urlUtils->getUrlWithoutResourceName($value));
        } else {
            $this->setExpectedException('TypeError');
            $this->urlUtils->getUrlWithoutResourceName($value);
        }
    }
}
