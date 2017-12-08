<?php

namespace AdminModule\Forms;

use Base\BaseForm;

/**
 * PexesoForm Form class
 */
class PexesoForm
{
	/** \AdminModule\Forms\Base */
	private $baseForm;

	/** \Repository\Pexeso */
	private $pexesoRepository;


	public function __construct(BaseForm $baseForm, \Repository\Pexeso $pexesoRepository)
	{
		$this->baseForm = $baseForm;
		$this->pexesoRepository = $pexesoRepository;
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
