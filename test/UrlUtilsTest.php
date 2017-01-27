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
            [false, null],
            [0, null],
            [new \stdClass(), null],
            ['nope', ''],
            ['https://www.gog.com/game/prince_of_persia_warrior_within', 'prince_of_persia_warrior_within'], // 5
            ['http://i3.kym-cdn.com/photos/images/original/000/976/353/cca.png', 'cca.png'],
            ['https://en.wikipedia.org/wiki/Portable_Document_Format', 'Portable_Document_Format'],
            ['http://docs.sfr.fr/guide/Vos_chaines_TV_box_de_SFR.pdf?#zoom=81&statusbar=0&navpanes=0&messages=0', 'Vos_chaines_TV_box_de_SFR.pdf'],
            ['/home/test/Vidéos/Best_vid_ever.mp4', 'Best_vid_ever.mp4'],
            ['ftp://some_code.py', 'some_code.py'], // 10
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
}
