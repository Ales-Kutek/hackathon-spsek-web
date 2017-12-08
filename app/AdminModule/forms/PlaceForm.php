<?php

namespace AdminModule\Forms;

use Base\BaseForm;

/**
 * PlaceForm Form class
 */
class PlaceForm
{
	/** \AdminModule\Forms\Base */
	private $baseForm;

	/** \Repository\Place */
	private $placeRepository;

	public function __construct(BaseForm $baseForm, \Repository\Place $placeRepository)
	{
		$this->baseForm = $baseForm;
		$this->placeRepository = $placeRepository;
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
