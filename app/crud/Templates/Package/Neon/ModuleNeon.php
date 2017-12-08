<?php
/**
 * Created by PhpStorm.
 * User: ales
 * Date: 10/8/17
 * Time: 2:53 PM
 */

namespace UW\Core\Crud\Templates\Package\Neon;


use UW\Core\Crud\ITemplate;
use UW\Core\Crud\Templates\Base;

class ModuleNeon extends Base implements ITemplate
{
    public function generate($name)
    {
        $str = 'name: "Název modulu"
link: "'.$name.':"
icon: "times"
map:
    grid:
        class: UW\\'.$name.'\Grids\\'.$name.'Grid
        inject: on
    form:
        class: UW\\'.$name.'\Forms\\'.$name.'Form
        inject: on
    repository:
        class: UW\\'.$name.'\Repository\\'.$name.'Repository
        inject: on';

        $this->createTemplate($str, $name);
    }

}