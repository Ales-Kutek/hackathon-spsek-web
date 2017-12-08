<?php

namespace AdminModule\Presenters;

/**
 * PlacesPresenter Presenter class
 */
class PlacesPresenter extends SecurePresenter
{
	/**
	 * @var
	 * \AdminModule\Forms\PlacesForm
	 */
	public $placesForm;

	/**
	 * @var
	 * \AdminModule\Grids\PlacesGrid
	 */
	public $placesGrid;

	/**
	 * @var
	 * \Repository\Places
	 */
	public $placesRepository;


	public function __construct(\AdminModule\Grids\PlacesGrid $placesGrid, \AdminModule\Forms\PlacesForm $placesForm, \Repository\PlacesRepository $placesRepository)
	{
		$this->placesGrid = $placesGrid;
		$this->placesRepository = $placesRepository;
		$this->placesForm = $placesForm;
	}


	public function beforeRender()
	{
		$this->template->title = "Pokrytí";
	}


	public function renderDetail($id)
	{
		$data = $this->placesRepository->getSingle($id);
		        $this->template->data = $data;
	}


	public function renderEdit($id)
	{
		$data = $this->placesRepository->getFormDataById($id);

		$this->getComponent("edit")->setDefaults($data);
	}


	public function handleDelete($id)
	{
		$this->placesRepository->removeById($id);
	}


	public function createComponentNew()
	{
		$form = $this->placesForm->create();

		$form->onSuccess[] = function($form, $values) {
		    $entity = NULL;

		    try {
		        $entity = $this->placesRepository->insertForm($form->getValues(TRUE));
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
		$form = $this->placesForm->create();

		$form->onSuccess[] = function($form, $values) {
		    $entity = NULL;

		    try {
		        $entity = $this->placesRepository->updateForm($form->getValues(TRUE), $values["id"]);
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
		$source = $this->placesRepository->getAll(TRUE);

		return $this->placesGrid->create($source);
	}
}
