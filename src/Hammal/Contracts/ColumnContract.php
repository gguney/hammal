<?php 
namespace Hammal\Contracts;

interface ColumnContract{
	/**
	 * Set value to given index.
	 * @param string $index
	 * @param string $value
	 *
	 * @return void
	 */
	public function set($index, $value);

	/**
	 * Get value for the given index.
	 * 
	 * @param string $index
	 *
	 * @return string
	 */
	public function get($index);

	/**
	 * Gets the value of atrributes.
	 *
	 * @return array
	 */
	public function getAttributes();

	/**
	 * Set the value of atrributes.
	 *
	 * @param array $atrributes
	 *
	 * @return Column
	 */
	public function setAttributes($attributes);

	/**
	 * Get the editable value of column.
	 *
	 * @return string
	 */
	public function getEditable();

	/**
	 * Set the value of editable.
	 *
	 * @param string $editable
	 *
	 * @return Column
	 */
	public function setEditable($editable);

	/**
	 * Get the default value of column.
	 *
	 * @return string
	 */
	public function getDefault();

	/**
	 * Set default value for column.
	 *
	 * @param string $default
	 *
	 * @return Column
	 */
	public function setDefault($default);

}
?>