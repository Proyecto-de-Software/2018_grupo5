<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

require_once(CODE_ROOT . "/vendor/autoload.php");

$json_settings = json_decode(file_get_contents(CODE_ROOT . '/config/settings.default.json'), true);

if (file_exists(CODE_ROOT . "/config/settings.json")) {
    $json_settings_override = json_decode(file_get_contents(CODE_ROOT . '/config/settings.default.json'), true);
    array_merge($json_settings, $json_settings_override);
}

define('SETTINGS', $json_settings, true);

// Create a simple "default" Doctrine ORM configuration for Annotations
$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration([CODE_ROOT . "/src/models"], $isDevMode, null, null, false);

$entityManager = EntityManager::create(SETTINGS['database'], $config);

