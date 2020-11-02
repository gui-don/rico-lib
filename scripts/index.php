<?php

foreach (glob(__DIR__."/../src/Slib/*.php") as $filename) {
    include $filename;
}

foreach (glob(__DIR__."/../src/Lib/*.php") as $filename) {
    include $filename;
}
