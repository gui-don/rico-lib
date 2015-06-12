<?php

ob_start();
print_r($_SERVER);
$encodeString = gzencode("<pre>".ob_get_clean()."</pre>");

echo $encodeString;


header('Set-Cookie: MagicCookie=YUMMY; another=one');
header('Content-Encoding: gzip');
