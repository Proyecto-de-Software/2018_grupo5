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
    protected $model = null;

    /**
     * DAO constructor.
     * @throws \Doctrine\ORM\ORMException
     * @throws Exception
     */
    function __construct() {
        /*if (!$this->model) {
            $msg = "<strong>\$model</strong> must be definend in class: <strong>" . get_called_class() . "</strong>";
            throw new Exception($msg);
        }*/
        $this->entityManager = $this->createEntityManager();
    }

    private function createEntityManager() {
        return EntityManager::create(SETTINGS['database'], self::getEntityConfiguration());
    }

    private static function getEntityConfiguration() {
        $isDevMode = !SETTINGS['debug'];
        return Setup::createAnnotationMetadataConfiguration(
            [CODE_ROOT . "/src/models"],
            $isDevMode,
            null,
            null,
            false);
    }

    function entityManager() {
        if(!$this->entityManager->isOpen()) {
            $this->entityManager = $this->createEntityManager();
        }
        return $this->entityManager;
    }

    /**
     * @param $repository
     * @return \Doctrine\Common\Persistence\ObjectRepository|\Doctrine\ORM\EntityRepository
     * @throws Exception
     */

    private function getRepository($repository) {
        require_once(CODE_ROOT . '/models/' . $repository . '.php');
        return $this->entityManager()->getRepository($repository);
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
        return $this->getModel()->find($id);
    }

    protected function findBy($array_assoc) {
        return $this->getModel()->findBy($array_assoc);
    }

    function findByMultipleId($ids) {
        return $this->getModel()->findBy(['id' => $ids]);
    }

    protected function findOneBy($array_assoc) {
        return $this->getModel()->findOneBy($array_assoc);
    }

    function persist($entity){
        $this->entityManager()->persist($entity);
        $this->entityManager()->flush();
    }

    function update($entity) {
        $this->entityManager()->merge($entity);
        $this->entityManager()->flush();
    }
}