<?php

namespace AdminModule\Forms;

use Base\BaseForm;

/**
 * DistrictForm Form class
 */
class DistrictForm
{
	/** \AdminModule\Forms\Base */
	private $baseForm;

	/** \Repository\District */
	private $districtRepository;


	public function __construct(BaseForm $baseForm, \Repository\DistrictRepository $districtRepository)
	{
		$this->baseForm = $baseForm;
		$this->districtRepository = $districtRepository;
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
