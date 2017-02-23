<?php 
namespace Hammal\Contracts;

interface ColumnHelperContract{
	/**
     * Detect ColumnHelper for used DB.
     * 
     * @return ColumnHelper
     */
	public static function detectColumnHelper();

	/**
     * Set foreigns and domestics of a Data Model.
     * 
     * @param  DataModel $dataModel 
     * @param  array $fks       
     * 
     * @return void   
     */
	public static function setupForeignsAndDomestics($dataModel,$fks);

	/**
     * Setup columns for DataModel.
     * 
     * @param  DataModel $dataModel
     * 
     * @return void
     */
	public static function setupColumns($model);
}
?>