<?php

namespace AdminModule\Forms;

use Base\BaseForm;
use Repository\Pexeso;
use Repository\Quiz;
use UW\Core\ORM\Repository\CoreRepository;

/**
 * DeviceForm Form class
 */
class DeviceForm
{
	/** \AdminModule\Forms\Base */
	private $baseForm;

	/** \Repository\Device */
	private $deviceRepository;
    /**
     * @var Quiz
     */
    private $quizRepository;
    /**
     * @var Pexeso
     */
    private $pexesoRepository;

    public function __construct(BaseForm $baseForm, \Repository\Device $deviceRepository, Quiz $quizRepository, Pexeso $pexesoRepository)
	{
		$this->baseForm = $baseForm;
		$this->deviceRepository = $deviceRepository;
        $this->quizRepository = $quizRepository;
        $this->pexesoRepository = $pexesoRepository;
    }


	public function create()
	{
		$form = $this->baseForm->create();

		$form->addHidden("id");

		$form->addText("title", "Titulek")->setAttribute("placeholder", "Nepovinné");
		$form->addText("code", "Označení")->setRequired();

		$form->addSelect("minigame", "Vybrat mini hru", $this->getMiniGames())->setRequired()->setPrompt("--- Vybrat ----");

		$form->addSubmit("submit", "Uložit");
		$form->addSubmit("submit_stay", "Uložit a zůstat");

		return $form;
	}

    private function getMiniGames(): array
    {
      $result = array();

        $this
            ->addMiniGame($result, "Kvízy", "quiz", $this->quizRepository)
            ->addMiniGame($result, "Pexesa", "pexeso", $this->pexesoRepository);

      return $result;
	}

    private function addMiniGame(array &$data, string $title, string $prefix, CoreRepository $repository)
    {
        $data[$title] = $repository->getPairs(function ($value) {
            return $value->getTitle();
        }, function ($value) use($prefix) {
            return $prefix . "_" . $value->getId();
          });

    return $this;
	}
}
