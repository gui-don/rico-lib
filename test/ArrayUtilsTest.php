<?php

namespace Rico\Test\VectorTest;

use Rico\Lib\ArrayUtils;

class ArrayUtilsTest extends \PHPUnit_Framework_TestCase
{
    public function providerFlatten()
    {
        return [
            [[], []], // 0
            [[['666']], ['666']],
            [['OK' => []], ['OK' => false]],
            [[['key' => 2]], [2]],
            [[[1], [2], [3], [4]], [1, 2, 3, 4]],
            [[[['array1']], [2], [['array2']], [4]], [['array1'], 2, ['array2'], 4]], // 5
            [[['value' => 1], ['value' => 2, 'ignored' => 4], ['accept' => 3, 'value' => 4]], [1, 2, 3]],
        ];
    }

    public function providerPluck()
    {
        $data[] = ['categoryId' => 1, 'eventId' => 2, 'eventName' => 3, 'vendorName' => 4];
        $data[] = ['categoryId' => 5, 'eventId' => 6, 'eventName' => 7, 'vendorName' => 8];
        $data[] = ['categoryId' => 9, 'eventId' => 10, 'eventName' => 11, 'vendorName' => 12];
        $data[] = ['eventId' => 10, 'eventName' => 11, 'vendorName' => 12];
        $data[] = ['categoryId' => false, 'eventId' => 10, 'eventName' => 11, 'vendorName' => 12];
        $data[] = ['categoryId' => 0.0, 'eventId' => 10, 'eventName' => 11, 'vendorName' => 12];

        return [
            [[], 'test', []], // 0
            [['666'], 'test', []],
            [['OK' => []], 'OK', []],
            [[['key' => 2], ['key' => 6, 'other' => 7]], 'key', [2, 6]],
            [$data, 'categoryId', [1, 5, 9, false, 0.0]],
            [$data, 'vendorName', [4, 8, 12, 12, 12, 12]], // 5
            [$data, 'eventId', [2, 6, 10, 10, 10, 10]],
            [[['text1', 'text2'], ['text3']], 0, ['text1', 'text3']],
        ];
    }

    public function providerTranspose()
    {
        return [
            [[], []], // 0
            [['666'], []],
            [[[12, 3]], [[12], [3]]],
            [[['key' => 2], ['key' => 6, 'other' => 7]], ['key' => [2, 6], 'other' => [1 => 7]]],
            [[['first' => 1, 'second' => 2, 'third' => 3], ['first' => 1], ['first' => 1, 'third' => 3, 'second' => 2]], ['first' => [1, 1, 1], 'second' => [0 => 2, 2 => 2], 'third' => [0 => 3, 2 => 3]]], // 5
        ];
    }

    /**
     * @covers ArrayUtils::flatten
     * @dataProvider providerFlatten
     */
    public function testFlatten($array, $expected)
    {
        $this->assertSame($expected, ArrayUtils::flatten($array));
    }

    /**
     * @covers ArrayUtils::pluck
     * @dataProvider providerPluck
     */
    public function testPluck($array, $property, $expected)
    {
        $this->assertSame($expected, ArrayUtils::pluck($array, $property));
    }

    /**
     * @covers ArrayUtils::transpose
     * @dataProvider providerTranspose
     */
    public function testTranspose($array, $expected)
    {
        $this->assertSame($expected, ArrayUtils::transpose($array));
    }
}
