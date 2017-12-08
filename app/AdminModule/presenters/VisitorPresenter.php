<?php

namespace AdminModule\Presenters;

/**
 * VisitorPresenter Presenter class
 */
class VisitorPresenter extends SecurePresenter
{
	/**
	 * @var
	 * \AdminModule\Forms\VisitorForm
	 */
	public $visitorForm;

	/**
	 * @var
	 * \AdminModule\Grids\VisitorGrid
	 */
	public $visitorGrid;

	/**
	 * @var
	 * \Repository\Visitor
	 */
	public $visitorRepository;


	public function __construct(\AdminModule\Grids\VisitorGrid $visitorGrid, \AdminModule\Forms\VisitorForm $visitorForm, \Repository\Visitor $visitorRepository)
	{
		$this->visitorGrid = $visitorGrid;
		$this->visitorRepository = $visitorRepository;
		$this->visitorForm = $visitorForm;
	}


	public function beforeRender()
	{
		$this->template->title = "Návštěvníci";
	}


	public function renderDetail($id)
	{
		$data = $this->visitorRepository->getSingle($id);
		        $this->template->data = $data;
	}


	public function renderEdit($id)
	{
		$data = $this->visitorRepository->getFormDataById($id);

		$this->getComponent("edit")->setDefaults($data);
	}


	public function handleDelete($id)
	{
		$this->visitorRepository->removeById($id);
	}


	public function createComponentNew()
	{
		$form = $this->visitorForm->create();

		$form->onSuccess[] = function($form, $values) {
		    $entity = NULL;

		    try {
		        $entity = $this->visitorRepository->insertForm($form->getValues(TRUE));
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

		return $form;
	}


	public function createComponentEdit()
	{
		$form = $this->visitorForm->create();

		$form->onSuccess[] = function($form, $values) {
		    $entity = NULL;

		    try {
		        $entity = $this->visitorRepository->updateForm($form->getValues(TRUE), $values["id"]);
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

		return $form;
	}


	public function createComponentGrid()
	{
		$source = $this->visitorRepository->getAll(TRUE);

		return $this->visitorGrid->create($source);
	}
}
