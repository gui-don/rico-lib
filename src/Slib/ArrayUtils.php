<?php

declare(strict_types=1);

namespace Rico\Slib;

abstract class ArrayUtils
{
    /**
     * Extracts each element of a $multidimensionalArray in a single list. Does not preserve any keys.
     *
     * @param array<array> $multidimensionalArray
     *
     * @return array<Object>
     */
    public static function flatten(array $multidimensionalArray): array
    {
        return iterator_to_array(new \RecursiveIteratorIterator(new \RecursiveArrayIterator($multidimensionalArray)), false);
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
     * Order an $array values by the number of occurrences of each element of that array. Work with any types.  De-duplicates values.
     * @param array<mixed> $array
     *
     * @return array<mixed>
     */
    public static function orderByOccurrence(array $array): array
    {
        $buffer = [];
        foreach ($array as $key => $element) {
            if (is_array($element)) {
                $keyElement = implode(',', $element);
            } elseif (is_object($element)) {
                $keyElement = spl_object_hash($element);
            } else {
                $keyElement = strval($element);
            }

            if (isset($buffer[$keyElement])) {
                $buffer[$keyElement]['count'] += 1;
            } else {
                $buffer[$keyElement]['count'] = 1;
                $buffer[$keyElement]['value'] = $element;
            }
        }

        usort($buffer, function ($a, $b) {
            if ($a === $b) {
                return 0;
            }

            return ($a['count'] < $b['count']) ? 1 : -1;
        });

        return array_column($buffer, 'value');
    }

    /**
     * Extracts all $property values from a multidimensional $multidimensionalArray.
     * @deprecated Use array_column
     * @param array<array> $multidimensionalArray
     * @param string  $property
     *
     * @return array<Object>
     */
    public static function pluck(array $multidimensionalArray, string $property): array
    {
        return array_column($multidimensionalArray, $property);
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
