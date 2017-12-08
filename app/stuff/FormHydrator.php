<?php

namespace Hydrator;

use UW\Core\ORM\Repository\Converter\IFormHydratorExtension;
use UW\Core\ORM\Repository\Converter\IFormToEntityExtension;
use Doctrine\ORM\Internal\Hydration\ArrayHydrator;
use Doctrine\ORM\Internal\Hydration\ObjectHydrator;
use Doctrine\ORM\Mapping\ClassMetadata;
use Kdyby;
use Netpromotion\Profiler\Profiler;
use Nette;
use Nette\Utils\ArrayHash;
use Core\ORM\Repository\Converter\RelationDataConverter;

class FormHydrator extends ArrayHydrator
{
    const PRIMARY_KEY = "id";


    public $data;

    /**
     * @var ClassMetadata
     */
    public $metadata;

	/**
	 * @param object $stmt
	 * @param object $resultSetMapping
	 * @param array $hints
	 * @return array
	 */
	public function hydrateAll($stmt, $resultSetMapping, array $hints = [])
    {
        $data = parent::hydrateAll($stmt, $resultSetMapping, $hints);

        $rootEntity = $this->getRootEntityNameFromMapping($resultSetMapping);
        $data = $data[0];

        $metadata = $this->getClassMetadata($rootEntity);

        $this->data = $data;
        $this->metadata = $metadata;

        $data = $this->hydrateForm($data, $metadata);

        return array("0" => $data);
	}

    private function hydrateForm(array $data, \Doctrine\ORM\Mapping\ClassMetadata $metadata) {
        $result = array();

        $result = $this->hydrateSimpleTypes($data, $metadata, $result);
        $result = $this->hydrateAssoc($data, $metadata, $result);

        return $result;
    }


    private function hydrateSimpleTypes($data, \Doctrine\ORM\Mapping\ClassMetadata $metadata, array $result) {
            $fields = $metadata->fieldMappings;

            foreach ($fields as $key => $value) {
                if (isset($data[$key])) {
                    $dataValue = $data[$key];

                    switch($value["type"]) {
                        default:
                            $result[$key] = $dataValue;
                        break;
                    }
                }
            }

            return $result;
        }

    private function hydrateAssoc($data, \Doctrine\ORM\Mapping\ClassMetadata $metadata, array $result) {
            $fields = $metadata->associationMappings;

            foreach ($fields as $key => $value) {
                if (isset($data[$key])) {
                    $dataValue = $data[$key];

                    switch($value["type"]) {
                        case RelationDataConverter::OneToMany:
                            $targetMetadata = $this->getClassMetadata($value["targetEntity"]);

                            foreach ($dataValue as $v) {
                                $result[$key][] = $this->hydrateForm($v, $targetMetadata);
                            }
                        break;

                        case RelationDataConverter::OneToOne:
                            $targetMetadata = $this->getClassMetadata($value["targetEntity"]);

                            $result[$key] = $this->hydrateForm($dataValue, $targetMetadata);
                        break;

                        case RelationDataConverter::ManyToOne:
                            $result[$key] = $dataValue[self::PRIMARY_KEY];
                        break;

                        case RelationDataConverter::ManyToMany:
                            foreach ($dataValue as $item) {
                                $result[$key][] = $item["id"];
                            }
                        break;
                    }
                }
            }

            return $result;
        }

        private function getRootEntityNameFromMapping($resultSetMapping) {
            $key = array_keys($resultSetMapping->entityMappings)[0];
            
            $result = $resultSetMapping->aliasMap[$key];
            
            return $result;
        }

	/**
	 * @return ArrayHash
	 */
	public function hydrateRow()
	{
            $data = parent::hydrateRow();
            
            return $data;
	}

}
