<?php
/**
 * Created by PhpStorm.
 * User: ales
 * Date: 10/8/17
 * Time: 2:41 PM
 */

namespace UW\Core\Crud\Templates\Package;


use UW\Core\Crud\ITemplate;
use UW\Core\Crud\Templates\Base;

class Extension extends Base implements ITemplate
{
    public function generate($name)
    {
        $str = '
namespace UW\\'.$name.'\DI;

use Nette\DI\Compiler;
use Nette\DI\CompilerExtension;
use Nette\DI\Config\Loader;
use Nette\DI\ContainerBuilder;
use Nette\Neon\Neon;
use Nette\PhpGenerator\ClassType;
use UW\\'.$name.'\Repository\\'.$name.'Repository;
use UW\Core\App\ConfigParameters;
use UW\Core\App\Helper\ModuleConfigurator;

class '.$name.'Extension extends CompilerExtension
{
    public function loadConfiguration()
    {
        /** @var ContainerBuilder $builder */
        $builder = $this->getContainerBuilder();

        /** @var Compiler $compiler */
        $compiler = $this->compiler;
    }

    public function beforeCompile()
    {
        $configurator = new ModuleConfigurator($this, $this->compiler);

        $configurator
            ->setModuleConfigFilePath(__DIR__ . DS . ".." . DS . "module.neon")
            ->setServicesConfigFilePath(__DIR__ . \'/services.neon\')
            ->setModuleCodeName($this->name)
            ->setRepositoryClassName('.$name.'Repository::class);

        $config = $configurator->configurate();

        $this->config = $config;
    }

    /**
     * @param \Nette\PhpGenerator\ClassType $class class, interface, trait description
     * @return void
     */
    public function afterCompile(ClassType $class)
    {
        parent::afterCompile($class);

        $initialize = $class->methods[\'initialize\'];
    }
}';

        $this->createTemplate($str, $name);
    }
}