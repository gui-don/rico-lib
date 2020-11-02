<?php

foreach (glob("src/Slib/*.php") as $filename) {
    include $filename;
}

foreach (glob("src/Lib/*.php") as $filename) {
    include $filename;
}
