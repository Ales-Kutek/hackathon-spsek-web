<?php

namespace UW\Core\Crud\Templates\AdminModule;

use Core\Crud;
use UW\Core\Crud\ITemplate;
use UW\Core\Crud\Templates\Base;

class Presenters extends Base implements ITemplate {
    public function generate($name) {
        $fullName = $this->getFullName($name);

        $namespace = new \Nette\PhpGenerator\PhpNamespace("AdminModule\\Presenters");
        
        $lower = lcfirst($name);
        
        $class = $namespace->addClass($fullName);
        
        $class
                ->addComment("$fullName Presenter class")
                ->setExtends("AdminModule\\Presenters\\SecurePresenter");
        
        $repositoryName = $lower . "Repository";
        $formFactoryName = $lower . "Form";
        $gridFactoryName = $lower . "Grid";
        
        $formFactory = $class
                ->addProperty($formFactoryName)
                ->setVisibility("public")
                ->addComment("@var")
                ->addComment("\\AdminModule\\Forms\\$name"."Form");
        
        $gridFactory = $class
                ->addProperty($gridFactoryName)
                ->setVisibility("public")
                ->addComment("@var")
                ->addComment("\\AdminModule\\Grids\\$name"."Grid");
        
        $repository = $class
                ->addProperty($repositoryName)
                ->setVisibility("public")
                ->addComment("@var")
                ->addComment("\\Repository\\$name");
        
        /** public function __construct **/
        $__construct = 
                $class
                ->addMethod("__construct")
                ->setVisibility("public");
        
        $__construct->addParameter($gridFactoryName)
                    ->setTypeHint("\\AdminModule\\Grids\\$name"."Grid");
        
        $__construct->addParameter($formFactoryName)
                    ->setTypeHint("\\AdminModule\\Forms\\$name"."Form");
        
        $__construct->addParameter($repositoryName)
                    ->setTypeHint("\\Repository\\$name");
        
        
        $__construct->addBody
        (
'$this->'.$gridFactoryName.' = $'.$gridFactoryName.';
$this->'.$repositoryName.' = $'.$repositoryName.';
$this->'.$formFactoryName.' = $'.$formFactoryName.';'       
        );

        $class->addMethod("beforeRender")->setVisibility("public")->addBody('$this->template->title = "' . $name .'";');

        $class->addMethod("renderDetail")->setVisibility("public")->addBody('$data = $this->'.$repositoryName.'->getSingle($id);
        $this->template->data = $data;')
            ->addParameter("id");

        /** public function renderEdit **/
        $renderEdit = 
                $class
                ->addMethod("renderEdit")
                ->setVisibility("public");
        
        $renderEdit->addParameter("id");
        
        $renderEdit->addBody(
'$data = $this->'.$repositoryName.'->getFormDataById($id);
        
$this->getComponent("edit")->setDefaults($data);'
        );
        
        /** public function handleDelete **/
        $handleDelete = 
                $class
                ->addMethod("handleDelete")
                ->setVisibility("public");
        
        $handleDelete->addParameter("id");
        
        $handleDelete->addBody('$this->' . $repositoryName . '->removeById($id);');
        
        /** public function createComponentNew **/
        $createComponentNew = 
                $class
                ->addMethod("createComponentNew")
                ->setVisibility("public");
        
        $createComponentNew->addBody(
'$form = $this->'.$formFactoryName.'->create();

$form->onSuccess[] = function($form, $values) {
    $entity = NULL;

    try {
        $entity = $this->'.$repositoryName.'->insertForm($form->getValues(TRUE));
        $this->flashMessage("Uloženo", "success");
    } catch (\Exception $ex) {
        $this->flashMessage("Nastala chyba. ({$ex->getMessage()})");
    }

    if ($entity !== NULL) {
            if ($form["submit_stay"]->isSubmittedBy()) {
                $this->redirect("edit", array("id" => $entity->id));
            } else if ($form["submit"]->isSubmittedBy()) {
                $this->redirect("default");
            }
      }
};

return $form;'
        );
        
        /** public function createComponentEdit **/
        $createComponentEdit = 
                $class
                ->addMethod("createComponentEdit")
                ->setVisibility("public");
        
        $createComponentEdit->addBody(
'$form = $this->'.$formFactoryName.'->create();

$form->onSuccess[] = function($form, $values) {
    $entity = NULL;

    try {
        $entity = $this->'.$repositoryName.'->updateForm($form->getValues(TRUE), $values["id"]);
        $this->flashMessage("Uloženo", "success");
    } catch (\Exception $ex) {
        $this->flashMessage("Nastala chyba. ({$ex->getMessage()})");
    }

    if ($entity !== NULL) {
        if ($form["submit_stay"]->isSubmittedBy()) {
            $this->redirect("edit", array("id" => $entity->id));
        } else if ($form["submit"]->isSubmittedBy()) {
            $this->redirect("default");
        }
    }
};

return $form;'
        );
        
        /** public function createComponentGrid **/
        $createComponentGrid = 
                $class
                ->addMethod("createComponentGrid")
                ->setVisibility("public");
        
        $createComponentGrid->addBody(
'$source = $this->'.$repositoryName.'->getAll(TRUE);
        
return $this->'.$gridFactoryName.'->create($source);'
        );
        
        $this->createTemplate((string) $namespace, $name);
    }
}
