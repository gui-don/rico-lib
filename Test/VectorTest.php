<?php

namespace Test\VectorTest;

use \Lib\Vector;

class VectorTest extends \PHPUnit_Framework_TestCase
{
    public function providerPluck()
    {
        $data[] = array('categoryId' => 1, 'eventId' => 2, 'eventName' => 3, 'vendorName' => 4);
        $data[] = array('categoryId' => 5, 'eventId' => 6, 'eventName' => 7, 'vendorName' => 8);
        $data[] = array('categoryId' => 9, 'eventId' => 10, 'eventName' => 11, 'vendorName' => 12);
        $data[] = array('eventId' => 10, 'eventName' => 11, 'vendorName' => 12);
        $data[] = array('categoryId' => false, 'eventId' => 10, 'eventName' => 11, 'vendorName' => 12);
        $data[] = array('categoryId' => 0.0, 'eventId' => 10, 'eventName' => 11, 'vendorName' => 12);

        return array(
            array(array(), 'test', array()), // 0
            array(array('666'), 'test', array()),
            array(array('OK' => array()), 'OK', array()),
            array(array(array('key' => 2), array('key' => 6, 'other' => 7)), 'key', array(2, 6)),
            array($data, 'categoryId', array(1, 5, 9, false, 0.0)),
            array($data, 'vendorName', array(4, 8, 12, 12, 12, 12)), // 5
            array($data, 'eventId', array(2, 6, 10, 10, 10, 10)),
            array(array(array("text1", "text2"), array("text3")), 0, array('text1', 'text3'))
        );
    }

    public function providerTranspose()
    {
        return array(
            array(array(), array()), // 0
            array(array('666'), array()),
            array('ok', null),
            array(array(array(12, 3)), array(array(12), array(3))),
            array(array(array('key' => 2), array('key' => 6, 'other' => 7)), array('key' => array(2, 6), 'other' => array(1 => 7))),
            array(array(array('first' => 1, 'second' => 2, 'third' => 3), array('first' => 1), array('first' => 1, 'third' => 3, 'second' => 2)), array('first' => array(1, 1, 1), 'second' => array(0 => 2, 2 => 2), 'third' => array(0 => 3, 2 => 3))), // 5
        );
    }

    /**
     * @covers Vector::pluck
     * @dataProvider providerPluck
     */
    public function testPluck($array, $property, $expected)
    {
        $this->assertSame($expected, Vector::pluck($array, $property));
    }

    /**
     * @covers Vector::transpose
     * @dataProvider providerTranspose
     */
    public function testTranspose($array, $expected)
    {
        $this->assertSame($expected, Vector::transpose($array));
    }
}
