<?php

if (isset($_SERVER['HTTP_REFERER'])) {
    echo $_SERVER['HTTP_REFERER'];
}

header('Set-Cookie: MagicCookie=NEW');