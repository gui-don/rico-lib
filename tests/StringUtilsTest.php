<?php

declare(strict_types=1);

namespace Rico\Test;

use Rico\Slib\StringUtils as StaticStringUtils;
use Rico\Lib\StringUtils;

class StringUtilsTest extends RicoTestCase
{
    /**
     * @var StringUtils
     */
    private $stringUtils;

    public function setUp(): void
    {
        $this->stringUtils = new StringUtils();
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
            [['', ''], 0],
            [['', 'secret'], 0],
            [['777', ''], 128931], // 10
            [['/home/', ''], 106817320],
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

    public function providerHumanFilesize()
    {
        return [
            [null, null], // 0
            ['test', null],
            [new \DateTime(), null],
            [3.14, null],
            [true, null],
            [0, '0B'], // 5
            [-243, '-0.24KB'],
            [2434, '2.43KB'],
            [23415406323, '23.4GB'],
            [141540632, '142MB'],
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

    public function providerRemoveBracketContent()
    {
        return [
            [null, null], // 0
            [true, null],
            [new \DateTime(), null],
            [3.14, null],
            [-234, null],
            ['test', 'test'], // 5
            ['[Hey]_The_BIG_Move_-_A\'s_(1920x1080_Blu-ray_FLAC)_[245D1BDA].mkv', '_The_BIG_Move_-_A\'s_(1920x1080_Blu-ray_FLAC)_.mkv'],
            ['Multiple [imbricated[stuff[]] ]  ', 'Multiple'],
            ['Openened [but not ended[', 'Openened [but not ended['],
            ['start][OK] @éàù¥ - ][€nd;', 'start][€nd;'],
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

    public function providerUnderscoreToSpace()
    {
        return [
            [null, null], // 0
            [true, null],
            [new \DateTime(), null],
            [3.14, null],
            [-234, null],
            ['test', 'test'], // 5
            ['[Hey]_The_BIG_Move_-_A\'s_(1920x1080_Blu-ray_FLAC)_[245D1BDA].mkv', '[Hey] The BIG Move - A\'s (1920x1080 Blu-ray FLAC) [245D1BDA].mkv'],
            ['Inside a sentence this_might_be more _ tricky_', 'Inside a sentence this might be more tricky'],
            ['When__multiple__underscore’s___occurs', 'When multiple underscore’s occurs'],
        ];
    }

    //--- TESTS

    /**
     * @covers Rico\Lib\StringUtils
     * @covers Rico\SLib\StringUtils
     * @dataProvider providerAlphaToId
     */
    public function testAlphaToId($values, $expected)
    {
        $this->standardStaticTest(StaticStringUtils::class, 'alphaToId', [$values[0], $values[1]], $expected);
        $this->standardTest($this->stringUtils, 'alphaToId', [$values[0], $values[1]], $expected);
    }

    /**
     * @covers Rico\Lib\StringUtils
     * @covers Rico\SLib\StringUtils
     * @dataProvider providerBeautifulise
     */
    public function testBeautifulise($value, $expected)
    {
        $test = function () use ($value, $expected) {
            $result = $this->stringUtils->beautifulise($value);
            $this->assertSame($expected, $result);

            // Doing it again changes nothing
            $this->assertSame($expected, $this->stringUtils->beautifulise($result));
        };

        $this->callbackStandardStaticTest(StaticStringUtils::class, 'beautifulise', [$value], $expected, $test);
        $this->callbackStandardTest($this->stringUtils, 'beautifulise', [$value], $expected, $test);
    }

    /**
     * @covers Rico\Lib\StringUtils
     * @covers Rico\SLib\StringUtils
     * @covers Rico\Lib\MathUtils
     * @covers Rico\SLib\MathUtils
     * @dataProvider providerHumanFilesize
     */
    public function testHumanFileSize($value, $expected)
    {
        $this->standardStaticTest(StaticStringUtils::class, 'humanFilesize', [$value], $expected);
        $this->standardTest($this->stringUtils, 'humanFilesize', [$value], $expected);
    }

    /**
     * @covers Rico\Lib\StringUtils
     * @covers Rico\SLib\StringUtils
     * @dataProvider providerIdToAlpha
     */
    public function testIdToAlpha($values, $expected)
    {
        $this->standardStaticTest(StaticStringUtils::class, 'idToAlpha', [$values[0], $values[1]], $expected);
        $this->standardTest($this->stringUtils, 'idToAlpha', [$values[0], $values[1]], $expected);
    }

    /**
     * @covers Rico\Lib\StringUtils
     * @covers Rico\SLib\StringUtils
     * @dataProvider providerMinify
     */
    public function testMinify($value, $expected)
    {
        $this->standardStaticTest(StaticStringUtils::class, 'minify', [$value], $expected);
        $this->standardTest($this->stringUtils, 'minify', [$value], $expected);
    }

    /**
     * @covers Rico\Lib\StringUtils
     * @covers Rico\SLib\StringUtils
     * @dataProvider providerNormalize
     */
    public function testNormalize($value, $expected)
    {
        $this->standardStaticTest(StaticStringUtils::class, 'normalize', [$value], $expected);
        $this->standardTest($this->stringUtils, 'normalize', [$value], $expected);
    }

    /**
     * @covers Rico\Lib\StringUtils
     * @covers Rico\SLib\StringUtils
     * @dataProvider providerRandString
     */
    public function testRandString($value, $expected, $allowedChars)
    {
        $test = function () use ($allowedChars, $value) {
            if ($value <= 0) {
                $this->assertEmpty($this->stringUtils->randString($value, $allowedChars));
            } else {
                if (mb_strlen($allowedChars) > 0) {
                    $result = $this->stringUtils->randString($value, $allowedChars);
                    $this->assertMatchesRegularExpression('/^['.$allowedChars.']+$/', $result);
                } else {
                    $result = $this->stringUtils->randString($value);
                    $this->assertMatchesRegularExpression('/^[a-zA-Z0-9]+$/', $result);
                }

                $this->assertEquals($value, mb_strlen($result, 'utf8'));
            }
        };

        $this->callbackStandardStaticTest(StaticStringUtils::class, 'randString', [$value, $allowedChars], $expected, $test);
        $this->callbackStandardTest($this->stringUtils, 'randString', [$value, $allowedChars], $expected, $test);
    }

    /**
     * @covers Rico\Lib\StringUtils
     * @covers Rico\SLib\StringUtils
     * @dataProvider providerNormalizeWhitespace
     */
    public function testNormalizeWhitespace($value, $expected)
    {
        $this->standardStaticTest(StaticStringUtils::class, 'normalizeWhitespace', [$value], $expected);
        $this->standardTest($this->stringUtils, 'normalizeWhitespace', [$value], $expected);
    }

    /**
     * @covers Rico\Lib\StringUtils
     * @covers Rico\SLib\StringUtils
     * @dataProvider providerRemoveBracketContent
     */
    public function testRemoveBracketContent($value, $expected)
    {
        $this->standardStaticTest(StaticStringUtils::class, 'removeBracketContent', [$value], $expected);
        $this->standardTest($this->stringUtils, 'removeBracketContent', [$value], $expected);
    }

    /**
     * @covers Rico\Lib\StringUtils
     * @covers Rico\SLib\StringUtils
     * @dataProvider providerRemoveLine
     */
    public function testRemoveLine($value, $expected)
    {
        $this->standardStaticTest(StaticStringUtils::class, 'removeLine', [$value], $expected);
        $this->standardTest($this->stringUtils, 'removeLine', [$value], $expected);
    }

    /**
     * @covers Rico\Lib\StringUtils
     * @covers Rico\SLib\StringUtils
     * @dataProvider providerRemoveWhitespace
     */
    public function testRemoveWhitespace($value, $expected)
    {
        $this->standardStaticTest(StaticStringUtils::class, 'removeWhitespace', [$value], $expected);
        $this->standardTest($this->stringUtils, 'removeWhitespace', [$value], $expected);
    }

    /**
     * @covers Rico\Lib\StringUtils
     * @covers Rico\SLib\StringUtils
     * @dataProvider providerSlugify
     */
    public function testSlugify($value, $expected)
    {
        $this->standardStaticTest(StaticStringUtils::class, 'slugify', [$value], $expected);
        $this->standardTest($this->stringUtils, 'slugify', [$value], $expected);
    }

    /**
     * @covers Rico\Lib\StringUtils
     * @covers Rico\SLib\StringUtils
     * @dataProvider providerUnderscoreToSpace
     */
    public function testUnderscoreToSpace($value, $expected)
    {
        $this->standardStaticTest(StaticStringUtils::class, 'underscoreToSpace', [$value], $expected);
        $this->standardTest($this->stringUtils, 'underscoreToSpace', [$value], $expected);
    }
}
