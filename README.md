# Rico Libraries

<div dir="ltr" class="center">
    <p dir="ltr" align="center"><img width="153" height="146" src="rico.png" /></p>
</div>

## PHP utility libraries ##

This project is made of some lightweight and well-tested libraries.

They are useful to access some frequently used low-level robust functions while not wanting to import tons of dependencies.

### Installation ###

##### Composer

    composer require gui-don/rico-library

##### Manual with source

Import the `autoload.php` file in your code:

```php
require_once('autoload.php');

use \Rico\Lib\StringUtils;

// etc
```

##### Manual with Phar

- Download latest phar file on [the releases page](https://gitlab.com/gui-don/rico-lib/-/releases).
- Import it in your code:

```php
require_once('phar://rico-lib.phar');

use \Rico\Lib\StringUtils;

// etc
```

### Documentation ###

#### How to - Procedural style (recommended in data object)

```php
<?php

require_once('autoload.php');

use \Rico\Slib\StringUtils;

// […] Some code
// $uglyString = getUglyString();

$beautifulString = StringUtils::beautifulise($uglyString);
```

#### How to - OOP style (recommended anywhere else)

```php
<?php

// autoload Not need if using composer
// require_once('autoload.php');

use \Rico\Lib\StringUtils;

// […] Some code
// $uglyString = $object->getUglyString();

$stringUtils = new StringUtils();
$beautifulString = $stringUtils->beautifulise($uglyString);
```

Missing a function? Make a pull request or simply extends the following classes.

---

#### `ArrayUtils` lib ####

- `flatten(array $multidimensionalArray): array`: Extracts each element of a $multidimensionalArray in a single list. Does not preserve any keys.

```php
    // […]
    $array = [1, 2, 3], ['test' => 2], [], [2, 5, 6, 7]];

    var_dump(ArrayUtils::flatten($array));
    // Result: [1, 2, 3, 2, 2, 5, 6, 7]
```

- `insert($needle, int $index, array $haystack): array`: Inserts an element $needle at the $index position in the $haystack, conserving the order and moving other element in the way. Does not preserve any keys.

```php
    // […]
    $needle = 'replace';
    $index = 10;
    $haystack = [0 => 'zero', 10 => 'misplaced', 14 => 'next'];

    var_dump(ArrayUtils::insert($needle, $index, $haystack));
    // Result: [0 => 'zero', 1 => 'replace', 2 => 'misplaced', 3 => 'next']
```

- `orderByOccurrence(array $array): array`: Order an $array values by the number of occurrences of each element of that array. Work with any types. De-duplicates values.

```php
    // […]
    $array = [1, 2, 3, 4, 5, 4, 5, 4];

    var_dump(ArrayUtils::orderByOccurrence($array));
    // Result: [4, 5, 1, 2, 3]
```

- `transpose(array $similarArrays): array`: Transforms multiple $similarArrays into key-valued arrays.

```php
    // […]
    $similarArrays = ['first' => 1, 'second' => 2, 'third' => 3], ['first' => 1], ['first' => 1, 'third' => 3, 'second' => 2];

    var_dump(ArrayUtils::transpose($similarArrays));
    // Result: ['first' => [1, 1, 1], 'second' => [2, 2], 'third' => [3, 3]]
```

#### `FileUtils` lib ####

- `addLine($file, $line)`: Adds a new $line at the end of a $file without duplication.

- `count($file, $countEmpty = false)`: Counts the number of lines in a $file.

- `extractExtension(string $filename)`: Extracts the extension (without the dot) of a filename alone or contained in a path.

#### `FilesystemUtils` lib ####

- `createPath($path)`: Creates the completer $path with all missing intermediates directories.

- `createSymlink($link, $path)`: Creates a symbolic $link pointing to $file.

- `listDirectory($path, $option)`: Gets filenames and folders names (according to $option) inside a $path.

#### `StringUtils` lib ####

- `alphaToId($string, $secret)`: Converts an alphabetic $string into an identifier (an integer).

- `beautifulise($uglyString)`: Transforms an $uglyString (with incorrect ponctuation) into beautiful string (with correct ponctuation).

- `humanFilesize($bytes)`: Gets a human readable string of a size in $bytes.

- `idToAlpha($integer, $secret)`: Converts a $identifier into an alphanumeric string.

- `minify(string $string)`: Removes whitespaces, line breaks and comment out of a $string.

- `normalize($string)`: Cleans a $string by removing multi-spaces, line breaks, indents and HTML tags.

- `normalizeWhitespace($string)`: Replaces all sort of spaces (tab, nil, non-breaking…) in a $string by a simple space.

- `randString($length, $allowedChars)`: Generates a random string of $length $allowedChars.

- `removeBracketContent($string)`: Removes brackets and its content from a $string.

- `removeLine($string)`: Removes all sort of line breaks inside a $string.

- `removeWhitespace($string)`: Removes all sort of spaces from a $string.

- `slugify($string)`: Transforms a $string into a ascii-only string separated by -.

- `underscoreToSpace($string)`: Replaces underscore in $string by spaces.

#### `ValidationUtils` lib ####

- `isEmail($mixed)`: Checks that $mixed value is an email.

- `isHexadecimal($mixed)`: Checks that $mixed value is a hexadecimal value.

- `isIp($mixed)`: Checks that $mixed value is an IP (v4 or v6).

- `isNumber($mixed)`: Checks that $mixed value is a decimal number (float or integer).

- `isPhoneNumber($string)`: Checks that $string value is a phone number.

- `isPositiveInt($mixed)`: Checks that $mixed value is a positive integer (primary key).

- `isURL($mixed)`: Checks that $mixed value is an URL.

- `isURLMagnet($mixed)`: Checks that $mixed value is a magnet URL.

#### `MathUtils` lib ####

- `smartRound($number, $idealLength)`: Rounds a $number adding decimal part only when int part of $number < $idealLength.

#### `UrlUtils` lib ####

- `getResourceName($url)`: Gets the name of a resource (image, pdf, …) out of an $url.

- `stripResourceName($url)`: Gets the URL without the resource (image, pdf, …).
