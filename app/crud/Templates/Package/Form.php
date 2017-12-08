<?php
/**
 * Created by PhpStorm.
 * User: ales
 * Date: 10/8/17
 * Time: 2:26 PM
 */

namespace UW\Core\Crud\Templates\Package;


use UW\Core\Crud\ITemplate;
use UW\Core\Crud\Templates\Base;

class Form extends Base implements ITemplate
{
    public function generate($name)
    {
        $str = '
namespace UW\\' . $name . '\Forms;

use UW\Core\Components\Form;
use UW\Core\Components\BaseForm;
use UW\\' . $name . '\Repository\\' . $name . 'Repository;

/**
 * ' . $name . ' Form class
 */
class ' . $name . 'Form
{
	/** @var BaseForm @inject */
	public $baseForm;

	public function create()
	{
		$form = $this->baseForm->create();

		$form->addHidden("id");

		$form->addText("title", "Název");
		
        $form->addSubmit("submit", "Uložit");
        $form->addSubmit("submit_stay", "Uložit a zůstat");
		
		return $form;
    }
}
';

        $this->createTemplate($str, $name);
    }
}