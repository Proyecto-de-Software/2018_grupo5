<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 30/10/18
 * Time: 22:37
 */

require_once(CODE_ROOT . "/vendor/autoload.php");

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;



class DAO {

    public static $entityManager;
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
        if (self::$entityManager == null) {
            self::$entityManager = $this->createEntityManager();
        }
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
        if(!self::$entityManager->isOpen()) {
            echo "new enti ";
            self::$entityManager = $this->createEntityManager();
        }
        return self::$entityManager;
    }

    /**
     * @param $repository
     * @return \Doctrine\Common\Persistence\ObjectRepository|\Doctrine\ORM\EntityRepository
     * @throws Exception
     */

     function getRepository($repository=null) {
         if ($repository == null){
             echo "Repository can't be NULL in DAO->GetRepositoy(..)";
             return null;
         }
        require_once(CODE_ROOT . '/models/' . $repository . '.php');
        return $this->entityManager()->getRepository($repository);
    }

    //abstract function getModelName();

    function getCurrentRepository() {
        return $this->getRepository($this->model);
    }


    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository|\Doctrine\ORM\EntityRepository
     * @throws Exception
     */
     function getModel() {
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
        try {
            return $this->getCurrentRepository()->find($id);
        } catch (Exception $e) {
            return null;
        }
    }

    protected function findBy($array_assoc) {
        return $this->getModel()->findBy($array_assoc);
    }

    function findByMultipleId($array_of_ids) {
        return $this->getCurrentRepository()->findBy(['id' => $array_of_ids]);
    }

    protected function findOneBy($array_assoc) {
        return $this->getModel()->findOneBy($array_assoc);
    }

    function persist($entity) {
        $this->entityManager()->persist($entity);
        $this->entityManager()->flush();
    }

    function update($entity) {
        $this->entityManager()->merge($entity);
        $this->entityManager()->flush();
    }
}