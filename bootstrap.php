<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once (__DIR__. "/src/vendor/autoload.php");


// Create a simple "default" Doctrine ORM configuration for Annotations
$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/../src/models"), $isDevMode, null, null, false);

$json = file_get_contents(__DIR__ .'/config/settings.json');
define('SETTINGS', json_decode($json, true));


// obtaining the entity manager
$entityManager = EntityManager::create(SETTINGS['database'], $config);

