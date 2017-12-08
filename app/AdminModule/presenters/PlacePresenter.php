<?php

namespace AdminModule\Presenters;

/**
 * PlacePresenter Presenter class
 */
class PlacePresenter extends SecurePresenter
{
	/**
	 * @var
	 * \AdminModule\Forms\PlaceForm
	 */
	public $placeForm;

	/**
	 * @var
	 * \AdminModule\Grids\PlaceGrid
	 */
	public $placeGrid;

	/**
	 * @var
	 * \Repository\Place
	 */
	public $placeRepository;


	public function __construct(\AdminModule\Grids\PlaceGrid $placeGrid, \AdminModule\Forms\PlaceForm $placeForm, \Repository\Place $placeRepository)
	{
		$this->placeGrid = $placeGrid;
		$this->placeRepository = $placeRepository;
		$this->placeForm = $placeForm;
	}


	public function beforeRender()
	{
		$this->template->title = "Místa";
	}


	public function renderDetail($id)
	{
		$data = $this->placeRepository->getSingle($id);
		        $this->template->data = $data;
	}


	public function renderEdit($id)
	{
		$data = $this->placeRepository->getFormDataById($id);

		$this->getComponent("edit")->setDefaults($data);
	}


	public function handleDelete($id)
	{
		$this->placeRepository->removeById($id);
	}


	public function createComponentNew()
	{
		$form = $this->placeForm->create();

		$form->onSuccess[] = function($form, $values) {
		    $entity = NULL;

		    try {
		        $entity = $this->placeRepository->insertForm($form->getValues(TRUE));
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
		$form = $this->placeForm->create();

		$form->onSuccess[] = function($form, $values) {
		    $entity = NULL;

		    try {
		        $entity = $this->placeRepository->updateForm($form->getValues(TRUE), $values["id"]);
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
		$source = $this->placeRepository->getAll(TRUE);

		return $this->placeGrid->create($source);
	}
}
