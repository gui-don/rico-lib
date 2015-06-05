<?php

namespace Test\StringTest;

use \Lib\String;

class StringTest extends \PHPUnit_Framework_TestCase
{
    public function providerRemoveWhitespace()
    {
        return array(
            array("   &nbsp;", '&nbsp;'), // 0
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
            array("   &nbsp;", '&nbsp;'), // 0
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
            array("Ceci <br /> avec un saut
 à la   ligne   et \ndes es\r\npac\n\res  en trop \t!  ", 'Ceci <br /> avec un saut à la   ligne   et des espaces  en trop 	!  '), // 0
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
            array("Ceci <br /> avec un saut
                à la   ligne   et \ndes es\r\npac\n\res  en trop \t!  ", 'Ceci avec un saut à la ligne et des espaces en trop !'), // 0
            array('\";alert(\'XSS escaping vulnerability\');//', '\";alert(\'XSS escaping vulnerability\');//'),
            array("   &nbsp;", ''),
            array(" Multiples
                sauts
                à
                la
                ligne.", 'Multiples sauts à la ligne.'),
            array('<h1>La pêche aux moules</h1><p>La pêche des moules etc.</p><br /><p>C\'est plus facile en <a href="#">hivers</a> etc.</p>', 'La pêche aux moulesLa pêche des moules etc. C\'est plus facile en hivers etc.'),
            array('666', '666'), // 5
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
            array('', true, ''), // 0
            array(true, true, ''),
            array('test', true, ''),
            array(2.5, true, ''),
            array(-4, true, ''),
            array(2, false, ''), // 5
            array(12, false, ''),
            array(15, false, ''),
            array(15, false, '0123456789'),
            array(30, false, 'abc'),
            array(20, false, '012345çàé'), // 10
            array(7, false, 'ù%3~'),
            array(50, false, 'aBcDeFgHiJkLmNoPqRsTuVwXyZ')
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
            array(12, false), // 0
            array(array('ok'), false),
            array(null, false),
            array(true, false),
            array(false, false),
            array(new \stdClass(), false), // 5
            array('OK', 'OK'),
            array(' No normalize   !', ' No normalize   !'),
            array('Ceci est faux : Oops !', 'Ceci est faux : Oops !'),
            array('C\'est bien ici la soirée moule-frites ?', 'C’est bien ici la soirée moule-frites ?'),
            array('"Fénéant" qu\'il disent ! C\'est présomptueux !', '“Fénéant” qu’il disent ! C’est présomptueux !'), // 10
            array('Gandhi nous enseigne ; « Je pense. »', 'Gandhi nous enseigne ; « Je pense. »'),
            array('Il m\'a dit : "oui" ! Ou plutôt, "Moui" !', 'Il m’a dit : “oui” ! Ou plutôt, “Moui” !'),
        );
    }

    public function providerMinify()
    {
        return array(
            array('ceci est un
                test', 'ceci est un test'),
            array('ok$"é', 'ok$"é'),
            array(null, ''),
            array(false, ''),
            array(0, '0'),
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

    /**
     * @covers String::removeWhitespace
     * @dataProvider providerRemoveWhitespace
     */
    public function testRemoveWhitespace($value, $expected)
    {
        $this->assertSame($expected, String::removeWhitespace($value));
    }

    /**
     * @covers String::normalizeWhitespace
     * @dataProvider providerNormalizeWhitespace
     */
    public function testNormalizeWhitespace($value, $expected)
    {
        $this->assertSame($expected, String::normalizeWhitespace($value));
    }

    /**
     * @covers String::removeLine
     * @dataProvider providerRemoveLine
     */
    public function testRemoveLine($value, $expected)
    {
        $this->assertSame($expected, String::removeLine($value));
    }

    /**
     * @covers String::normalize
     * @dataProvider providerNormalize
     */
    public function testNormalize($value, $expected)
    {
        $this->assertSame($expected, String::normalize($value));
    }

    /**
     * @covers String::randString
     * @dataProvider providerRandString
     */
    public function testRandString($value, $expectingFalse, $allowedChars)
    {
        if ($expectingFalse) {
            $this->assertFalse(String::randString($value));
        } else {
            if ($allowedChars) {
                $result = String::randString($value, $allowedChars);
                $this->assertRegExp('/^['.$allowedChars.']+$/', $result);
            } else {
                $result = String::randString($value);
                $this->assertRegExp('/^[a-zA-Z0-9]+$/', $result);
            }

            $this->assertEquals($value, mb_strlen($result, 'utf8'));
        }
    }

    /**
     * @covers String::slugify
     * @dataProvider providerSlugify
     */
    public function testSlugify($strValue, $bExpected)
    {
        $this->assertSame($bExpected, String::slugify($strValue));
    }

    /**
     * @covers String::beautifulise
     * @dataProvider providerBeautifulise
     */
    public function testBeautifulise($value, $expected)
    {
        $this->assertSame($expected, String::beautifulise($value));
    }

    /**
     * @covers String::minify
     * @dataProvider providerMinify
     */
    public function testMinify($value, $expected)
    {
        $this->assertSame($expected, String::minify($value));
    }
}