<?php

namespace AdminModule\Forms;

use Base\BaseForm;
use Nette\Forms\Container;
use WebChemistry\Forms\Controls\DI\MultiplierExtension;
use WebChemistry\Forms\Controls\Multiplier;

/**
 * QuizForm Form class
 */
class QuizForm
{
	/** \AdminModule\Forms\Base */
    private $baseForm;

    /** \Repository\Quiz */
    private $quizRepository;

    public function __construct(BaseForm $baseForm, \Repository\Quiz $quizRepository)
    {
        $this->baseForm = $baseForm;
        $this->quizRepository = $quizRepository;
	}

	public function create()
	{
		$form = $this->baseForm->create();

		$form->addHidden("id");

		$form->addText("title", "Titulek")->setRequired();

		/** @var Multiplier $questions */
		$questions = $form->addMultiplier("quiz_question", function (Container $container) {
		    $container->addText("title", "Název otázky")->setRequired();
		    $container->addUpload("file_path", "Obrázek");
		    $container->addCheckbox("true", "Správna odpověď");
        }, 2);

		$questions->addCreateButton("Přidat otázku");
		$questions->addRemoveButton("Odstranit");

		$form->addSubmit("submit", "Uložit");
		$form->addSubmit("submit_stay", "Uložit a zůstat");

		return $form;
	}
}
