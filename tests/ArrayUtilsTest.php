<?php

declare(strict_types=1);

namespace Rico\Test;

use Rico\Slib\ArrayUtils as StaticArrayUtils;
use Rico\Lib\ArrayUtils;

class ArrayUtilsTest extends RicoTestCase
{
    /**
     * @var ArrayUtils
     */
    private $arrayUtils;

    public function setUp(): void
    {
        $this->arrayUtils = new ArrayUtils();
    }

    public function providerFlatten()
    {
        return [
            [[], []], // 0
            [[['666']], ['666']],
            [['OK' => []], []],
            [[['key' => 2]], [2]],
            [[[1], [2], [3], [4]], [1, 2, 3, 4]],
            [[[['array1']], [2], [['array2']], [4]], ['array1', 2, 'array2', 4]], // 5
            [[['value' => 1], ['value' => 2, 'ignored' => 4], ['accept' => 3, 'value' => 4]], [1, 2, 4, 3, 4]],
            [[[1, 2, 3], [2], [], [2, 5, 6, 7]], [1, 2, 3, 2, 2, 5, 6, 7]],
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
            [[['text1', 'text2'], ['text3']], '0', ['text1', 'text3']],
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

    public function providerInsert()
    {
        return [
            [[], 0, [], [[]]], // 0
            ['value', 4, [5 => 'test'], [4 => 'value', 5 => 'test']],
            ['three', 2, ['one', 'two', 'four'], ['one', 'two', 'three', 'four']],
            [20, 20, [5 => 5, 25 => 25, 30 => 30], [5 => 5, 20 =>20, 25 => 25, 30 => 30]],
            ['replace', 1, [0 => 'zero', 1 => 'misplaced', 2 => 'next'], [0 => 'zero', 1 => 'replace', 2 => 'misplaced', 3 => 'next']],
            ['replace', 10, [0 => 'zero', 10 => 'misplaced', 14 => 'next'], [0 => 'zero', 1 => 'replace', 2 => 'misplaced', 3 => 'next']],
        ];
    }

    //--- TESTS

    /**
     * @covers Rico\Lib\ArrayUtils
     * @covers Rico\SLib\ArrayUtils
     * @dataProvider providerFlatten
     */
    public function testFlatten($array, $expected)
    {
        $this->assertSame($expected, StaticArrayUtils::flatten($array));
        $this->assertSame($expected, $this->arrayUtils->flatten($array));
    }

    /**
     * @covers Rico\Lib\ArrayUtils
     * @covers Rico\SLib\ArrayUtils
     * @dataProvider providerPluck
     */
    public function testPluck($array, $property, $expected)
    {
        $this->assertSame($expected, StaticArrayUtils::pluck($array, $property));
        $this->assertSame($expected, $this->arrayUtils->pluck($array, $property));
    }

    /**
     * @covers Rico\Lib\ArrayUtils
     * @covers Rico\SLib\ArrayUtils
     * @dataProvider providerTranspose
     */
    public function testTranspose($array, $expected)
    {
        $this->assertSame($expected, StaticArrayUtils::transpose($array));
        $this->assertSame($expected, $this->arrayUtils->transpose($array));
    }


    /**
     * @covers Rico\Lib\ArrayUtils
     * @covers Rico\SLib\ArrayUtils
     * @dataProvider providerInsert
     */
    public function testInsert($needle, $index, $haystack, $expected)
    {
        $this->assertSame($expected, StaticArrayUtils::insert($needle, $index, $haystack));
        $this->assertSame($expected, $this->arrayUtils->insert($needle, $index, $haystack));
    }
}
