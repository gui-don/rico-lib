# Rico-lib Project #

---

___________________________________![rico](rico.png)___________________________________


## A bunch of PHP utility libraries. ##

### What does this library need to work? ###

Nothing. Just a recent version of PHP.

---

#### File lib ####

- **listDirectory($path, $option)** : Get filenames and folders names inside a directory

- **createPath($path)** : Create all missing directories from a path


#### String lib ####

- **removeWhitespace($string)** : Remove all sort of spaces from a string

- **normalizeWhitespace($string)** : Replace all sort of spaces by a simple space

- **removeLine($string)** : Remove all sort of line breaks

- **normalize($string)** : Clean a string by removing multi-spaces, line breaks, indents and HTML tags

- **randString($length, $allowedChars)** : Generates a random string of alphanumeric characters

- **slugify($string)** : Transform a random string into a ascii-only string

- **beautifulise($string)** : Transform an ugly string (with incorrect ponctuation) into beautiful string (with correct ponctuation)

- **getResourceNameInUrl($url)** : Get the name of a resource (image, pdf, ...) out of an URL


#### Checker lib ####

- **isPositiveInt($mixed)** : Checks a variable to be a positive integer (primary key)

- **isNumber($mixed)** : Check a value to be a decimal number (float or integer)

- **isHexadecimal($string)** : Check a value to be a hexadecimal value

- **isURL($string)** : Check a string to be an URL

- **isEmail($string)** : Check a string to be an email

- **isPhoneNumber($string)** : Check a string to be a phone number


#### Vector lib ####

- **pluck($data, $property)** : Extract all property values from a multidimensional array

- **transpose($array)** : Transform multiple similar arrays into key-valued arrays
