<?php
/**
 * Created by PhpStorm.
 * User: ales
 * Date: 28.5.17
 * Time: 18:14
 */

namespace UW\Core\ORM\Repository;

use UW\Core\ORM\Repository\Converter\BasicDataConverter;
use UW\Core\ORM\Repository\Converter\IFormToEntityExtension;
use Core\ORM\Repository\Converter\RelationDataConverter;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManager;
use Nette\Object;
use Nette\Utils\ArrayHash;

class FormToEntity extends Object
{
    /** @var IFormToEntityExtension[] */
    private static $extensionConverters = array();

    public $onPostFlush = array();

    /**
     * @param IFormToEntityExtension $converter
     */
    public static function addExtensionConverter(IFormToEntityExtension $converter)
    {
        self::$extensionConverters[] = $converter;
    }

    /**
     * @var ClassMetadata
     */
    private $classMetadata;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /** @var array */
    public $values;

    /** @var mixed */
    private $entity;

    /**
     * FormToEntity constructor.
     * @param ClassMetadata $classMetadata
     * @param EntityManager $entityManager
     * @param $entity
     * @param bool $process
     */
    public function __construct(array $values, ClassMetadata $classMetadata, EntityManager $entityManager, &$entity, bool $process = TRUE)
    {
        $this->classMetadata = $classMetadata;
        $this->entityManager = $entityManager;
        $this->entity = $entity;
        $this->values = $values;


        if ($process) {
            $this->process();
        }
    }

    public function runOnPostFlush(CoreRepository $repository)
    {
        $this->onPostFlush($repository);
    }

    /**
     * @param IFormToEntityExtension[] $converters
     */
    public function runExtensionConverters(array $converters)
    {
        foreach ($this->getValues() as $key => $value) {
            foreach ($converters as $converter) {
                if ($converter->catch($key, $value, $this)) {
                    $converter->convert($this, $this->getEntity(), $key, $value);

                    if ($converter->detachValue()) {
                        unset($this->values[$key]);
                    }
                }
            }
        }
    }

    public function process()
    {
        $this->runExtensionConverters(self::$extensionConverters);

        $basicDataConvertor = new BasicDataConverter($this->getValues(), $this->getClassMetadata(), $this->getEntityManager(), $this->getEntity());
        $entity = $basicDataConvertor->getResult();

        $relationConvertor = new RelationDataConverter($this->getValues(), $this->getClassMetadata(), $this->getEntityManager(), $this->getEntity());
        $entity = $relationConvertor->getResult();

        return $entity;
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
     * @return FormToEntity
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
     * @return FormToEntity
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @param ArrayHash $values
     * @return FormToEntity
     */
    public function setValues(array $values)
    {
        $this->values = $values;
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
     * @return FormToEntity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }
}