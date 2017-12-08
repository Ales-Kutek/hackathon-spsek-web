<?php

namespace UW\Core\Crud\Templates\AdminModule\Templates;

use UW\Core\Crud\ITemplate;
use UW\Core\Crud\Templates\Base;

class DefaultLatte extends Base implements ITemplate {
    public function generate($name) {
        
        $latte = 
'{block content}
<div class="row">
    <div class="col-md-12">
        <a class="btn-md btn btn-primary pull-right" href="{plink new}">Přidat</a>
    </div>
</div>
{control grid}
';
                
            
        
        $this->createTemplate((string) $latte, $name);
    }
}

