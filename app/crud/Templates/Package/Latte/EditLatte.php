<?php

namespace UW\Core\Crud\Templates\Package\Latte;

use Core\Crud;
use UW\Core\Crud\ITemplate;
use UW\Core\Crud\Templates\Base;

class EditLatte extends Base implements ITemplate {
    public function generate($name) {
        
        $latte = 
'{block content}
{control edit}
';
                
            
        
        $this->createTemplate((string) $latte, $name);
    }
}

