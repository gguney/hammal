<?php 

namespace DataModel\DataModel;

use DataModel\Contracts\DataModelContract;
use DataModel\ColumnHelper\ColumnHelper;

abstract class DataModel implements DataModelContract
{
	protected $name;
	protected $model;
	protected $table;

	protected $tableFields = ['*'];
	protected $formFields = ['*'];
	protected $hiddenFields;

    protected $columns;
    protected $foreigns;
    protected $domestics;
    protected $rules;

    protected $pivot = false;
    protected $DATA_MODELS_PATH = "\\App\\Http\\DataModels" ;
    protected $MODELS_PATH = "\\App\\Http\\Models" ;

	public function __construct()
	{ 
		$this->setName();
		$this->remember();
		//SuperCacheHelper::rememberModel($this);
	}
	private function remember()
	{
		$columnHelper = ColumnHelper::detectColumnHelper();
		$columnHelper->setupColumns($this);
	    $columnHelper->getFKs($this);
	}
	public function getName()
	{
		return $this->name;
	}
	public function setName()
	{
		$name = get_class($this);

		$this->name =substr($name, strrpos($name, '\\') + 1);

		if(!isset($this->model))
		{
			if(!$this->pivot)
				$this->model = $this->toModelName($this->name);        
			else 
				$this->model = 'Ref'.$this->toModelName($this->name);        
 
		}
		$modelPath = $this->MODELS_PATH.'\\'.$this->model;
		$model = (new $modelPath);
		if(!isset($this->table))
		{
			if(!$this->pivot)
				$this->table = $this->toTableName($this->name);     
			else
				$this->table = 'ref_'.$this->toTableName($this->name);     
		}
		if(!isset($this->hiddenFields))
		{	
			$hiddenFields = $model->getHidden();
			$this->hiddenFields = $hiddenFields;
		}
	}
	private function toModelName($name)
	{	
		return $name;
	}
	private function toTableName($name)
	{	
		return lcfirst($name.'s');
	}
	public function getModelName()
	{
		return $this->model;
	}

	public function getTable()
	{
		return $this->table;
	}
	public function getColumns()
	{
		return $this->columns;
	}
	public function getRules()
	{
		return $this->rules;
	}
	public function getFormFields()
	{
        return $this->formFields;   
	}
	public function getTableFields()
	{
        return $this->tableFields;
	}
	public function getHiddenFields()
	{
		return $this->hiddenFields;
	}
	public function setRules($rules){
		$this->rules = $rules;
	}
	public function setColumns($columns){
		$this->columns = $columns;
	}

    public function setForeigns($foreigns)
    {
        $this->foreigns = $foreigns;
    }
    public function setFormFields(array $formFields)
    {
       
        $this->formFields = $formFields;   

    }
    public function setTableFields(array $tableFields)
    {
       
        $this->tableFields = $tableFields;   

    }
    public function getForeigns()
    {                   

        return $this->foreigns;
    }

    public function setDomestics($domestics)
    {
        $this->domestics = $domestics;
    }

    public function getDomestics()
    {

        return $this->domestics;
    }


}