<?php
declare(strict_types=1);
if (version_compare(PHP_VERSION, '7.0.0', '<')) {
    throw new Exception('This library require PHP 7.0 minimum');
}

/*
 * Register the autoloader.
 * Based off the official PSR-4 autoloader example found here:
 * https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader-examples.md
 *
 * @param string $class The fully-qualified class name.
 * @return void
 */
spl_autoload_register(function ($class) {
    $prefix = 'Rico\\';
    $prefixTest = 'Rico\\Test\\';

    $length = mb_strlen($prefix);
    $lengthTest = mb_strlen($prefixTest);
    if (strncmp($prefix, $class, $length) === 0) {
        $baseDir = defined('UTILITY_BASE_DIR') ? UTILITY_BASE_DIR : __DIR__.'/src/';
        $relativeClass = mb_substr($class, $length);
    }
    if (strncmp($prefixTest, $class, $lengthTest) === 0) {
        $baseDir = defined('UTILITY_BASE_DIR') ? UTILITY_BASE_DIR : __DIR__.'/test/';
        $relativeClass = mb_substr($class, $lengthTest);
    }

    if (empty($baseDir)) {
        return;
    }

    $file = $baseDir.str_replace('\\', '/', $relativeClass).'.php';

    if (file_exists($file)) {
        require $file;
    }
});
