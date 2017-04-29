<?php 
namespace GGuney\Hammal\Contracts;

interface ColumnHelperContract{
	/**
     * Detect ColumnHelper for used DB.
     * 
     * @return ColumnHelper
     */
	public static function detectColumnHelper($DM);

	/**
     * Setup columns for DataModel.
     * 
     * @param  DataModel $dataModel
     * 
     * @return void
     */
	public function getColumns($model);
}
?>