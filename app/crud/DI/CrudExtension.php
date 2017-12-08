<?php

namespace Core\Crud\DI;

use Nette\DI\Compiler;
use Nette\DI\CompilerExtension;
use Nette\DI\ContainerBuilder;
use Nette\DI\Statement;
use Nette\PhpGenerator\ClassType;
use UW\Core\Crud\Crud;

class CrudExtension extends CompilerExtension
{

    private $defaults = array();

    public function loadConfiguration()
    {
        $this->config += $this->defaults;

        /** @var ContainerBuilder $builder */
        $builder = $this->getContainerBuilder();

        /** @var Compiler $compiler */
        $compiler = $this->compiler;

        $services = $this->loadFromFile(__DIR__ . '/services.neon');
        
        $services[999] = new Statement(Crud::class, array(
            $this->config
        ));
        
        $compiler->loadDefinitions($builder, $services, $this->name);
    }

    /**
     * @param \Nette\PhpGenerator\ClassType $class class, interface, trait description
     * @return void
     */
    public function afterCompile(ClassType $class)
    {
        parent::afterCompile($class);

        $initialize = $class->methods['initialize'];
    }
}