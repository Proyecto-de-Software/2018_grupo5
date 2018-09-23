<?php

//this file is for the CLI doctrine tool

use Doctrine\ORM\Tools\Console\ConsoleRunner;

require_once  __DIR__ . "/../bootstrap.php";

return ConsoleRunner::createHelperSet($entityManager);



