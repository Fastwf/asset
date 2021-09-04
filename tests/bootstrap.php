<?php declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';


// Install error handler to throw errors like fastwf engine
function onError($severity, $errMessage, $errFile = null, $errLine = null, $errContext = null) {
    throw new \ErrorException($errMessage, 0, $severity, $errFile, $errLine);
}


set_error_handler("onError");


if (!function_exists('apache_request_headers')) {
    // Create fake apache_request_headers method
    function apache_request_headers() {
        return [];
    }
}
