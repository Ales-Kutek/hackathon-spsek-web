<?php

namespace AdminModule\Presenters;

/**
 * PexesoPresenter Presenter class
 */
class PexesoPresenter extends SecurePresenter
{
	/**
	 * @var
	 * \AdminModule\Forms\PexesoForm
	 */
	public $pexesoForm;

	/**
	 * @var
	 * \AdminModule\Grids\PexesoGrid
	 */
	public $pexesoGrid;

	/**
	 * @var
	 * \Repository\Pexeso
	 */
	public $pexesoRepository;


	public function __construct(\AdminModule\Grids\PexesoGrid $pexesoGrid, \AdminModule\Forms\PexesoForm $pexesoForm, \Repository\Pexeso $pexesoRepository)
	{
		$this->pexesoGrid = $pexesoGrid;
		$this->pexesoRepository = $pexesoRepository;
		$this->pexesoForm = $pexesoForm;
	}


	public function beforeRender()
	{
		$this->template->title = "Pexeso";
	}


	public function renderDetail($id)
	{
		$data = $this->pexesoRepository->getSingle($id);
		        $this->template->data = $data;
	}


	public function renderEdit($id)
	{
		$data = $this->pexesoRepository->getFormDataById($id);

		$this->getComponent("edit")->setDefaults($data);
	}


	public function handleDelete($id)
	{
		$this->pexesoRepository->removeById($id);
	}


	public function createComponentNew()
	{
		$form = $this->pexesoForm->create();

		$form->onSuccess[] = function($form, $values) {
		    $entity = NULL;

		    try {
		        $entity = $this->pexesoRepository->insertForm($form->getValues(TRUE));
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
		$form = $this->pexesoForm->create();

		$form->onSuccess[] = function($form, $values) {
		    $entity = NULL;

		    try {
		        $entity = $this->pexesoRepository->updateForm($form->getValues(TRUE), $values["id"]);
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
		$source = $this->pexesoRepository->getAll(TRUE);

		return $this->pexesoGrid->create($source);
	}
}
