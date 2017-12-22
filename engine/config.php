<?php

define("BASE_URL", "http://localhost/venus/");
define("ENVIRONMENT", "development");

if (version_compare(PHP_VERSION, '5.3.0', '>=')) {
    define("WORKING_DIRECTORY", __DIR__ . "/../");
} else {
    define("WORKING_DIRECTORY", dirname(__FILE__) . "/../");
}

switch (ENVIRONMENT) {
    case "development":
        ini_set("display_errors", 1);
        break;
    case "test":
        ini_set("display_errors", 1);
        break;
    case "production":
        ini_set("display_errors", 1);
        break;
}

if (!defined("BASE_URL"))
    die("Unauthorized direct access");

require_once WORKING_DIRECTORY . 'engine/core.php';

$core = new core();
$core->start();
