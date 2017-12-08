<?php

namespace AdminModule\Presenters;

/**
 * DevicePresenter Presenter class
 */
class DevicePresenter extends SecurePresenter
{
	/**
	 * @var
	 * \AdminModule\Forms\DeviceForm
	 */
	public $deviceForm;

	/**
	 * @var
	 * \AdminModule\Grids\DeviceGrid
	 */
	public $deviceGrid;

	/**
	 * @var
	 * \Repository\Device
	 */
	public $deviceRepository;


	public function __construct(\AdminModule\Grids\DeviceGrid $deviceGrid, \AdminModule\Forms\DeviceForm $deviceForm, \Repository\Device $deviceRepository)
	{
		$this->deviceGrid = $deviceGrid;
		$this->deviceRepository = $deviceRepository;
		$this->deviceForm = $deviceForm;
	}


	public function beforeRender()
	{
		$this->template->title = "Zařízení";
	}


	public function renderDetail($id)
	{
		$data = $this->deviceRepository->getSingle($id);
		        $this->template->data = $data;
	}


	public function renderEdit($id)
	{
		$data = $this->deviceRepository->getFormDataById($id);

		$this->getComponent("edit")->setDefaults($data);
	}


	public function handleDelete($id)
	{
		$this->deviceRepository->removeById($id);
	}


	public function createComponentNew()
	{
		$form = $this->deviceForm->create();

		$form->onSuccess[] = function($form, $values) {
		    $entity = NULL;

		    try {
		        $entity = $this->deviceRepository->insertForm($form->getValues(TRUE));
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
		$form = $this->deviceForm->create();

		$form->onSuccess[] = function($form, $values) {
		    $entity = NULL;

		    try {
		        $entity = $this->deviceRepository->updateForm($form->getValues(TRUE), $values["id"]);
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
		$source = $this->deviceRepository->getAll(TRUE);

		return $this->deviceGrid->create($source);
	}
}
