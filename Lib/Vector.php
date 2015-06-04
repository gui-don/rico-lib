<?php

namespace Lib;

/**
 * Library Vector
 */
abstract class Vector
{
    /**
     * Extract all property values from a multidimensional array
     * @param mixed[] $data Multidimensional array
     * @param mixed $property Property to extract
     * @return mixed[]
     */
    public static function pluck($data, $property)
    {
        return array_reduce($data, function($result, $array) use($property) {
            isset($array[$property]) && $result[] = $array[$property];
            return $result;
        }, array());
    }

	/**
	 * Transform multiple similar arrays into key-valued arrays
	 * @see Test cases for more information
	 * @param mixed[] $array
	 */
	public static function transpose($array)
	{
		if (!is_array($array)) {
				return null;
		}

		$out = array();
		foreach ($array as $key => $subarr) {
			if (!is_array($subarr)) {
				continue;
			}

			foreach ($subarr as $subkey => $subvalue) {
				$out[$subkey][$key] = $subvalue;
			}
		}

		return $out;
	}
}
