<?php
/**
 * Created by PhpStorm.
 * User: ales
 * Date: 10/8/17
 * Time: 2:57 PM
 */

namespace UW\Core\Crud\Templates\Package\Neon;


use UW\Core\Crud\ITemplate;
use UW\Core\Crud\Templates\Base;

class ServicesNeon extends Base implements ITemplate
{
    public function generate($name)
    {
        $this->createTemplate("", $name);
    }
}