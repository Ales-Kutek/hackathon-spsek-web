<?php
/**
 * Created by PhpStorm.
 * User: ales
 * Date: 8/21/17
 * Time: 9:59 AM
 */

namespace UW\Core\ORM\Repository\Converter;


interface IFormToDatabaseExtension
{
    public function catch();

    public function preProcessConvert($entity, $key, $value);

    public function postProcessConvert($entity, $key, $value);
}