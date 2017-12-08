<?php

namespace AdminModule\Forms;

use Base\BaseForm;

/**
 * DeviceForm Form class
 */
class DeviceForm
{
	/** \AdminModule\Forms\Base */
	private $baseForm;

	/** \Repository\Device */
	private $deviceRepository;


	public function __construct(BaseForm $baseForm, \Repository\Device $deviceRepository)
	{
		$this->baseForm = $baseForm;
		$this->deviceRepository = $deviceRepository;
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
