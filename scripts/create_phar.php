<?php

$phar = new Phar('rico-lib.phar');
$phar->addFile('index.php');
$phar->buildFromDirectory('../src');

