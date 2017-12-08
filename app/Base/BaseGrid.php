<?php

/**
 * GridFactory
 */
namespace Base;

use Ublaboo\DataGrid\DataGrid;

/**
 * Base class
 * základní továrnička pro grid
 */
class BaseGrid
{

    public static function translateGrid(DataGrid &$grid)
    {
        $translator = new \Ublaboo\DataGrid\Localization\SimpleTranslator([
            'ublaboo_datagrid.no_item_found_reset' => 'Žádné položky nenalezeny. Filtr můžete vynulovat',
            'ublaboo_datagrid.no_item_found' => 'Žádné položky nenalezeny.',
            'ublaboo_datagrid.here' => 'zde',
            'ublaboo_datagrid.items' => 'Položky',
            'ublaboo_datagrid.all' => 'všechny',
            'ublaboo_datagrid.from' => 'z',
            'ublaboo_datagrid.reset_filter' => 'Resetovat filtr',
            'ublaboo_datagrid.group_actions' => 'Hromadné akce',
            'ublaboo_datagrid.show_all_columns' => 'Zobrazit všechny sloupce',
            'ublaboo_datagrid.hide_column' => 'Skrýt sloupec',
            'ublaboo_datagrid.action' => 'Akce',
            'ublaboo_datagrid.previous' => 'Předchozí',
            'ublaboo_datagrid.next' => 'Další',
            'ublaboo_datagrid.choose' => 'Vyberte',
            'ublaboo_datagrid.execute' => 'Provést',
            'ublaboo_datagrid.save' => 'Uložit',
            'ublaboo_datagrid.cancel' => 'Zrušit',
            'ublaboo_datagrid.filter_submit_button' => 'Hledat',
            'Name' => 'Jméno',
            'Inserted' => 'Vloženo'
        ]);

        $grid->setTranslator($translator);

        return $grid;
    }

    /**
     * @param mixed $source
     * @return \Ublaboo\DataGrid\DataGrid
     */
    public function create(&$source)
    {
        $grid = new DataGrid();
        
        $grid->setDataSource($source);

        $grid->onAnchor[] = function () use ($grid) {
            $grid->template->headHtml = "";
            $grid->redrawControl("headHtml");
        };


        $grid->setTemplateFile(__DIR__ . DS . "templates" . DS . "grid" . DS . "datagrid.latte");

        self::translateGrid($grid);

        $grid->setDefaultPerPage(20);
        $grid->setItemsPerPageList([10, 20, 30, 50, 100]);
        $grid->setAutoSubmit(FALSE);
        $grid->setRememberState(TRUE);
        $grid->setStrictSessionFilterValues(FALSE);
        
        return $grid;
    }

}