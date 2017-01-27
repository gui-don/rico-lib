# Rico-lib Project #

---

<p align="center"><img src="rico.png" /></p>


## A bunch of PHP utility libraries. ##

This project is made of some lightweight and well-tested libraries.
It’s useful when you want to access some frenquently used low-level robust functions but you don’t want to import tons of dependencies.

### What does this library need to work? ###

PHP7 or superior. Not compatible with previous PHP versions.
It also needs the `php-iconv`, `php-mbstring` and `php-dom` extensions to be enabled.

### How to - Procedural style ###

    <?php

    require_once('autoload.php');

    use \Rico\Slib\StringUtils;

    // […] Some code
    // $uglyString = $object->getUglyString();

    $beautifulString = StringUtils::beautifulise($uglyString);

### How to - OOP style ###

    <?php

    require_once('autoload.php');

    use \Rico\Lib\StringUtils;

    // […] Some code
    // $uglyString = $object->getUglyString();

    $stringUtils = new StringUtils();
    $beautifulString = $stringUtils->beautifulise($uglyString);

### Installation ###

You can add this library as a local dependency to your project using [Composer](https://getcomposer.org/):

    composer require gui-don/rico-library

---

#### `FileUtils` lib ####

- `count($file, $countEmpty = false)`: Counts the number of lines in a $file.

- `addLine($file, $line)`: Adds a new $line at the end of a $file without duplication.

#### `FilesystemUtils` lib ####

- `listDirectory($path, $option)`: Gets filenames and folders names (according to $option) inside a $path.

- `createPath($path)`: Creates the completer $path with all missing intermediates directories.

- `createSymlink($link, $path)`: Creates a symbolic $link pointing to $file.

#### `StringUtils` lib ####

- `removeWhitespace($string)`: Removes all sort of spaces from a $string.

- `normalizeWhitespace($string)`: Replaces all sort of spaces (tab, nil, non-breaking…) in a $string by a simple space.

- `removeLine($string)`: Removes all sort of line breaks inside a $string.

- `normalize($string)`: Cleans a $string by removing multi-spaces, line breaks, indents and HTML tags.

- `randString($length, $allowedChars)`: Generates a random string of $length $allowedChars.

- `slugify($string)`: Transforms a $string into a ascii-only string separated by -.

- `beautifulise($uglyString)`: Transforms an $uglyString (with incorrect ponctuation) into beautiful string (with correct ponctuation).

- `minify(string $string)`: Removes whitespaces, line breaks and comment out of a $string.

- `alphaToId($string, $secret)`: Converts an alphabetic $string into an identifier (an integer).

- `IdToAlpha($integer, $secret)`: Converts a $identifier into an alphanumeric string.

- `humanFilesize($bytes)`: Gets a human readable string of a size in $bytes.

#### `ValidationUtils` lib ####

- `isPositiveInt($mixed)`: Checks that $mixed value is a positive integer (primary key).

- `isNumber($mixed)`: Checks that $mixed value is a decimal number (float or integer).

- `isHexadecimal($mixed)`: Checks that $mixed value is a hexadecimal value.

- `isURL($mixed)`: Checks that $mixed value is an URL.

- `isIp($mixed)`: Checks that $mixed value is an IP (v4 or v6).

- `isEmail($mixed)`: Checks that $mixed value is an email.

- `isPhoneNumber($string)`: Checks that $string value is a phone number.


#### `ArrayUtils` lib ####

- `flatten($multidimensionalArray)`: Extracts each element of a $multidimensionalArray in a single list.

- `pluck($multidimensionalArray, $property)`: Extracts all $property values from a multidimensional $multidimensionalArray.

- `transpose($similarArrays)`: Transforms multiple $similarArrays into key-valued arrays.

#### `MathUtils` lib ####

- `smartRound($number, $idealLength)`: Rounds a $number adding decimal part only when int part of $number < $idealLength.

#### `UrlUtils` lib ####

- `getResourceName($url)`: Gets the name of a resource (image, pdf, …) out of an $url.

- `getUrlWithoutResourceName($url)`: Gets the URL without the resource (image, pdf, …).
