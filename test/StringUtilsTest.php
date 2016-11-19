<?php

declare(strict_types=1);

namespace Rico\Test\StringTest;

use Rico\Lib\StringUtils;

class CharsTest extends \PHPUnit_Framework_TestCase
{
    public function providerRemoveWhitespace()
    {
        return [
            [false, null], // 0
            [null, null],
            [-47.12, null],
            [7484, null],
            [new \stdClass(), null],
            ['   &nbsp;', '&nbsp;'], // 5
            ['666  ', '666'],
            ['107, quai du docteur Dervaux,92600  ', '107,quaidudocteurDervaux,92600'],
            ['Espace  demerde', 'Espacedemerde'],
            ['On veut	garder les
                retours à la
                ligne mais pas les  espaces',
                'Onveutgarderles
retoursàla
lignemaispaslesespaces', ],
        ];
    }

    public function providerNormalizeWhitespace()
    {
        return [
            [false, null], // 0
            [null, null],
            [-47.12, null],
            [7484, null],
            [new \stdClass(), null],
            ['   &nbsp;', '&nbsp;'], // 5
            ['666', '666'],
            ['107, quai du docteur Dervaux,92600  ', '107, quai du docteur Dervaux,92600'],
            ['Espace  demerde', 'Espace de merde'],
            ['On veut	garder les
                retours à la
                ligne mais pas les  espaces',
                'On veut garder les
 retours à la
 ligne mais pas les espaces', ],
        ];
    }

    public function providerRemoveLine()
    {
        return [
            [false, null], // 0
            [null, null],
            [-47.12, null],
            [7484, null],
            [new \stdClass(), null],
            ["Ceci <br /> avec un saut
 à la   ligne   et \ndes es\r\npac\n\res  en trop \t!  ", 'Ceci <br /> avec un saut à la   ligne   et des espaces  en trop 	!  '], // 5
            [' Multiples
 sauts
 à
 la
 ligne.', ' Multiples sauts à la ligne.'],
            ['666', '666'],
            ['
    Chaos


Pompidou




   ', '    ChaosPompidou   '],
        ];
    }

    public function providerNormalize()
    {
        return [
            [false, null], // 0
            [null, null],
            [-47.12, null],
            [7484, null],
            [new \stdClass(), null],
            ["Ceci <br /> avec un saut
                à la   ligne   et \ndes es\r\npac\n\res  en trop \t!  ", 'Ceci avec un saut à la ligne et des espaces en trop !'], // 5
            ['\";alert(\'XSS escaping vulnerability\');//', '\";alert(\'XSS escaping vulnerability\');//'],
            ['   &nbsp;', ''],
            [' Multiples
                sauts
                à
                la
                ligne.', 'Multiples sauts à la ligne.'],
            ['<h1>La pêche aux moules</h1><p>La pêche des moules etc.</p><br /><p>C\'est plus facile en <a href="#">hivers</a> etc.</p>', 'La pêche aux moulesLa pêche des moules etc. C\'est plus facile en hivers etc.'],
            ['666', '666'], // 10
            ['¿Puede seguir funcionando sin una  red  social corporativa?', '¿Puede seguir funcionando sin una red social corporativa?'],
            ['<div>IS THAT A <br/></div>', 'IS THAT A'],
            ['&nbsp;&lt;ok&gt;&nbsp;&nbsp; TAG OK ? zc"  ', 'TAG OK ? zc"'],
            ['
    Exemplo #1 Creating a Document


<?php$doc = new DOMDocument();$doc->loadHTMLFile("filename.html");echo $doc->saveHTML();?>




   ', 'Exemplo #1 Creating a Document'],
        ];
    }

    public function providerRandString()
    {
        return [
            ['', null, ''], // 0
            [true, null, 'ok'],
            ['test', null, ''],
            [2.5, null, ''],
            [-4, false, 'abcde'],
            [15, null, new \stdClass()], // 5
            [15, null, 45],
            [0, false, ''],
            [2, true, ''],
            [12, true, ''],
            [15, true, ''], // 10
            [15, true, '0123456789'],
            [30, true, 'abc'],
            [20, true, '012345çàé'],
            [7, true, 'ù%3~'],
            [50, true, 'aBcDeFgHiJkLmNoPqRsTuVwXyZ'], // 15
        ];
    }

    public function providerSlugify()
    {
        return [
            [false, null], // 0
            [null, null],
            [-47.12, null],
            [7484, null],
            [new \stdClass(), null],
            ['test', 'test'], // 5
            ['Êtes-vous fait pour être le prochain développeur de notre agence ?', 'etes-vous-fait-pour-etre-le-prochain-developpeur-de-notre-agence'],
            ['0123456789', '0123456789'],
            ['	927 • Entidad aseguradora, ¿estás preparada para combatir el fraude?', '927-entidad-aseguradora-estas-preparada-para-combatir-el-fraude'],
            ['     PMI : Qué vale su Gestión de Producción
                (GPAO) ?', 'pmi-que-vale-su-gestion-de-produccion-gpao'],
            ['', ''],
        ];
    }

    public function providerBeautifulise()
    {
        return [
            [12, null], // 0
            [['ok'], null],
            [null, null],
            [true, null],
            [false, null],
            [new \stdClass(), null], // 5
            ['OK', 'OK'],
            [' No normalize   !', 'No normalize !'],
            ['Ceci est faux : Oops !', 'Ceci est faux : Oops !'],
            ['C\'est bien ici la soirée moule-frites ?', 'C’est bien ici la soirée moule-frites ?'],
            ['"Fénéant" qu\'il disent ! C\'est présomptueux !', '“Fénéant” qu’il disent ! C’est présomptueux !'], // 10
            ['Wrong spaces are " wrong ".', 'Wrong spaces are “ wrong ”.'],
            ['400000$, 1000000€, 345243£, 12000¥', '400 000 $, 1 000 000 €, 345 243 £, 12 000 ¥'],
            ['In cash: $3000', 'In cash: $3 000'],
            ['Then he cries "Bastard"!', 'Then he cries “Bastard”!'],
            ['"No!" "No!" "No!", "No!" and "No"...', '“No!” “No!” “No!”, “No!” and “No”…'], // 15
            ['Gandhi nous enseigne ; « Je pense. »', 'Gandhi nous enseigne ; « Je pense. »'],
            ['\'\'Camion!\'\'', '“Camion!”'],
            ['Le fameux " " " est trompeur disait "Renouard".', 'Le fameux " " " est trompeur disait "Renouard".'],
            ['the Tetsumen Tou ("Iron Fist" - a reference to the original Red Baron TV series, "Dragon") doctors', 'the Tetsumen Tou (“Iron Fist” - a reference to the original Red Baron TV series, “Dragon”) doctors'],
            ['As an expression of gratitude for the heroes of both the    ".hack//Sign" and the     ".hack" game series,', 'As an expression of gratitude for the heroes of both the “.hack//Sign” and the “.hack” game series,'],
            ['Il m\'a dit :
    "oui" ! Ou plutôt, "Moui" !', 'Il m’a dit :
 “oui” ! Ou plutôt, “Moui” !'],
            ['a "mystery voice" suddenly speaks to them: "A game has now started. In order to escape the room, Keisuke is the "unlocker" and one heroine the "keyhole". With an assigned act, he must "use the key"."', 'a “mystery voice” suddenly speaks to them: “A game has now started. In order to escape the room, Keisuke is the “unlocker” and one heroine the “keyhole”. With an assigned act, he must “use the key”.”'],
        ];
    }

    public function providerMinify()
    {
        return [
            [false, null], // 0
            [null, null],
            [-47.12, null],
            [7484, null],
            [new \stdClass(), null],
            ['ceci est un
                test', 'ceci est un test'], // 5
            ['ok$"é', 'ok$"é'],
            ['a:focus {
  outline: thin dotted #333;
  outline: 5px auto -webkit-focus-ring-color;
  outline-offset: -2px;
  outline: thin dotted #333;
  outline: 5px auto -webkit-focus-ring-color;
  outline-offset: -2px;
}', 'a:focus{outline:thin dotted #333;outline:5px auto -webkit-focus-ring-color;outline-offset:-2px;outline:thin dotted #333;outline:5px auto -webkit-focus-ring-color;outline-offset:-2px;}'],
            ['/*************
                        OK OK OK
                   **************/
                   [class*="span" ] {
    float: left;
    min-height: 1px;
    margin-left: 20px;
  }', '[class*="span" ]{float:left;min-height:1px;margin-left:20px;}'],
            ['audio,
canvas,
video {
  display: inline-block;
  *display: inline;
  *zoom: 1;
}', 'audio,canvas,video{display:inline-block;*display:inline;*zoom:1;}'],
        ];
    }

    public function providerGetResourceNameInUrl()
    {
        return [
            ['nope', ''], // 0
            [null, null],
            [false, null],
            [0, null],
            [new \stdClass(), null],
            ['https://www.gog.com/game/prince_of_persia_warrior_within', 'prince_of_persia_warrior_within'], // 5
            ['http://i3.kym-cdn.com/photos/images/original/000/976/353/cca.png', 'cca.png'],
            ['https://en.wikipedia.org/wiki/Portable_Document_Format', 'Portable_Document_Format'],
            ['http://docs.sfr.fr/guide/Vos_chaines_TV_box_de_SFR.pdf?#zoom=81&statusbar=0&navpanes=0&messages=0', 'Vos_chaines_TV_box_de_SFR.pdf'],
            ['/home/test/Vidéos/Best_vid_ever.mp4', 'Best_vid_ever.mp4'],
        ];
    }

    public function providerAlphaToId()
    {
        return [
            [[null, ''], null], // 0
            [[false, ''], null],
            [[0, ''], null],
            [[new \stdClass(), ''], null],
            [['test', new \stdClass()], null],
            [['abraCADABRA', ''], 17251060315943390], // 5
            [['phpcode', ''], 858638639286],
            [['phpcode', 'secret'], 1193128009855],
            [['', ''], ''],
            [['', 'secret'], ''],
            [['777', ''], 128931], // 10
            [['/home/', ''], 106817320],
        ];
    }

    public function providerIdToAlpha()
    {
        return [
            [[null, ''], null], // 0
            [[false, ''], null],
            [[0, ''], 'a'],
            [[new \stdClass(), ''], null],
            [['test', new \stdClass()], null],
            [['abraCADABRA', ''], null], // 5
            [[3432, 345], null],
            [[858638639286, ''], 'phpcode'],
            [[1193128009855, 'secret'], 'phpcode'],
            [[0, 'secret'], 'h'],
            [[128931, ''], '777'], // 10
            [[106817320, ''], 'homea'],
            [[92395783831158784, ''], 'gPkLA3jITS'],
        ];
    }

    public function providerHumanFilesize()
    {
        return [
            [null, null], // 0
            ['test', null],
            [new \DateTime, null],
            [3.14, null],
            [true, null],
            [0, '0B'], // 5
            [-243, '-0.24KB'],
            [2434, '2.38KB'],
            [23415406323, '21.8GB'],
            [141540632, '135MB'],
        ];
    }

    /**
     * @covers StringUtils::removeWhitespace
     * @dataProvider providerRemoveWhitespace
     */
    public function testRemoveWhitespace($value, $expected)
    {
        if ($expected !== null) {
            $this->assertSame($expected, StringUtils::removeWhitespace($value));
        } else {
            $this->setExpectedException('TypeError');
            StringUtils::removeWhitespace($value);
        }
    }

    /**
     * @covers StringUtils::normalizeWhitespace
     * @dataProvider providerNormalizeWhitespace
     */
    public function testNormalizeWhitespace($value, $expected)
    {
        if ($expected !== null) {
            $this->assertSame($expected, StringUtils::normalizeWhitespace($value));
        } else {
            $this->setExpectedException('TypeError');
            StringUtils::normalizeWhitespace($value);
        }
    }

    /**
     * @covers StringUtils::removeLine
     * @dataProvider providerRemoveLine
     */
    public function testRemoveLine($value, $expected)
    {
        if ($expected !== null) {
            $this->assertSame($expected, StringUtils::removeLine($value));
        } else {
            $this->setExpectedException('TypeError');
            StringUtils::removeLine($value);
        }
    }

    /**
     * @covers StringUtils::normalize
     * @dataProvider providerNormalize
     */
    public function testNormalize($value, $expected)
    {
        if ($expected !== null) {
            $this->assertSame($expected, StringUtils::normalize($value));
        } else {
            $this->setExpectedException('TypeError');
            StringUtils::normalize($value);
        }
    }

    /**
     * @covers StringUtils::randString
     * @dataProvider providerRandString
     */
    public function testRandString($value, $bExpected, $allowedChars)
    {
        if ($bExpected !== null) {
            if ($value <= 0) {
                $this->assertEmpty(StringUtils::randString($value, $allowedChars));
            } else {
                if (strlen($allowedChars) > 0) {
                    $result = StringUtils::randString($value, $allowedChars);
                    $this->assertRegExp('/^['.$allowedChars.']+$/', $result);
                } else {
                    $result = StringUtils::randString($value);
                    $this->assertRegExp('/^[a-zA-Z0-9]+$/', $result);
                }

                $this->assertEquals($value, mb_strlen($result, 'utf8'));
            }
        } else {
            $this->setExpectedException('TypeError');
            StringUtils::randString($value, $allowedChars);
        }
    }

    /**
     * @covers StringUtils::slugify
     * @dataProvider providerSlugify
     */
    public function testSlugify($value, $bExpected)
    {
        if ($bExpected !== null) {
            $this->assertSame($bExpected, StringUtils::slugify($value));
        } else {
            $this->setExpectedException('TypeError');
            StringUtils::slugify($value);
        }
    }

    /**
     * @covers StringUtils::beautifulise
     * @dataProvider providerBeautifulise
     */
    public function testBeautifulise($value, $expected)
    {
        if ($expected !== null) {
            $result = StringUtils::beautifulise($value);
            $this->assertSame($expected, $result);

            // Doing it again changes nothing
            $this->assertSame($expected, StringUtils::beautifulise($result));
        } else {
            $this->setExpectedException('TypeError');
            StringUtils::beautifulise($value);
        }
    }

    /**
     * @covers StringUtils::minify
     * @dataProvider providerMinify
     */
    public function testMinify($value, $expected)
    {
        if ($expected !== null) {
            $this->assertSame($expected, StringUtils::minify($value));
        } else {
            $this->setExpectedException('TypeError');
            StringUtils::minify($value);
        }
    }

    /**
     * @covers StringUtils::getResourceNameInUrl
     * @dataProvider providerGetResourceNameInUrl
     */
    public function testGetResourceNameInUrl($value, $expected)
    {
        if ($expected !== null) {
            $this->assertSame($expected, StringUtils::getResourceNameInUrl($value));
        } else {
            $this->setExpectedException('TypeError');
            StringUtils::getResourceNameInUrl($value);
        }
    }

    /**
     * @covers StringUtils::alphaToId
     * @dataProvider providerAlphaToId
     */
    public function testAlphaToId($values, $expected)
    {
        if ($expected !== null) {
            $this->assertSame($expected, StringUtils::alphaToId($values[0], $values[1]));
        } else {
            $this->setExpectedException('TypeError');
            StringUtils::alphaToId($values[0], $values[1]);
        }
    }

    /**
     * @covers StringUtils::idToAplpha
     * @dataProvider providerIdToAlpha
     */
    public function testIdToAplpha($values, $expected)
    {
        if ($expected !== null) {
            $this->assertSame($expected, StringUtils::IdToAlpha($values[0], $values[1]));
        } else {
            $this->setExpectedException('TypeError');
            StringUtils::IdToAlpha($values[0], $values[1]);
        }
    }

    /**
     * @covers StringUtils::humanFilesize
     * @dataProvider providerHumanFilesize
     */
    public function testHumanFilesize($value, $expected)
    {
        if ($expected !== null) {
            $this->assertSame($expected, StringUtils::humanFilesize($value));
        } else {
            $this->setExpectedException('TypeError');
            StringUtils::humanFilesize($value);
        }
    }
}
