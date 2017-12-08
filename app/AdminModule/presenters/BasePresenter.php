<?php

namespace AdminModule\Presenters;

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends \Nette\Application\UI\Presenter {
    public function startup() {
        parent::startup();
    }
}
