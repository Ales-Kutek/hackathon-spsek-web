<?php

namespace AdminModule\Forms;

use Base\BaseForm;
use Repository\Device;

/**
 * PlaceForm Form class
 */
class PlaceForm
{
	/** \AdminModule\Forms\Base */
	private $baseForm;

	/** \Repository\Place */
	private $placeRepository;
    /**
     * @var Device
     */
    private $deviceRepository;

    /**
     * PlaceForm constructor.
     * @param BaseForm $baseForm
     * @param \Repository\Place $placeRepository
     * @param Device $deviceRepository
     */
    public function __construct(BaseForm $baseForm, \Repository\Place $placeRepository, Device $deviceRepository)
	{
		$this->baseForm = $baseForm;
		$this->placeRepository = $placeRepository;
        $this->deviceRepository = $deviceRepository;
    }


	public function create()
	{
		$form = $this->baseForm->create();

		$form->addHidden("id");

        $form->addText("title", "Název tabule")->setRequired();
        $form->addTextArea("description", "Krátký popis");

        $form->addSelect("device", "Zařízení", $this->deviceRepository->getPairs(function (\Entity\Device $value) {
            return $value->getTitle() . " " . "( " . $value->getCode() . ")";
        }));


        $form->addSubmit("submit", "Uložit");
		$form->addSubmit("submit_stay", "Uložit a zůstat");

		return $form;
	}
}
