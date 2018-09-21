<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

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
var_dump(SETTINGS);
// Create a simple "default" Doctrine ORM configuration for Annotations
$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration([CODE_ROOT . "/src/models"], $isDevMode, null, null, false);
$entityManager = EntityManager::create(SETTINGS['database'], $config);

