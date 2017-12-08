<?php
/**
 * Created by PhpStorm.
 * User: ales
 * Date: 08/12/17
 * Time: 18:28
 */

namespace UW\Core\App\Helper;


use Latte\Engine;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Forms\Container;
use Nette\Forms\Controls\HiddenField;
use Nette\Forms\Controls\SelectBox;
use Nette\Forms\Controls\TextInput;
use Nette\Utils\Html;

class HtmlControl extends TextInput {
    private $html;

    public function __construct($html)
    {
        $this->html = $html;
    }

    public function getControl()
    {
        return parent::getControl();
    }

    public static function register()
    {
        Container::extensionMethod("addHtml", function ($container, $name, $html) {
            $container[$name] = new HtmlControl($html);
        });
    }
}