<?php

namespace AdminModule\Presenters;

use Nette\Utils\ArrayHash;

/**
 * Secure presenter class
 */
class SecurePresenter extends BasePresenter {

    public function checkRequirements($element) {
        if (!$this->user->isLoggedIn()) {
            $this->redirect("Auth:");
        }
    }
}
