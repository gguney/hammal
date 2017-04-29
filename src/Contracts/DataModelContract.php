<?php
namespace GGuney\Hammal\Contracts;

interface DataModelContract
{
    /**
     * Constructor. Set DataModel Name, remember the DataModel.
     * 
     */
	public function __construct();

	/**
	 * Get DataModels folder path.
	 * 
	 * @return string
	 */
	public function getDataModelsPath();

	/**
	 *  Get Models folder path.
	 * 
	 * @return string
	 */
	public function getModelsPath();


	/**
	 * Get name of the DataModel.
	 * 
	 * @return string
	 */
	public function getName();

	/**
	 * Set name of the DataModel
	 *
	 * @return void
	 */
	public function setName();

	/**
	 * Get user-friendly name of the DataModel.
	 * 
	 * @return string
	 */
	public function getShowName();

	/**
	 * Set special non-editable fields.
	 * 
	 * @param array $nonEditableFields
	 */
	public function setNonEditableFields($nonEditableFields);

	/**
	 * Get non-editable fields.
	 * 
	 * @return array
	 */
	public function getNonEditableFields();

	/**
	 * Get foreign model data.
	 * 
	 * @return array
	 */
    public function getForeignsData();

    /**
     * Set foreign model data.
     *
	 * @return void
     */
    public function setForeignsData();

    /**
     * Add foreign data.
     *
	 * @return void
     */
    public function addForeignData($data);

    /**
     * Get model name.
     * 
     * @return string
     */
	public function getModelName();

	/**
	 * Get table name.
	 * 
	 * @return string
	 */
	public function getTable();

	/**
	 * Get columns of DataModel.
	 * 
	 * @return array
	 */
	public function getColumns();

	/**
	 * Get rules for validation.
	 * 
	 * @return array
	 */
	public function getRules();

	/**
	 * Get form fields.
	 * 
	 * @return array
	 */
	public function getFormFields();

	/**
	 * Get table fields.
	 * 
	 * @return array
	 */
	public function getTableFields();

	/**
	 * Get hidden fields.
	 * 
	 * @return array
	 */
	public function getHiddenFields();

	/**
	 * Set rules for validation.
	 * 
	 * @param array
	 * 
	 * @return void
	 */
	public function setRules($rules);

	/**
	 * Set columns information.
	 *
	 * @param array
	 * 
	 * @return void
	 */
	public function setColumns($columns);

	/**
	 * Set foreign tables.
	 *
	 * @param array
	 * 
	 * @return void
	 */
    public function setForeigns($foreigns);

    /**
     * Set form fields.
     * 
     * @param array $formFields
     * 
	 * @return void
     */
    public function setFormFields(array $formFields);

    /**
     * Set table fields.
     * 
     * @param array $tableFields
     * 
	 * @return void
     */
    public function setTableFields(array $tableFields); 

    /**
     * Get foreigns of proper table.
     * 
     * @return void
     */
    public function getForeigns();

    /**
     * Set domestics of table.
     * 
     * @param array $domestics 
     *
	 * @return void
     */
    public function setDomestics($domestics);

    /**
     * Get domestics of table.
     * 
	 * @return array $domestics 
     */
    public function getDomestics();
}
?>