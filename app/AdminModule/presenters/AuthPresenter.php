<?php

namespace AdminModule\Presenters;

use Forms\LoginForm;
use Nette\Http\Session;
use Nette\Mail\Message;
use Nette\Mail\SmtpMailer;
use Nette\Security\AuthenticationException;
use Nette\Utils\Random;
use NetteOpauth;
use Repository\UserRepository;

/**
 * Presenter pro přihlašování/odhlašování uživatelů a zasílání zapomenutého hesla.
 * Napojení na další providery pro přihlášení.
 */
class AuthPresenter extends BasePresenter
{

    /** @var LoginForm @inject */
    public $loginForm;

    /** @var  UserRepository @inject */
    public $userRepository;

    /** @var Session @inject */
    public $session;

    /**
     * Odhlášení uživatele
     * Smaže i data z Identity
     */
    public function actionLogout()
    {
        $this->getUser()->logout(TRUE);
        $this->flashMessage("Byli jste úspěšně odhlášeni.", 'success');
        $this->redirect("Auth:default");
    }

    /**
     * Továrnička na přihlašovací formulář a samotné přihlášení
     * @return \UW\Core\Components\Form
     */
    public function createComponentAuth()
    {
        $form = $this->loginForm->create();

        $form->onSuccess[] = function ($form, $values) {
            $entity = NULL;

            try {
                $this->user->setExpiration('14 days');
                $this->user->login($values->email, $values->password);
            } catch (\Exception $ex) {
                $this->flashMessage($ex->getMessage());
            }

            $this->redirect("Admin:Dashboard:default");
        };

        return $form;
    }
}
