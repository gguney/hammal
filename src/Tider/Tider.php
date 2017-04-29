<?php
namespace GGuney\Hammal\Tider;

use GGuney\Hammal\Contracts\TiderContract;

class Tider implements TiderContract{

	/**
	 * Convert table name to model function name.
	 * 
	 * @param  string $tableName
	 * 
	 * @return string  
	 */
	public static function toFunctionName($tableName)
	{
		$tmpTableName = str_singular($tableName);
		$explodedTableName = explode('_',$tableName);
		$functionName = '';
		foreach($explodedTableName as $key => $partial)
		{
			if(sizeof($explodedTableName)-1 != $key)
				$functionName .= ($key==0) ? $partial : ucfirst($partial);
			else
				$functionName .= ($key==0) ? str_singular($partial) : ucfirst(str_singular($partial));
		}
		return $functionName;
	}

	/**
	 * Convert table name to model name.
	 * 
	 * @param  string $tableName 
	 * 
	 * @return string
	 */
	public static function toModelName($tableName)
	{
		return ucfirst(self::toFunctionName($tableName));
	}

	/**
	 * Make plural string to singular.
	 * 
	 * @param  string $plural 
	 * 
	 * @return string
	 */
	public static function singularize($plural)
	{
		return str_singular($plural);
	}	

	/**
	 * Remove dashes and make all words upper case.
	 * 
	 * @param  string $plural 
	 * 
	 * @return string
	 */
	public static function beautify($bad)
	{	
		return ucwords(str_replace('_',' ',$bad ));
	}

	/**
	 * Convert DataModel name to model name.
	 * 
	 * @param  string $DMName 
	 * 
	 * @return string
	 */
	public static function toModelNameFromDMName($DMName){
		return self::singularize($DMName);
	}

	/**
	 * Convert DataModel name to table name.
	 * 
	 * @param  string $DMName 
	 * @return string
	 */
	public static function toTableNameFromDMName($DMName){
		$name = preg_split('/(?=[A-Z])/',lcfirst($DMName));
		$name = strtolower(implode('_',$name));
		return lcfirst($name);
	}
}

