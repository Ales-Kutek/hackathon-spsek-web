<?php

namespace Forms;

use Base\BaseForm;
use Repository\UserRepository;

class LoginForm {

    /** @var BaseForm $baseForm */
    public $baseForm;

    /** @var UserRepository $userRepository */
    public $userRepository;

    public function __construct(BaseForm $baseForm, UserRepository $userRepository) {
        $this->baseForm = $baseForm;
        $this->userRepository = $userRepository;
    }

    /**
     * Vytvoření formuláře na přihlášení
     * @return \UW\Core\Components\Form
     */
    public function create() {
        $form = $this->baseForm->create();

        $form->addText("email", "E-mail")
                ->setAttribute('placeholder', $form['email']->caption)
                ->setRequired();
        $form->addPassword("password", "Heslo")
                ->setAttribute('placeholder', $form['password']->caption)
                ->setRequired();
        $form->addSubmit("submit", "Přihlásit se");

        return $form;
    }

}
