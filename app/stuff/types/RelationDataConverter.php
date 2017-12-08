<?php
/**
 * Created by PhpStorm.
 * User: ales
 * Date: 28.5.17
 * Time: 18:33
 */

namespace Core\ORM\Repository\Converter;


use UW\Core\ORM\Repository\Converter\ConverterException;
use UW\Core\ORM\Repository\FormToEntity;
use UW\Core\App\Helper\Strings;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\NoResultException;
use Kdyby\Doctrine\EntityDao;
use Nette\Utils\ArrayHash;

class RelationDataConverter
{
    const OneToOne = 1;
    const ManyToOne = 2;
    const ManyToMany = 8;
    const OneToMany = 4;

    /**
     * @var ClassMetadata
     */
    private $classMetadata;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var mixed
     */
    private $entity;

    /**
     * @var array
     */
    private $values;

    /**
     * RelationDataConvertor constructor.
     * @param ClassMetadata $classMetadata
     * @param EntityManager $entityManager
     * @param $entity
     */
    public function __construct(array $values, ClassMetadata $classMetadata, EntityManager $entityManager, $entity)
    {
        $this->classMetadata = $classMetadata;
        $this->entityManager = $entityManager;
        $this->entity = $entity;
        $this->values = $values;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        $data = $this->getValues();
        $entity = $this->getEntity();

        foreach ($this->getClassMetadata()->associationMappings as $key => $value) {
            if (isset($data[$key])) {
                $dataValue = $data[$key];

                switch ($value["type"]) {
                    case self::OneToMany:
                        $this->persistOneToMany($this->getClassMetadata(), $value["targetEntity"], $dataValue, $entity);
                        break;

                    case self::ManyToOne:
                        $method = "set".Strings::camelCase($key);

                        try {
                            $entity->$method($this->getRawSingleEntityById($dataValue, $value["targetEntity"]));
                        } catch (NoResultException $exception) {

                        }
                    break;

                    case self::OneToOne:
                        $entity->{$key} = $this->persistOneToOne($value["targetEntity"], $dataValue, $entity, $key);
                        break;
                    case self::ManyToMany:
                        $this->persistManyToMany($value["targetEntity"], $dataValue, $entity, $key);
                    break;
                }
            }
        }

        return $entity;
    }

    /**
     * @param string $targetEntity
     * @param array $value
     * @param $entity
     * @param $key
     */
    public function persistManyToMany(string $targetEntity, array $value, $entity, $key)
    {
        $elements = array();
        $methodName = "get" . Strings::camelCase($key);

        if (method_exists($entity, $methodName) === FALSE) {
            throw new ConverterException("Implimentujte metodu \"$methodName\" třídě " . get_class($entity) . ".");
        }


        $entityValue = $entity->{$methodName}();

        if ($entityValue instanceof Collection === FALSE) {
            throw new ConverterException("Objektu " . get_class($entity) . " přidejte do constructoru \"\$this->$key = new ArrayCollection()\" aby mohla ManyToMany fungovat správně.");
        }

        $entityValue->clear();

        foreach ($value as $v) {
            $element = $this->getRawSingleEntityById($v, $targetEntity);
            $elements[] = $element;

            $entityValue->add($element);
        }
    }

    /**
     * vytvoří/editne/smaže one to many associace
     * @param \Doctrine\ORM\Mapping\ClassMetadata $metadata
     * @param string $targetEntity
     * @param array $dataValue
     * @param mixed $entity
     */
    public function persistOneToMany(\Doctrine\ORM\Mapping\ClassMetadata $metadata, string $targetEntity, array $dataValue, $entity) {
        /** @var EntityDao $repository */
        $repository = $this->getEntityManager()->getRepository($targetEntity);

        $targetMetadata = $repository->getClassMetadata();

        $entityArray = $this->convertOneToMany($metadata, $repository, $dataValue, $entity);

        if ($entity->id != "") {
            $this->checkDeletedOneToMany(
                $entityArray,
                $this->getOneToManyFlatArray(
                    $this->getTargetPropertyOneToMany($metadata->getName(), $targetMetadata),
                    $entity->id,
                    $targetMetadata->getName()
                )
            );
        }
        foreach($entityArray as $k => $v) {
            $this->getEntityManager()->persist($v);
        }

    }

