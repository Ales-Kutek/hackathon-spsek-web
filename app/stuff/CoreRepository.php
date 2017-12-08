<?php
/**
 * Created by PhpStorm.
 * User: ales
 * Date: 28.5.17
 * Time: 18:09
 */

namespace UW\Core\ORM\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\NoResultException;
use Kdyby\Doctrine\EntityDao;
use Kdyby\Doctrine\QueryBuilder;
use Netpromotion\Profiler\Profiler;
use Nette\Object;
use Nette\Utils\ArrayHash;
use phpDocumentor\Reflection\Types\Null_;

/**
 * Class CoreRepository
 * @package Core\ORM\Repository
 */
abstract class CoreRepository extends Object
{
    /** @var bool */
    public static $front = FALSE;

    /**
     * @return \Kdyby\Doctrine\EntityManager
     */
    abstract protected function getEntityManager();

    /**
     * @return EntityDao
     */
    abstract public function getDao();


    /**
     * @return \Kdyby\Doctrine\Mapping\ClassMetadata
     */
    public function getClassMetadata()
    {
        if (method_exists($this->getDao(), "getRepoUtils")) {
            $classMetadata = $this->getDao()->getRepoUtils()->getClassMetadata();
        } else {
            $classMetadata = $this->getDao()->getClassMetaData();
        }

        return $classMetadata;
    }

    /**
     * získá entitu na základě id (tohle využívat při přiřazování associací, je to rychlejší)
     * @param mixed $id
     * @param string $entityName
     * @return mixed
     */
    public function getRawSingleEntityById($id, string $entityName = NULL) {
        $repository = $this->getDao();

        if ($entityName === NULL) {
            $entityName = $repository->getClassName();
        }

        try {
            $entity = $repository
                ->createQueryBuilder("u")
                ->where("u.id = :id")
                ->setParameter("id", $id)
                ->getQuery()
                ->getSingleResult();
        } catch (NoResultException $ex) {
            return NULL;
        } catch (\Exception $ex) {
            throw $ex;
        }

        return $entity;
    }

    /**
     * vloží nový záznam z formuláře do DB
     * @param ArrayHash $data
     * @param callable|null $preConvert
     * @param callable|null $aftConvert
     * @return mixed
     */
    public function insertForm(array $data) {
        $repository = $this->getDao();

        $className = $repository->getClassName();
        $entity = new $className();

        if (method_exists($repository, "getRepoUtils")) {
            $classMetadata = $repository->getRepoUtils()->getClassMetadata();
        } else {
            $classMetadata = $repository->getClassMetaData();
        }

        $conv = new FormToEntity($data, $classMetadata, $this->getEntityManager(), $entity);

        $this->getEntityManager()->persist($entity);

        $this->getEntityManager()->flush();

        $conv->runOnPostFlush($this);

        $this->getEntityManager()->flush();

        return $entity;
    }

    /**
     * editne záznam z formuláře
     * @param array $data
     * @param mixed $id
     * @return mixed
     */
    public function updateForm(array $data, $id = NULL) {
        $repository = $this->getDao();

        if ($id === NULL) {
            $id = $data["id"];
        }
        
        $entity = $this->getRawSingleEntityById($id);

        if (method_exists($repository, "getRepoUtils")) {
            $classMetadata = $repository->getRepoUtils()->getClassMetadata();
        } else {
            $classMetadata = $repository->getClassMetaData();
        }

        $conv = new FormToEntity($data, $classMetadata, $this->getEntityManager(), $entity);

        $this->getEntityManager()->persist($entity);

        $this->getEntityManager()->flush();

        $conv->runOnPostFlush($this);

        $this->getEntityManager()->flush();

        return $entity;
    }

    public function getSimpleList(string $column)
    {
        return $this->getPairs(function($entity) use ($column) {
            return $entity->{$column};
        });
    }

    public function getPairsWithRawData($data, callable $callbackValue, callable $callbackKey = NULL)
    {
        $result = array();

        foreach ($data as $k => $v) {
            $primalKey = $v->id;

            if ($callbackKey !== NULL) {
                $primalKey = $callbackKey($v);
            }

            $value = $callbackValue($v);

            $result[$primalKey] = $value;
        }

        return $result;
    }

    /**
     * získá páry
     * @param string $name
     * @param string $key
     * @return array
     */
    public function getPairs(callable $callbackValue, callable $callbackKey = NULL) {
        $queryData = $this->getBaseQuery()->getQuery()->getResult();

        $result = $this->getPairsWithRawData($queryData, $callbackValue, $callbackKey);

        return $result;
    }

    /**
     * získá základní query včetně potřebných joinů
     * @return \Kdyby\Doctrine\QueryBuilder
     */
    public function getBaseQuery(bool $joins = TRUE) {
        $className = $this->getDao()->getClassName();
        
//        dump($className);
//        die();
        

        $query = $this->getEntityManager()->createQueryBuilder()
            ->select("u")
            ->from($className, "u");

        if ($joins) {
            $this->getJoins($query);
        }
        return $query;
    }

    /**
     * @return QueryBuilder
     */
    public function getCountQuery(array $criteria = array(), array $orderBy = array(), $limit = NULL, $offset = NULL)
    {
        $countQuery = $this->getByQuery($criteria, $orderBy, $limit, $offset)->select("count(u)");

        return $countQuery;
    }

