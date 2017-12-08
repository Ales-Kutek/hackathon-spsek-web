<?php
/**
 * Created by PhpStorm.
 * User: ales
 * Date: 10/8/17
 * Time: 3:01 PM
 */

namespace UW\Core\Crud\Templates\Package;


use UW\Core\Crud\ITemplate;
use UW\Core\Crud\Templates\Base;

class AdminPresenter extends Base implements ITemplate
{
    public function generate($name)
    {
        $lowerName = lcfirst($name);

        $str = '
namespace AdminModule\Presenters;
use UW\\' . $name. '\Repository\\' . $name. 'Repository;
use UW\\' . $name. '\Forms\\' . $name. 'Form;
use UW\\' . $name. '\Grids\\' . $name. 'Grid;

/**
 * ' . $name. 'Presenter class
 */
class ' . $name. 'Presenter extends SecurePresenter
{
	/**
	 * @var ' . $name. 'Form
     * @inject
	 */
	public $' . $lowerName . 'Form;

	/**
	 * @var ' . $name. 'Grid
     * @inject
	 */
	public $' . $lowerName . 'Grid;

	/**
	 * @var ' . $name. 'Repository
     * @inject
	 */
	public $' . $lowerName . 'Repository;
	
    public function handleDelete($id)
	{
        $this->' . $lowerName . 'Repository->removeById($id);
	}
	
    public function beforeRender()
    {
        $this->template->title = $this->' . $lowerName . 'Repository->getParameters()->getTitle();
	}


    public function renderEdit($id)
	{
		$data = $this->' . $lowerName . 'Repository->getFormDataById($id);

		$this->getComponent("edit")->setDefaults($data);
	}

    public function renderDetail($id)
    {
        $data = $this->' . $lowerName . 'Repository->getSingle($id);

        $this->template->data = $data;
	}

	public function createComponentNew()
	{
		$form = $this->' . $lowerName . 'Form->create();

		$form->onSuccess[] = function($form, $values) {
		    $entity = NULL;

		    try {
		        $entity = $this->' . $lowerName . 'Repository->insertForm($form->getValues(TRUE));
		    } catch (\Exception $ex) {
		        throw $ex;
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

		return $form;
	}


	public function createComponentEdit()
	{
		$form = $this->' . $lowerName . 'Form->create();

		$form->onSuccess[] = function($form, $values) {
		    $entity = NULL;

		    try {
		        $entity = $this->' . $lowerName . 'Repository->updateForm($form->getValues(TRUE), $values["id"]);
		    } catch (\Exception $ex)
            {
                throw $ex;
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

		return $form;
	}


	public function createComponentGrid()
	{
		$source = $this->' . $lowerName . 'Repository->getBaseQuery(TRUE);

		return $this->' . $lowerName . 'Grid->create($source);
	}

}
';
        $this->createTemplate($str, $name);
    }
}