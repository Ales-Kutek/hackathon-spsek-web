<?php

namespace UW\Core\Crud\Templates\AdminModule\Templates;

use Core\Crud;
use UW\Core\Crud\ITemplate;
use UW\Core\Crud\Templates\Base;

class DetailLatte extends Base implements ITemplate {
    public function generate($name) {
        
        $latte = 
'{block content}
<div class="row">
    <div class="col-md-12">
        <a class="btn-md btn btn-primary pull-right" href="{plink edit, $data->getId()}">Upravit</a>
    </div>
</div>
<div class="col-row">
    <div class="col-md-12">
         {$data->getTitle()}
    </div>
</div>
';
                
            
        
        $this->createTemplate((string) $latte, $name);
    }
}