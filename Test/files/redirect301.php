<?php

header('Set-Cookie: redirection=from301');
header('Status: HTTP/1.1 301 Moved Permanently');
header("Location: httpAnswer.php");