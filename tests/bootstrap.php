<?php declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';


// Install error handler to throw errors like fastwf engine
function onError($severity, $errMessage, $errFile = null, $errLine = null, $errContext = null) {
    throw new \ErrorException($errMessage, 0, $severity, $errFile, $errLine);
}


set_error_handler("onError");
