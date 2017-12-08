<?php

namespace AdminModule\Grids;

use Base\BaseGrid;


/**
 * PlacesGrid Grid class
 */
class PlacesGrid
{
	/** \AdminModule\Grids\Base */
	private $baseGrid;


	public function __construct(BaseGrid $baseGrid)
	{
		$this->baseGrid = $baseGrid;
	}


	public function create($source)
	{
		$grid = $this->baseGrid->create($source);

        $grid->setPagination(FALSE);

		$grid->addColumnLink("title", "Název", "Places:detail")->setSortable()->setFilterText();
		$grid->addColumnLink("district", "Okres", "Places:detail", "district.title")->setSortable()->setFilterText();

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
