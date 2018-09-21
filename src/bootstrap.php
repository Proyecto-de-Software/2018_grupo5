<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

require_once(CODE_ROOT . "/vendor/autoload.php");


// Create a simple "default" Doctrine ORM configuration for Annotations
$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration([CODE_ROOT . "/src/models"], $isDevMode, null, null, false);

$json = file_get_contents(CODE_ROOT . '/config/settings.json');
define('SETTINGS', json_decode($json, true));


// obtaining the entity manager
$entityManager = EntityManager::create(SETTINGS['database'], $config);

