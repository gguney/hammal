<?php
namespace GGuney\Hammal\Contracts;

interface RulerContract{

	/**
	 * Get rules from columns data.
	 * 
	 * @param  array $columns
	 * @param  array $formFields 
	 * 
	 * @return array
	 */
	public static function getRules($columns, $formFields);

}
?>