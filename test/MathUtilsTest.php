<?php

declare(strict_types=1);

namespace Rico\Test;

use Rico\Slib\MathUtils;

class MathUtilsTest extends \PHPUnit_Framework_TestCase
{
    public function providerSmartRound()
    {
        return [
            [0.00, 0, 0.0],
            [0, 3, 0.0],
            [3452, 3, 3452.0],
            [34.311, 3, 34.3],
            [234.34, 3, 234.0],
            [234.34, 4, 234.3],
        ];
    }


    /**
     * @covers MathUtils::smartRound
     * @dataProvider providerSmartRound
     */
    public function testSmartRound($value, $idealLength, $expected)
    {
        $this->assertSame($expected, MathUtils::smartRound($value, $idealLength));
    }
}
