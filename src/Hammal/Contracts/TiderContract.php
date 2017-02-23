<?php
namespace Hammal\Contracts;

interface TiderContract{

	/**
	 * Convert table name to model function name.
	 * 
	 * @param  string $tableName
	 * 
	 * @return string  
	 */
	public static function toFunctionName($tableName);

	/**
	 * Convert table name to model name.
	 * 
	 * @param  string $tableName 
	 * 
	 * @return string
	 */
	public static function toModelName($tableName);

	/**
	 * Make plural string to singular.
	 * 
	 * @param  string $plural 
	 * 
	 * @return string
	 */
	public static function singularize($plural);

	/**
	 * Remove dashes and make all words upper case.
	 * 
	 * @param  string $plural 
	 * 
	 * @return string
	 */
	public static function beautify($bad);

	/**
	 * Convert DataModel name to model name.
	 * 
	 * @param  string $DMName 
	 * 
	 * @return string
	 */
	public static function toModelNameFromDMName($DMName);

	/**
	 * Convert DataModel name to table name.
	 * 
	 * @param  string $DMName 
	 * @return string
	 */
	public static function toTableNameFromDMName($DMName);

}
?>