    public function count(array $criteria = array(), array $orderBy = array(), $limit = NULL, $offset = NULL)
    {
        $count = $this->getCountQuery($criteria, $orderBy, $limit, $offset)->getQuery()->getSingleScalarResult();

        return $count;
    }

    /**
     * Metoda, která je nutná přepsat, pokud jsou potřeba joiny
     * @param \Kdyby\Doctrine\QueryBuilder $query
     * @return \Kdyby\Doctrine\QueryBuilder
     */
    protected function getJoins(\Kdyby\Doctrine\QueryBuilder &$query) {
        return $query;
    }

    /**
     * získá entitu na základě ID
     * @param mixed $id
     * @param bool $getQuery
     * @param string $keyName
     * @return mixed
     * @throws \Exception
     */
    public function getSingle($id, bool $getQuery = FALSE, $keyName = "id") {
        $query = $this->getBaseQuery()
            ->where("u.$keyName = :id")
            ->setParameter("id", $id);

        if ($getQuery) {
            return $query;
        }

        try {
            $result = $query->getQuery()->getSingleResult();
        } catch (NoResultException $ex) {
            return NULL;
        } catch (\Exception $ex) {
            throw $ex;
        }

        return $result;
    }

    /**
     * získá data pro formulář na základě ID
     * @param mixed $id
     * @return array
     */
    public function getFormDataById($id, array $exceptFields = array()) {

        $result = $this->getSingle($id, TRUE)->getQuery()->getSingleResult("form");

        foreach ($exceptFields as $field) {
            if (isset($result[$field])) {
                unset($result[$field]);
            }
        }


        return $result;
    }

    /**
     * získá všechny záznamy
     * @param bool $getQuery
     * @return mixed
     */
    public function getAll(bool $getQuery = FALSE) {
        $query = $this->getBaseQuery();

        if ($getQuery) {
            return $query;
        }

        $result = $query->getQuery()->getResult();

        return $result;
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     * @param null $limit
     * @param null $offset
     * @return QueryBuilder
     */
    public function getByQuery(array $criteria = array(), array $orderBy = array(), $limit = NULL, $offset = NULL, bool $joins = TRUE)
    {
        $query = $this->getBaseQuery($joins);

        $query->whereCriteria($criteria);

        foreach ($orderBy as $key => $value) {
            $order = "ASC";
            $col = NULL;

            if (is_numeric($key) === FALSE && ($value == "ASC" || $value == "DESC")) {
                $order = $value;
                $col = $key;
            } else {
                $col = $value;
            }

            if (strpos($col, ".") !== FALSE) {
                $query->orderBy($col, $order);
            } else {
                $query->orderBy("u.$col", $order);
            }
        }

        $query->setFirstResult($offset)
            ->setMaxResults($limit);

        return $query;
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     * @param null $limit
     * @param null $offset
     * @return array
     */
    public function getAllBy(array $criteria = array(), array $orderBy = array(), $limit = NULL, $offset = NULL)
    {
        return $this->getByQuery($criteria, $orderBy, $limit, $offset)->getQuery()->getResult();
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     * @param null $limit
     * @param null $offset
     * @return mixed
     */
    public function getSingleOrNullBy(array $criteria = array(), array $orderBy = array(), $limit = NULL, $offset = NULL)
    {
        return $this->getByQuery($criteria, $orderBy, $limit, $offset)->getQuery()->getOneOrNullResult();
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     * @param null $limit
     * @param null $offset
     * @return mixed
     */
    public function getSingleBy(array $criteria = array(), array $orderBy = array(), $limit = NULL, $offset = NULL)
    {
        return $this->getByQuery($criteria, $orderBy, $limit, $offset)->getQuery()->getSingleResult();
    }


    /**
     * smaže entitu
     * @param mixed $entity
     * @return boolean
     */
    public function remove($entity) {
        try {
            $this->getEntityManager()->remove($entity);

            $this->getEntityManager()->flush($entity);
        } catch (\Exception $ex) {
            throw $ex;
        }

        return TRUE;
    }

    /**
     * smaže entitu na základě id
     * @param mixed $id
     * @return boolean
     */
    public function removeById($id) {
        return $this->remove($this->getRawSingleEntityById($id));
    }

    /**
     * @param array $ids
     * @param bool $getQuery
     * @param string $keyName
     * @return array|QueryBuilder
     */
    public function getByIds(array $ids, bool $getQuery = FALSE, string $keyName = "id")
    {

        /** @var QueryBuilder $query */
        $query = $this->getAll(TRUE);

        foreach (array_values($ids) as $k => $id) {
            $query->orWhere("u.$keyName = ?$k")
                    ->setParameter($k, $id);
        }

        if ($getQuery) {
            return $query;
        }

        if (count($ids) === 0) {
            return array();
        }

        $result = $query->getQuery()->getResult();

        return $result;
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getUpdateQuery()
    {
        $query = $this->getDao()->createQueryBuilder()
                ->update($this->getDao()->getClassName(), "u");

        return $query;
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getDeleteQuery()
    {
        $query = $this->getDao()->createQueryBuilder()
            ->delete($this->getDao()->getClassName(), "u");

        return $query;
    }

//    public function getLikeQuery(QueryBuilder $queryBuilder, array $like = array())
//    {
//        foreach ($like as $key => $value) {
//            $queryBuilder->
//        }
//    }
}