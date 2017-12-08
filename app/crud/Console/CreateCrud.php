<?php

/**
 * Creates command for creating components.
 */

namespace UW\Core\Crud\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use UW\Core\Crud\Crud;

/**
 * Create component command class
 */
class CreateCrud extends Command {
    /**
     * @var Crud
     */
    private $crud;
    
    /** @var \Nette\DI\Container $container */
    private $container;
    
    /**
     * Injectnutí závislostí
     * @param Crud $crud
     * @param \Nette\DI\Container $container
     */
    public function injectDepend(Crud $crud, \Nette\DI\Container $container)
    {
        $this->crud = $crud;
        $this->container = $container;
    }
    
    /**
     * {@inheritdoc}
     */
    protected function configure() {
        $this->setName('components:create')
                ->setDescription('Create component by name.')
                ->addArgument(
                        'name', \Symfony\Component\Console\Input\InputArgument::REQUIRED, 'Name of the component'
                )
                ->addArgument("variant", InputArgument::REQUIRED, "Variant");
    }

    /**
     * Vytvoření komponenty
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $name = $input->getArgument('name');
        $output->writeln("Creating component " . $name);
        
        $this->crud->init($name, $input->getArgument("variant"));
    }

}
