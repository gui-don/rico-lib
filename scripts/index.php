<?php


spl_autoload_register(function ($class) {
    $prefix = 'Rico\\';

    $length = mb_strlen($prefix);
    $baseDir = '';
    $relativeClass = '';
    if (0 === strncmp($prefix, $class, $length)) {
        //$baseDir =  __DIR__.'/src/';
        $relativeClass = mb_substr($class, $length);
    }

    $file = $baseDir.str_replace('\\', '/', $relativeClass).'.php';

    require_once 'phar://rico-lib.phar/'.$file;
});

