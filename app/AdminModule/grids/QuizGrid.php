<?php

namespace AdminModule\Grids;

use Base\BaseGrid;

/**
 * QuizGrid Grid class
 */
class QuizGrid
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

		$grid->addColumnLink("title", "Název", "Quiz:detail")->setSortable()->setFilterText();

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
