<?php
use Doctrine\ORM\Tools\Console\ConsoleRunner;

require_once CODE_ROOT . '/bootstrap.php';

return ConsoleRunner::createHelperSet($entityManager);