<?php

//this file is for the CLI doctrine tool

use Doctrine\ORM\Tools\Console\ConsoleRunner;

require_once  __DIR__ . "/../bootstrap.php";


// Setup Doctrine
$configuration = Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(
    $paths = [CODE_ROOT . '/models'],
    $isDevMode = true,
    null,
    null,
    false
);

// Get the entity manager
$entityManager = Doctrine\ORM\EntityManager::create(SETTINGS['database'], $configuration);

return ConsoleRunner::createHelperSet($entityManager);



