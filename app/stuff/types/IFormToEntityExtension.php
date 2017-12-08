<?php
/**
 * Created by PhpStorm.
 * User: ales
 * Date: 8/17/17
 * Time: 8:24 AM
 */

namespace UW\Core\ORM\Repository\Converter;


use UW\Core\ORM\Hydrator\FormHydrator;
use UW\Core\ORM\Repository\FormToEntity;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\Mapping\ClassMetadata;

interface IFormToEntityExtension
{
    /**
     * @return bool
     */
    public function catch($key, $value, FormToEntity $formToEntity): bool;

    /**
     * @param FormToEntity $formToEntity
     * @param $entity
     * @param $key
     * @param $value
     */
    public function convert(FormToEntity $formToEntity, $entity, $key, $value);

    /**
     * jestliže vrátí TRUE, tak converter se již o tuto hodnotu nebude dál starat (nějak jí upravovat)
     * jestliže vrátí FALSE, tak converter se bude snažit tuto hodnotu přeložit například na nějakou asociaci
     * @return bool
     */
    public function detachValue(): bool;
}