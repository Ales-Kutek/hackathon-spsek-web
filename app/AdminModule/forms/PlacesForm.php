<?php

namespace AdminModule\Forms;

use Base\BaseForm;
use Repository\DistrictRepository;

/**
 * PlacesForm Form class
 */
class PlacesForm
{
	/** \AdminModule\Forms\Base */
	private $baseForm;

	/** \Repository\Places */
	private $placesRepository;
    /**
     * @var DistrictRepository
     */
    private $districtRepository;


    public function __construct(BaseForm $baseForm, \Repository\PlacesRepository $placesRepository, DistrictRepository $districtRepository)
	{
		$this->baseForm = $baseForm;
		$this->placesRepository = $placesRepository;
        $this->districtRepository = $districtRepository;
    }


	public function create()
	{
		$form = $this->baseForm->create();

		$form->addHidden("id");

		$form->addText("title", "Titulek")->setRequired();

        $form->addSelect("district", "Okres", $this->districtRepository->getSimpleList("title"))->setRequired()->setPrompt("Vybrat okres");

		$form->addSubmit("submit", "Uložit");
		$form->addSubmit("submit_stay", "Uložit a zůstat");

		return $form;
	}
}
