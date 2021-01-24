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
     * Inserts an element $needle at the $index position in the $haystack, conserving the order and moving other element in the way.\n
     * Careful, keys will not be preserved.
     *
     * @param mixed $needle
     * @param int $index
     * @param array<mixed> $haystack
     *
     * @return array<mixed>
     */
    public static function insert($needle, int $index, array $haystack): array
    {
        return StaticArrayUtils::insert($needle, $index, $haystack);
    }

    /**
     * Order an $array values by the number of occurrences of each element of that array. Work with any types. De-duplicates values.
     *
     * @param array[string]|array[int] $array
     *
     * @return array
     */
    public static function orderByOccurrence(array $array): array
    {
        return StaticArrayUtils::orderByOccurrence($array);
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
