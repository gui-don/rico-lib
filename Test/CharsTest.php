<?php
declare(strict_types=1);

namespace Rico\Test\StringTest;

use \Rico\Lib\Chars;

class CharsTest extends \PHPUnit_Framework_TestCase
{
    public function providerRemoveWhitespace()
    {
        return array(
            array(false, null), // 0
            array(null, null),
            array(-47.12, null),
            array(7484, null),
            array(new \stdClass(), null),
            array("   &nbsp;", '&nbsp;'), // 5
            array('666  ', '666'),
            array('107, quai du docteur Dervaux,92600  ', '107,quaidudocteurDervaux,92600'),
            array('Espace  demerde', 'Espacedemerde'),
            array("On veut	garder les
                retours à la
                ligne mais pas les  espaces",
                "Onveutgarderles
retoursàla
lignemaispaslesespaces")
        );
    }

    public function providerNormalizeWhitespace()
    {
        return array(
            array(false, null), // 0
            array(null, null),
            array(-47.12, null),
            array(7484, null),
            array(new \stdClass(), null),
            array("   &nbsp;", '&nbsp;'), // 5
            array('666', '666'),
            array('107, quai du docteur Dervaux,92600  ', '107, quai du docteur Dervaux,92600'),
            array('Espace  demerde', 'Espace de merde'),
            array("On veut	garder les
                retours à la
                ligne mais pas les  espaces",
                "On veut garder les
 retours à la
 ligne mais pas les espaces")
        );
    }

    public function providerRemoveLine()
    {
        return array(
            array(false, null), // 0
            array(null, null),
            array(-47.12, null),
            array(7484, null),
            array(new \stdClass(), null),
            array("Ceci <br /> avec un saut
 à la   ligne   et \ndes es\r\npac\n\res  en trop \t!  ", 'Ceci <br /> avec un saut à la   ligne   et des espaces  en trop 	!  '), // 5
            array(" Multiples
 sauts
 à
 la
 ligne.", ' Multiples sauts à la ligne.'),
            array('666', '666'),
            array('
    Chaos


Pompidou




   ', '    ChaosPompidou   ')
        );
    }

    public function providerNormalize()
    {
        return array(
            array(false, null), // 0
            array(null, null),
            array(-47.12, null),
            array(7484, null),
            array(new \stdClass(), null),
            array("Ceci <br /> avec un saut
                à la   ligne   et \ndes es\r\npac\n\res  en trop \t!  ", 'Ceci avec un saut à la ligne et des espaces en trop !'), // 5
            array('\";alert(\'XSS escaping vulnerability\');//', '\";alert(\'XSS escaping vulnerability\');//'),
            array("   &nbsp;", ''),
            array(" Multiples
                sauts
                à
                la
                ligne.", 'Multiples sauts à la ligne.'),
            array('<h1>La pêche aux moules</h1><p>La pêche des moules etc.</p><br /><p>C\'est plus facile en <a href="#">hivers</a> etc.</p>', 'La pêche aux moulesLa pêche des moules etc. C\'est plus facile en hivers etc.'),
            array('666', '666'), // 10
            array('¿Puede seguir funcionando sin una  red  social corporativa?', '¿Puede seguir funcionando sin una red social corporativa?'),
            array('<div>IS THAT A <br/></div>', 'IS THAT A'),
            array('&nbsp;&lt;ok&gt;&nbsp;&nbsp; TAG OK ? zc"  ', 'TAG OK ? zc"'),
            array('
    Exemplo #1 Creating a Document


<?php$doc = new DOMDocument();$doc->loadHTMLFile("filename.html");echo $doc->saveHTML();?>




   ', 'Exemplo #1 Creating a Document')
        );
    }

    public function providerRandString()
    {
        return array (
            array('', null, ''), // 0
            array(true, null, 'ok'),
            array('test', null, ''),
            array(2.5, null, ''),
            array(-4, false, 'abcde'),
            array(15, null, new \stdClass()), // 5
            array(15, null, 45),
            array(0, false, ''),
            array(2, true, ''),
            array(12, true, ''),
            array(15, true, ''), // 10
            array(15, true, '0123456789'),
            array(30, true, 'abc'),
            array(20, true, '012345çàé'),
            array(7, true, 'ù%3~'),
            array(50, true, 'aBcDeFgHiJkLmNoPqRsTuVwXyZ') // 15
        );
    }

    public function providerSlugify()
    {
        return array(
            array(false, null), // 0
            array(null, null),
            array(-47.12, null),
            array(7484, null),
            array(new \stdClass(), null),
            array('test', 'test'), // 5
            array('Êtes-vous fait pour être le prochain développeur de notre agence ?', 'etes-vous-fait-pour-etre-le-prochain-developpeur-de-notre-agence'),
            array('0123456789', '0123456789'),
            array('	927 • Entidad aseguradora, ¿estás preparada para combatir el fraude?', '927-entidad-aseguradora-estas-preparada-para-combatir-el-fraude'),
            array('     PMI : Qué vale su Gestión de Producción
                (GPAO) ?', 'pmi-que-vale-su-gestion-de-produccion-gpao'),
            array('', ''),
        );
    }

    public function providerBeautifulise()
    {
        return array(
            array(12, null), // 0
            array(array('ok'), null),
            array(null, null),
            array(true, null),
            array(false, null),
            array(new \stdClass(), null), // 5
            array('OK', 'OK'),
            array(' No normalize   !', 'No normalize !'),
            array('Ceci est faux : Oops !', 'Ceci est faux : Oops !'),
            array('C\'est bien ici la soirée moule-frites ?', 'C’est bien ici la soirée moule-frites ?'),
            array('"Fénéant" qu\'il disent ! C\'est présomptueux !', '“Fénéant” qu’il disent ! C’est présomptueux !'), // 10
            array('Wrong spaces are " wrong ".', 'Wrong spaces are “ wrong ”.'),
            array('400000$, 1000000€, 345243£, 12000¥', '400 000 $, 1 000 000 €, 345 243 £, 12 000 ¥'),
            array('In cash: $3000', 'In cash: $3 000'),
            array('Then he cries "Bastard"!', 'Then he cries “Bastard”!'),
            array('"No!" "No!" "No!", "No!" and "No"...', '“No!” “No!” “No!”, “No!” and “No”…'), // 15
            array('Gandhi nous enseigne ; « Je pense. »', 'Gandhi nous enseigne ; « Je pense. »'),
            array('\'\'Camion!\'\'', '“Camion!”'),
            array('Le fameux " " " est trompeur disait "Renouard".', 'Le fameux " " " est trompeur disait "Renouard".'),
            array('the Tetsumen Tou ("Iron Fist" - a reference to the original Red Baron TV series, "Dragon") doctors', 'the Tetsumen Tou (“Iron Fist” - a reference to the original Red Baron TV series, “Dragon”) doctors'),
            array('As an expression of gratitude for the heroes of both the    ".hack//Sign" and the     ".hack" game series,', 'As an expression of gratitude for the heroes of both the “.hack//Sign” and the “.hack” game series,'),
            array('Il m\'a dit :
    "oui" ! Ou plutôt, "Moui" !', 'Il m’a dit :
 “oui” ! Ou plutôt, “Moui” !'),
            array('a "mystery voice" suddenly speaks to them: "A game has now started. In order to escape the room, Keisuke is the "unlocker" and one heroine the "keyhole". With an assigned act, he must "use the key"."', 'a “mystery voice” suddenly speaks to them: “A game has now started. In order to escape the room, Keisuke is the “unlocker” and one heroine the “keyhole”. With an assigned act, he must “use the key”.”')
        );
    }

    public function providerMinify()
    {
        return array(
            array(false, null), // 0
            array(null, null),
            array(-47.12, null),
            array(7484, null),
            array(new \stdClass(), null),
            array('ceci est un
                test', 'ceci est un test'), // 5
            array('ok$"é', 'ok$"é'),
            array('a:focus {
  outline: thin dotted #333;
  outline: 5px auto -webkit-focus-ring-color;
  outline-offset: -2px;
  outline: thin dotted #333;
  outline: 5px auto -webkit-focus-ring-color;
  outline-offset: -2px;
}', 'a:focus{outline:thin dotted #333;outline:5px auto -webkit-focus-ring-color;outline-offset:-2px;outline:thin dotted #333;outline:5px auto -webkit-focus-ring-color;outline-offset:-2px;}'),
            array('/*************
                        OK OK OK
                   **************/
                   [class*="span" ] {
    float: left;
    min-height: 1px;
    margin-left: 20px;
  }', '[class*="span" ]{float:left;min-height:1px;margin-left:20px;}'),
            array('audio,
canvas,
video {
  display: inline-block;
  *display: inline;
  *zoom: 1;
}', 'audio,canvas,video{display:inline-block;*display:inline;*zoom:1;}')
        );
    }

    public function providerGetResourceNameInUrl()
    {
        return array(
            array('nope', ''), // 0
            array(null, null),
            array(false, null),
            array(0, null),
            array(new \stdClass(), null),
            array('https://www.gog.com/game/prince_of_persia_warrior_within', 'prince_of_persia_warrior_within'), // 5
            array('http://i3.kym-cdn.com/photos/images/original/000/976/353/cca.png', 'cca.png'),
            array('https://en.wikipedia.org/wiki/Portable_Document_Format', 'Portable_Document_Format'),
            array('http://docs.sfr.fr/guide/Vos_chaines_TV_box_de_SFR.pdf?#zoom=81&statusbar=0&navpanes=0&messages=0', 'Vos_chaines_TV_box_de_SFR.pdf'),
            array('/home/test/Vidéos/Best_vid_ever.mp4', 'Best_vid_ever.mp4')
        );
    }

    public function providerAlphaToId()
    {
        return array(
            array(array(null, ''), null), // 0
            array(array(false, ''), null),
            array(array(0, ''), null),
            array(array(new \stdClass(), ''), null),
            array(array('test', new \stdClass()), null),
            array(array('abraCADABRA', ''), 17251060315943390), // 5
            array(array('phpcode', ''), 858638639286),
            array(array('phpcode', 'secret'), 1193128009855),
            array(array('', ''), ''),
            array(array('', 'secret'), ''),
            array(array('777', ''), 128931), // 10
            array(array('/home/', ''), 106817320)
        );
    }

    public function providerIdToAlpha()
    {
        return array(
            array(array(null, ''), null), // 0
            array(array(false, ''), null),
            array(array(0, ''), 'a'),
            array(array(new \stdClass(), ''), null),
            array(array('test', new \stdClass()), null),
            array(array('abraCADABRA', ''), null), // 5
            array(array(3432, 345), null),
            array(array(858638639286, ''), 'phpcode'),
            array(array(1193128009855, 'secret'), 'phpcode'),
            array(array(0, 'secret'), 'h'),
            array(array(128931, ''), '777'), // 10
            array(array(106817320, ''), 'homea'),
            array(array(92395783831158784, ''), 'gPkLA3jITS'),
        );
    }

    /**
     * @covers Chars::removeWhitespace
     * @dataProvider providerRemoveWhitespace
     */
    public function testRemoveWhitespace($value, $expected)
    {
        if ($expected !== null) {
            $this->assertSame($expected, Chars::removeWhitespace($value));
        } else {
            $this->setExpectedException('TypeError');
            Chars::removeWhitespace($value);
        }
    }

    /**
     * @covers Chars::normalizeWhitespace
     * @dataProvider providerNormalizeWhitespace
     */
    public function testNormalizeWhitespace($value, $expected)
    {
        if ($expected !== null) {
            $this->assertSame($expected, Chars::normalizeWhitespace($value));
        } else {
            $this->setExpectedException('TypeError');
            Chars::normalizeWhitespace($value);
        }
    }

    /**
     * @covers Chars::removeLine
     * @dataProvider providerRemoveLine
     */
    public function testRemoveLine($value, $expected)
    {
        if ($expected !== null) {
            $this->assertSame($expected, Chars::removeLine($value));
        } else {
            $this->setExpectedException('TypeError');
            Chars::removeLine($value);
        }
    }

    /**
     * @covers Chars::normalize
     * @dataProvider providerNormalize
     */
    public function testNormalize($value, $expected)
    {
        if ($expected !== null) {
            $this->assertSame($expected, Chars::normalize($value));
        } else {
            $this->setExpectedException('TypeError');
            Chars::normalize($value);
        }
    }

    /**
     * @covers Chars::randString
     * @dataProvider providerRandString
     */
    public function testRandString($value, $bExpected, $allowedChars)
    {
        if ($bExpected !== null) {
            if ($value <= 0) {
                $this->assertFalse(Chars::randString($value, $allowedChars));
            } else {
                if (strlen($allowedChars) > 0) {
                    $result = Chars::randString($value, $allowedChars);
                    $this->assertRegExp('/^['.$allowedChars.']+$/', $result);
                } else {
                    $result = Chars::randString($value);
                    $this->assertRegExp('/^[a-zA-Z0-9]+$/', $result);
                }

                $this->assertEquals($value, mb_strlen($result, 'utf8'));
            }
        } else {
            $this->setExpectedException('TypeError');
            Chars::randString($value, $allowedChars);
        }
    }

    /**
     * @covers Chars::slugify
     * @dataProvider providerSlugify
     */
    public function testSlugify($value, $bExpected)
    {
        if ($bExpected !== null) {
            $this->assertSame($bExpected, Chars::slugify($value));
        } else {
            $this->setExpectedException('TypeError');
            Chars::slugify($value);
        }
    }

    /**
     * @covers Chars::beautifulise
     * @dataProvider providerBeautifulise
     */
    public function testBeautifulise($value, $expected)
    {
        if ($expected !== null) {
            $result = Chars::beautifulise($value);
            $this->assertSame($expected, $result);

            // Doing it again changes nothing
            $this->assertSame($expected, Chars::beautifulise($result));
        } else {
            $this->setExpectedException('TypeError');
            Chars::beautifulise($value);
        }

    }

    /**
     * @covers Chars::minify
     * @dataProvider providerMinify
     */
    public function testMinify($value, $expected)
    {
        if ($expected !== null) {
            $this->assertSame($expected, Chars::minify($value));
        } else {
            $this->setExpectedException('TypeError');
            Chars::minify($value);
        }
    }

    /**
     * @covers Chars::getResourceNameInUrl
     * @dataProvider providerGetResourceNameInUrl
     */
    public function testGetResourceNameInUrl($value, $expected)
    {
        if ($expected !== null) {
            $this->assertSame($expected, Chars::getResourceNameInUrl($value));
        } else {
            $this->setExpectedException('TypeError');
            Chars::getResourceNameInUrl($value);
        }
    }

    /**
     * @covers Chars::alphaToId
     * @dataProvider providerAlphaToId
     */
    public function testAlphaToId($values, $expected)
    {
        if ($expected !== null) {
            $this->assertSame($expected, Chars::alphaToId($values[0], $values[1]));
        } else {
            $this->setExpectedException('TypeError');
            Chars::alphaToId($values[0], $values[1]);
        }
    }

    /**
     * @covers Chars::idToAplpha
     * @dataProvider providerIdToAlpha
     */
    public function testIdToAplpha($values, $expected)
    {
        if ($expected !== null) {
            $this->assertSame($expected, Chars::IdToAlpha($values[0], $values[1]));
        } else {
            $this->setExpectedException('TypeError');
            Chars::IdToAlpha($values[0], $values[1]);
        }
    }
}
