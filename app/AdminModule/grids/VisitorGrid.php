<?php

namespace AdminModule\Grids;

use Base\BaseGrid;

/**
 * VisitorGrid Grid class
 */
class VisitorGrid
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

		$grid->addColumnLink("title", "Název", "Visitor:detail")->setSortable()->setFilterText();
		$grid->addColumnNumber("score", "Skóre")->setSortable()->setFilterText();

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
