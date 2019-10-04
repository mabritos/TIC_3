<?php

error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 1);

date_default_timezone_set("America/Montevideo");

require("protected/config/config.php");

require("webApp/protected/autoload.php");

require("protected/vendor/autoload.php");

$_webApp = new WebApp(SITIO);

$_webApp->run();
