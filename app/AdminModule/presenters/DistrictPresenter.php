<?php

namespace AdminModule\Presenters;

/**
 * DistrictPresenter Presenter class
 */
class DistrictPresenter extends SecurePresenter
{
	/**
	 * @var
	 * \AdminModule\Forms\DistrictForm
	 */
	public $districtForm;

	/**
	 * @var
	 * \AdminModule\Grids\DistrictGrid
	 */
	public $districtGrid;

	/**
	 * @var
	 * \Repository\District
	 */
	public $districtRepository;


	public function __construct(\AdminModule\Grids\DistrictGrid $districtGrid, \AdminModule\Forms\DistrictForm $districtForm, \Repository\DistrictRepository $districtRepository)
	{
		$this->districtGrid = $districtGrid;
		$this->districtRepository = $districtRepository;
		$this->districtForm = $districtForm;
	}


	public function beforeRender()
	{
		$this->template->title = "District";
	}


	public function renderDetail($id)
	{
		$data = $this->districtRepository->getSingle($id);
		        $this->template->data = $data;
	}


	public function renderEdit($id)
	{
		$data = $this->districtRepository->getFormDataById($id);

		$this->getComponent("edit")->setDefaults($data);
	}


	public function handleDelete($id)
	{
		$this->districtRepository->removeById($id);
	}


	public function createComponentNew()
	{
		$form = $this->districtForm->create();

		$form->onSuccess[] = function($form, $values) {
		    $entity = NULL;

		    try {
		        $entity = $this->districtRepository->insertForm($form->getValues(TRUE));
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
		$form = $this->districtForm->create();

		$form->onSuccess[] = function($form, $values) {
		    $entity = NULL;

		    try {
		        $entity = $this->districtRepository->updateForm($form->getValues(TRUE), $values["id"]);
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
		$source = $this->districtRepository->getAll(TRUE);

		return $this->districtGrid->create($source);
	}
}
