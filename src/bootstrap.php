<?php
define('CODE_ROOT', dirname(__FILE__));
require_once "vendor/autoload.php";

$default_setting = file_get_contents(__DIR__ . '/config/settings.default.json');

$settings = [];

if ( file_exists(__DIR__ . "/config/settings.json")) {
    $override_setting = file_get_contents(__DIR__ . "/config/settings.json");
    $settings = array_merge(json_decode($default_setting, true),json_decode($override_setting, true));
} else {
    $settings = json_decode($default_setting, true);
}

define('SETTINGS', $settings, true);

