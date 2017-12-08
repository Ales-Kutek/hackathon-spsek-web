<?php
/**
 * Created by PhpStorm.
 * User: ales
 * Date: 28.5.17
 * Time: 18:19
 */

namespace UW\Core\ORM\Repository\Converter;


use UW\Core\App\Helper\Strings;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManager;
use Nette\Utils\ArrayHash;

class BasicDataConverter
{
    /**
     * @var ArrayHash
     */
    private $values;
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
     * BasicDataConvertor constructor.
     * @param array $values
     * @param ClassMetadata $classMetadata
     * @param EntityManager $entityManager
     * @param $entity
     */
    public function __construct(array $values, ClassMetadata $classMetadata, EntityManager $entityManager, $entity)
    {
        $this->values = $values;
        $this->classMetadata = $classMetadata;
        $this->entityManager = $entityManager;
        $this->entity = $entity;
    }

    public function getResult() {
        $fields = $this->getClassMetadata()->fieldMappings;

        $data = $this->getValues();

        $entity = $this->getEntity();

        foreach ($fields as $key => $value) {
            if (array_key_exists($key, $data)) {
                $method = "set".Strings::camelCase($key);

                $entity->{$method}($this->convertType($value["type"], $data[$key]));
            }
        }

        return $entity;
    }

    protected function convertType($type, $value)
    {
        switch($type) {
            case "datetime":
                if ($value != "") {
                    return $value;
                }
                break;

            default:
                return $value;
                break;
        }

        return NULL;
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
     * @return BasicDataConverter
     */
    public function setValues(ArrayHash $values)
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
     * @return BasicDataConverter
     */
    public function setClassMetadata(ClassMetadata $classMetadata)
    {
        $this->classMetadata = $classMetadata;
        return $this;
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
     * @return BasicDataConverter
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
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
     * @return BasicDataConverter
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }


}