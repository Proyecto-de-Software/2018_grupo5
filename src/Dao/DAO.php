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


class DAO extends Singleton {

    private $entityManager;
    public $model=null;
    private $repository;

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
    private function getRepository($repository) {

        if (isset($this->repository)){
            return $this->repository;
        }

        if (!$repository || $repository=="") {
            throw new Exception("\$model must be definend in class: <strong>" . get_called_class() . "</strong>");
        }
        require_once(CODE_ROOT . '/models/' . $repository . '.php');
        $this->repository = $this->entityManager->getRepository($repository);
        return $this->repository;
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository|\Doctrine\ORM\EntityRepository
     * @throws Exception
     */
    protected function getModel() {
        return $this->getRepository($this->model);
    }

    /**
     * @return array|object[]
     * @throws Exception
     */
    function getAll() {
        return $this->getModel()->findAll();
    }


    function getById($id) {
        return $this->getModel()->findOneBy(['id' => $id]);
    }

    protected function findBy($array_assoc) {
        return $this->getModel()->findBy($array_assoc);
    }

    protected function findOneBy($array_assoc) {
        return $this->getModel()->findOneBy($array_assoc);
    }

    function persist($entity){
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    function update($entity) {
        $this->entityManager->merge($entity);
        $this->entityManager->flush();
    }
}