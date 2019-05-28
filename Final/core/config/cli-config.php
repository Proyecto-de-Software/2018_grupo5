<?php

//this file is for the CLI doctrine tool
use Doctrine\ORM\Tools\Console\ConsoleRunner;

require_once FW_CODE_ROOT . "/SiMiL.php";

// Setup Doctrine
$configuration = Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(
    $paths = [__DIR__ . '/../models'],
    $isDevMode = true,
    null,
    null,
    false
);

// Get the entity manager
$entityManager = Doctrine\ORM\EntityManager::create(SETTINGS['database'], $configuration);

return ConsoleRunner::createHelperSet($entityManager);
