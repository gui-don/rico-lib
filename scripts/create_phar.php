<?php

$directory = getcwd();
chdir(__DIR__);

$phar = new Phar('rico-lib.phar');
$phar->addFile('index.php');
$phar->buildFromDirectory('../src');

chdir($directory);
