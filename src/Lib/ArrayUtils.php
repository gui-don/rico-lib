<?php

declare(strict_types=1);

namespace Rico\Lib;

use Rico\Slib\ArrayUtils as StaticArrayUtils;

class ArrayUtils
{
    /**
     * Extracts each element of a $multidimensionalArray in a single list.
     *
     * @param array<array> $multidimensionalArray
     *
     * @return array<Object>
     */
    public function flatten(array $multidimensionalArray): array
    {
        return StaticArrayUtils::flatten($multidimensionalArray);
    }

    /**
     * Extracts all $property values from a multidimensional $multidimensionalArray.
     *
     * @param array<array> $multidimensionalArray
     * @param string  $property
     *
     * @return array<Object>
     */
    public function pluck(array $multidimensionalArray, string $property): array
    {
        return StaticArrayUtils::pluck($multidimensionalArray, $property);
    }

    /**
     * Transforms multiple $similarArrays into key-valued arrays.
     *
     * @param mixed[] $similarArrays
     *
     * @return mixed[]
     */
    public function transpose(array $similarArrays): array
    {
        return StaticArrayUtils::transpose($similarArrays);
    }
}
