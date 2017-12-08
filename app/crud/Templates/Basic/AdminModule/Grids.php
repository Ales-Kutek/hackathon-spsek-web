<?php

namespace UW\Core\Crud\Templates\AdminModule;

use Core\Crud;
use UW\Core\Crud\ITemplate;
use UW\Core\Crud\Templates\Base;

class Grids extends Base implements ITemplate {
    public function generate($name) {

        $fullName = $this->getFullName($name);

        $namespace = new \Nette\PhpGenerator\PhpNamespace("AdminModule\\Grids");
        
        $lower = lcfirst($name);
        
        $class = $namespace->addClass($fullName);
        
        $class
                ->addComment("$fullName Grid class");
        
        $baseGrid = $class
                ->addProperty("baseGrid")
                ->setVisibility("private")
                ->addComment("\\AdminModule\\Grids\\Base");
        
        /** public function __construct **/
        $__construct = 
                $class
                ->addMethod("__construct")
                ->setVisibility("public");
        
        $__construct->addParameter('baseGrid')
                    ->setTypeHint("\\AdminModule\\Grids\\Base");
        
        $__construct->addBody
        (
'$this->baseGrid = $baseGrid;'       
        );
        
        /** public function create **/
        $create = 
                $class
                ->addMethod("create")
                ->setVisibility("public");
        
        $create->addParameter("source");
        
        $create->addBody(
'$grid = $this->baseGrid->create($source);
    
$grid->addColumnLink("title", "Název", "'.$name.':detail")->setSortable()->setFilterText();

$grid->addAction("edit", "")
        ->setIcon("pencil")
        ->setTitle("Upravit");
$grid->addAction("delete", "", "delete!")
        ->setIcon("trash")
        ->setTitle("Smazat")
        ->setClass("btn btn-xs btn-danger")
        ->setConfirm(function($item) {
            return "Opravdu chcete smazat {$item->id}?";
        }, "id");
return $grid;'
        );
        
        $this->createTemplate((string) $namespace, $name);
    }
}
