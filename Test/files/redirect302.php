<?php

header('Set-Cookie: redirection=from302');
header('Status: HTTP/1.1 302 Moved Temporarly');
header("Location: httpAnswer.php");