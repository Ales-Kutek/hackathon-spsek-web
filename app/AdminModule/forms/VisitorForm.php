<?php

namespace AdminModule\Forms;

use Base\BaseForm;

/**
 * VisitorForm Form class
 */
class VisitorForm
{
	/** \AdminModule\Forms\Base */
	private $baseForm;

	/** \Repository\Visitor */
	private $visitorRepository;


	public function __construct(BaseForm $baseForm, \Repository\Visitor $visitorRepository)
	{
		$this->baseForm = $baseForm;
		$this->visitorRepository = $visitorRepository;
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
