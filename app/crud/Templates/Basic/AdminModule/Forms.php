<?php

namespace UW\Core\Crud\Templates\AdminModule;

use UW\Core\Crud\ITemplate;
use UW\Core\Crud\Templates\Base;

class Forms extends Base implements ITemplate {
    public function generate($name) {
        $fullName = $this->getFullName($name);

        $namespace = new \Nette\PhpGenerator\PhpNamespace("AdminModule\\Forms");
        
        $lower = lcfirst($name);
        
        $class = $namespace->addClass($fullName);
        
        $class
                ->addComment("$fullName Form class");
        
        $baseForm = $class
                ->addProperty("baseForm")
                ->setVisibility("private")
                ->addComment("\\AdminModule\\Forms\\Base");
        
        $nameRepository = $class
                ->addProperty($lower . "Repository")
                ->setVisibility("private")
                ->addComment("\\Repository\\$name");
        
        /** public function __construct **/
        $__construct = 
                $class
                ->addMethod("__construct")
                ->setVisibility("public");
        
        $__construct->addParameter('baseForm')
                    ->setTypeHint("\\AdminModule\\Forms\\Base");
        
        $__construct->addParameter($lower."Repository")
                    ->setTypeHint("\\Repository\\$name");
        
        $__construct->addBody
        (
'$this->baseForm = $baseForm;
$this->'.$lower.'Repository = $'.$lower.'Repository;
'       
        );
        
        /** public function create **/
        
        $create = 
                $class
                ->addMethod("create")
                ->setVisibility("public");
        
        $create->addBody(
'$form = $this->baseForm->create();

$form->addHidden("id");

$form->addText("title", "Titulek")->setRequired();

$form->addSubmit("submit", "Uložit");
$form->addSubmit("submit_stay", "Uložit a zůstat");

return $form;
'
        );
        
        $this->createTemplate((string) $namespace, $name);
    }
}
