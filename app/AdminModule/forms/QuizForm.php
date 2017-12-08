<?php

namespace AdminModule\Forms;

/**
 * QuizForm Form class
 */
class QuizForm
{
	/** \AdminModule\Forms\Base */
	private $baseForm;

	/** \Repository\Quiz */
	private $quizRepository;


	public function __construct(Base $baseForm, \Repository\Quiz $quizRepository)
	{
		$this->baseForm = $baseForm;
		$this->quizRepository = $quizRepository;
	}


	public function create()
	{
		$form = $this->baseForm->create();

		$form->addHidden("id");

		$form->addText("title", "Titulek")->setRequired();

		$form->addSubmit("submit", "Uložit");
		$form->addSubmit("submit_stay", "Uložit a zůstat");

		return $form;
	}
}