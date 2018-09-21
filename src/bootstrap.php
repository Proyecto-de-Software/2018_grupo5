<?php

require_once(CODE_ROOT . "/vendor/autoload.php");

$default_setting = file_get_contents(CODE_ROOT . '/config/settings.default.json');
$settings = [];

if ( file_exists(CODE_ROOT . "/config/settings.json")) {
    $override_setting = file_get_contents(CODE_ROOT . "/config/settings.json");
    $settings = array_merge(json_decode($default_setting, true),json_decode($override_setting, true));
} else {
    $settings = json_decode($default_setting, true);
}

define('SETTINGS', $settings, true);

