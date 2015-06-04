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
            array(array('error', 'test', 'not a number'), false),
            array(array('error', 'test', 8), false),
            array(array(19,  5.2, 8.7), true),
            array(array(19, 'e7', 8.7), false)
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

    public function providerIsURL() {
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
     * @covers String::isPositiveInt
     * @dataProvider providerIsPositiveInt
     */
    public function testIsPositiveInt($value, $expected)
    {
        $this->assertSame($expected, String::isPositiveInt($value));
    }

    /**
     * @covers String::isNumber
     * @dataProvider providerIsNumber
     */
    public function testIsNumber($strValue, $bExpected)
    {
        $this->assertSame($bExpected, String::isNumber($strValue));
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
     * @covers String::isHexadecimal
     * @dataProvider providerIsHexadecimal
     */
    public function testIsHeadecimal($value, $expected)
    {
        $this->assertSame($expected, String::isHexadecimal($value));
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
	 * @covers String::isUrl
	 * @dataProvider providerIsURL
	 */
	public function testIsURL($strValue, $bExpected) {
		$this->assertSame($bExpected, String::isURL($strValue));
	}
}
