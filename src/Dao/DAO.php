<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 30/10/18
 * Time: 22:37
 */

use Doctrine\ORM\EntityManager;
require_once(CODE_ROOT . "/vendor/autoload.php");

use Doctrine\ORM\Tools\Setup;


abstract class DAO {
    private $entityManager;
    public $model;

    function __construct() {
        $this->entityManager = EntityManager::create(SETTINGS['database'], self::getEntityConfiguration());
    }

    private static function getEntityConfiguration() {
        // Create a simple "default" Doctrine ORM configuration for Annotations
        $isDevMode = true;
        return Setup::createAnnotationMetadataConfiguration(
            [CODE_ROOT . "/src/models"],
            $isDevMode,
            null,
            null,
            false);
    }

    public function getModel($repository) {
        require_once(CODE_ROOT . '/models/' . $repository . '.php');
        return $this->entityManager->getRepository($repository);
    }

    /**
     * @return array|object[]
     */
    function getAll() {
        return $this->getModel($this->model)->findAll();
    }


}