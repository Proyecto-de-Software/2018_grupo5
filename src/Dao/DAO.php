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


class DAO {
    private $entityManager;
    public $model=null;


    function __construct() {
        if (!$this->model) {
            throw new Exception("<strong>\$model</strong> must be definend in class: <strong>" . get_called_class() . "</strong>");
        }
        $this->entityManager = EntityManager::create(SETTINGS['database'], self::getEntityConfiguration());

    }

    private static function getEntityConfiguration() {
        $isDevMode = true;
        return Setup::createAnnotationMetadataConfiguration(
            [CODE_ROOT . "/src/models"],
            $isDevMode,
            null,
            null,
            false);
    }

    /**
     * @param $repository
     * @return \Doctrine\Common\Persistence\ObjectRepository|\Doctrine\ORM\EntityRepository
     * @throws Exception
     */
    public function getModel($repository) {
        if (!$repository || $repository=="") {
            throw new Exception("\$model must be definend in class: <strong>" . get_called_class() . "</strong>");
        }
        require_once(CODE_ROOT . '/models/' . $repository . '.php');
        return $this->entityManager->getRepository($repository);
    }


    /**
     * @return array|object[]
     * @throws Exception
     */
    function getAll() {
        return $this->getModel($this->model)->findAll();
    }

    function getById($id) {
        return $this->getModel($this->model)->findBy(['id'=>$id]);

    }

}