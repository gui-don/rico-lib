<?php

namespace Rico\Lib;

abstract class ArrayUtils
{
    /**
     * Extracts all $property values from a multidimensional $multidimensionalArray.
     *
     * @param mixed[] $multidimensionalArray
     * @param string  $property
     *
     * @return mixed[]
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
     * @param mixed[] $similarArrays
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
