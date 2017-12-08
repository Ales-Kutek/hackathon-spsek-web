<?php

namespace Base;

use Nette\Application\UI\Form;

class BaseForm {
    const PROMPT_TEXT = "--- Vybrat ---";

    /**
     * @return Form
     */
    public function create() {
        $form = new Form();
        
        $form->setRenderer(new \Instante\Bootstrap3Renderer\BootstrapRenderer);

        return $form;
    }
}