    /**
     * @param string $column
     * @param $value
     * @param string $entityName
     * @return array
     */
    public function getOneToManyFlatArray(string $column, $value, string $entityName) {
        $result = $this->getEntityManager()
            ->createQueryBuilder()
            ->select("u")
            ->from($entityName, "u")
            ->where("u.$column = :value")
            ->setParameter("value", $value)
            ->getQuery()
            ->getResult();

        return $result;
    }

    /**
     * @param array $persisted
     * @param array $all
     */
    public function checkDeletedOneToMany(array $persisted, array $all) {
        $persisted_ids = array();

        foreach ($persisted as $v) {
            $persisted_ids[] = $v->id;
        }

        foreach ($all as $key => $value) {
            if (!in_array($value->id, $persisted_ids)) {
                $this->getEntityManager()->remove($value);
                $this->getEntityManager()->flush($value);
            }
        }
    }


    /**
     * @param ClassMetadata $metadata
     * @param EntityDao $targetsRepository
     * @param array $formData
     * @param $sourceEntity
     * @return array
     */
    public function convertOneToMany(\Doctrine\ORM\Mapping\ClassMetadata $metadata, EntityDao $targetsRepository, array $formData, $sourceEntity) {
        $entities = array();

        $targetsMetadata = $targetsRepository->getClassMetadata();
        $entityName = $targetsMetadata->getName();

        $sourceEntityKey = $this->getTargetPropertyOneToMany($metadata->getName(), $targetsMetadata);

        foreach ($formData as $key => $value) {
            $entity = NULL;

            if (isset($value["id"]) && $value["id"] != "") {
//                try {
                $entity = $this->getRawSingleEntityById($value["id"], $targetsMetadata->getName());
//                } catch (NoResultException $exception) {
//                    $entity= new $entityName();
//                }

            } else {
                $entity = new $entityName();
            }

            $conv = new FormToEntity($value, $targetsMetadata, $this->getEntityManager(), $entity);

            $entity = $conv->getEntity();

            $entity->{$sourceEntityKey} = $sourceEntity;

            $entities[] = $entity;
        }

        return $entities;
    }

    public function getTargetPropertyOneToMany(string $sourceEntityName, \Doctrine\ORM\Mapping\ClassMetadata $targetsMetadata) {
        foreach ($targetsMetadata->associationMappings as $key => $value) {
            if ($value["targetEntity"] == $sourceEntityName) {
                return $key;
            }
        }

        return FALSE;
    }

    public function getRawSingleEntityById($dataValue, string $targetEntityName)
    {
        $item = $this->getEntityManager()->createQueryBuilder()
                    ->select("u")
                    ->from($targetEntityName, "u")
                    ->where("u.id = :id")
                        ->setParameter("id", $dataValue)
                    ->getQuery()
                    ->getSingleResult();

        return $item;
    }

    public function persistOneToOne(string $targetEntity, array $dataValue, $entity, string $key) {
        /** @var EntityDao $targetRepository */
        $targetRepository = $this->getEntityManager()->getRepository($targetEntity);

        if ($entity->{$key} === NULL) {
            $entityVar =  new $targetEntity();

            $conv = new FormToEntity($dataValue, $targetRepository->getClassMetadata(), $this->getEntityManager(), $entityVar);
        } else {
            $conv = new FormToEntity($dataValue, $targetRepository->getClassMetadata(), $this->getEntityManager(), $entity->{$key});
        }

        $item = $conv->getEntity();

        $this->getEntityManager()->persist($item);

        return $item;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @param array $values
     * @return RelationDataConverter
     */
    public function setValues(array $values)
    {
        $this->values = $values;
        return $this;
    }

    /**
     * @return ClassMetadata
     */
    public function getClassMetadata()
    {
        return $this->classMetadata;
    }

    /**
     * @param ClassMetadata $classMetadata
     */
    public function setClassMetadata(ClassMetadata $classMetadata)
    {
        $this->classMetadata = $classMetadata;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param EntityManager $entityManager
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param mixed $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }
}