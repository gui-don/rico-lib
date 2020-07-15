<?php

declare(strict_types=1);

namespace Rico\Test;

use Rico\Slib\MathUtils as StaticMathUtils;
use Rico\Lib\MathUtils;

class MathUtilsTest extends RicoTestCase
{
    /**
     * @var MathUtils
     */
    private $mathUtils;

    public function setUp(): void
    {
        $this->mathUtils = new MathUtils();
    }

    public function providerSmartRound()
    {
        return [
            ['test', 2, null], // 0
            [0.00, 'test', null],
            [null, 2, null],
            [0.00, null, null],
            [true, 2, null],
            [0.00, true, null], // 5
            [new \stdClass(), 2, null],
            [0.00, new \stdClass(), null],
            [0.00, 0, 0.0],
            [0, 3, 0.0],
            [3452, 3, 3452.0], // 10
            [34.311, 3, 34.3],
            [234.34, 3, 234.0],
            [234.34, 4, 234.3],
        ];
    }

    //--- TESTS

    /**
     * @covers Rico\Lib\MathUtils
     * @covers Rico\SLib\MathUtils
     * @dataProvider providerSmartRound
     */
    public function testSmartRound($value, $idealLength, $expected)
    {
        $this->standardStaticTest(StaticMathUtils::class, 'smartRound', [$value, $idealLength], $expected);
        $this->standardTest($this->mathUtils, 'smartRound', [$value, $idealLength], $expected);
    }
}
