<?php
use Doctrine\ORM\Tools\Console\ConsoleRunner;


require_once __DIR__ ."/../bootstrap.php";

$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration([CODE_ROOT . "/src/models"], $isDevMode, null, null, false);
$entityManager = EntityManager::create(SETTINGS['database'], $config);


return ConsoleRunner::createHelperSet($entityManager);
