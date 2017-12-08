<?php
/**
 * Created by PhpStorm.
 * User: ales
 * Date: 10/8/17
 * Time: 2:35 PM
 */

namespace UW\Core\Crud\Templates\Package;


use UW\Core\Crud\ITemplate;
use UW\Core\Crud\Templates\Base;

class Grid extends Base implements ITemplate
{
    public function generate($name)
    {
        $str = '
namespace UW\\'.$name.'\Grids;

use UW\Core\Components\BaseGrid;

/**
 * '.$name.' Grid class
 */
class '.$name.'Grid
{
	/** @var \UW\Core\Components\BaseGrid @inject */
	public $baseGrid;

	public function create($source)
	{
		$grid = $this->baseGrid->create($source);

		$grid->addColumnLink("title", "Název", "'.$name.':detail")->setSortable()->setFilterText();

		$grid->addAction("edit", "")
		        ->setIcon("pencil")
		        ->setTitle("Upravit");
		$grid->addAction("delete", "", "delete!")
		        ->setIcon("trash")
		        ->setTitle("Smazat")
		        ->setClass("btn btn-xs btn-danger")
		        ->setConfirm(function($item) {
		            return "Opravdu chcete smazat {$item->id}?";
		        }, "id");
		        
		return $grid;
	}
}
';

        $this->createTemplate($str, $name);
    }
}