<?php

declare(strict_types=1);

namespace Rico\Slib;

abstract class ArrayUtils
{
    /**
     * Extracts each element of a $multidimensionalArray in a single list.
     *
     * @param array<array> $multidimensionalArray
     *
     * @return array<Object>
     */
    public static function flatten(array $multidimensionalArray): array
    {
        return array_map('current', $multidimensionalArray);
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
        if (($offset = array_search($index, array_keys($haystack))) === false) {
            $haystack[$index] = $needle;
            ksort($haystack);
            return $haystack;
        }

        return array_merge(array_slice($haystack, 0, (int) $offset), [$needle], array_slice($haystack, (int) $offset));
    }

    /**
     * Extracts all $property values from a multidimensional $multidimensionalArray.
     *
     * @param array<array> $multidimensionalArray
     * @param string  $property
     *
     * @return array<Object>
     */
    public static function pluck(array $multidimensionalArray, string $property): array
    {
        return array_reduce($multidimensionalArray, function ($result, $currentArray) use ($property) {
            if (isset($currentArray[$property])) {
                $result[] = $currentArray[$property];
            }

            return $result;
        }, []);
    }

    /**
     * Transforms multiple $similarArrays into key-valued arrays.
     *
     * @param array<array> $similarArrays
     *
     * @return mixed[]
     */
    public static function transpose(array $similarArrays): array
    {
        $keyValuesArrays = [];
        foreach ($similarArrays as $globalKey => $currentArray) {
            if (!is_array($currentArray)) {
                continue;
            }

            foreach ($currentArray as $currentKey => $currentValue) {
                $keyValuesArrays[$currentKey][$globalKey] = $currentValue;
            }
        }

        return $keyValuesArrays;
    }
}
