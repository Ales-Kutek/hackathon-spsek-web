<?php

namespace AdminModule\Presenters;

/**
 * QuizPresenter Presenter class
 */
class QuizPresenter extends SecurePresenter
{
	/**
	 * @var
	 * \AdminModule\Forms\QuizForm
	 */
	public $quizForm;

	/**
	 * @var
	 * \AdminModule\Grids\QuizGrid
	 */
	public $quizGrid;

	/**
	 * @var
	 * \Repository\Quiz
	 */
	public $quizRepository;


	public function __construct(\AdminModule\Grids\QuizGrid $quizGrid, \AdminModule\Forms\QuizForm $quizForm, \Repository\Quiz $quizRepository)
	{
		$this->quizGrid = $quizGrid;
		$this->quizRepository = $quizRepository;
		$this->quizForm = $quizForm;
	}


	public function beforeRender()
	{
		$this->template->title = "Quiz";
	}


	public function renderDetail($id)
	{
		$data = $this->quizRepository->getSingle($id);
		        $this->template->data = $data;
	}


	public function renderEdit($id)
	{
	}


	public function handleDelete($id)
	{
		$this->quizRepository->removeById($id);
	}


	public function createComponentNew()
	{
		$form = $this->quizForm->create();

		$form->onSuccess[] = function($form, $values) {
		    $entity = NULL;

		    try {
		        $entity = $this->quizRepository->insertForm($form->getValues(TRUE));
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
		$form = $this->quizForm->create();

        $id = $this->getParameter("id");

        $data = $this->quizRepository->getFormDataById($id);

        $form->setDefaults($data);

		$form->onSuccess[] = function($form, $values) {
		    $entity = NULL;

		    try {
		        $entity = $this->quizRepository->updateForm($form->getValues(TRUE), $values["id"]);
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
		$source = $this->quizRepository->getAll(TRUE);

		return $this->quizGrid->create($source);
	}
}
