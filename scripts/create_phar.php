<?php

$phar = new Phar(__DIR__ . '/rico-lib.phar');
$phar->addFile(__DIR__ . '/index.php');
$phar->buildFromDirectory(__DIR__ . '/../src');
