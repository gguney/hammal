<?php 

namespace ModelCourier\DataModel;

use ModelCourier\Contracts\DataModelContract;
use ModelCourier\ColumnHelper\ColumnHelper;

abstract class DataModel implements DataModelContract
{
	protected $name;
	protected $model;
	protected $table;

	protected $tableFields = ['*'];
	protected $formFields = ['*'];
	protected $hiddenFields;
    protected $nonEditableFields;

    protected $columns;
    protected $foreigns;
    protected $domestics;
    protected $rules;

    protected $foreignsData;

    protected $pivot = false;
    protected static $DATA_MODELS_PATH = "\\App\\Http\\DataModels\\" ;
    protected static $MODELS_PATH = "\\App\\Http\\Models\\" ;

	public function __construct()
	{ 
		$this->setName();
		$this->remember();
		//SuperCacheHelper::rememberModel($this);
	}
	public static function getDataModelsPath()
	{
		return self::$DATA_MODELS_PATH;
	}
	public static function getModelsPath()
	{
		return self::$MODELS_PATH;
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

	public function setNonEditableFields($nonEditableFields)
	{
		$this->nonEditableFields = $nonEditableFields;
	}
	public function getNonEditableFields()
	{
		return $this->nonEditableFields;
	}

	public function setName()
	{
		$name = get_class($this);
		$this->name = substr($name, strrpos($name, '\\') + 1);
		if(!isset($this->model))
		{
			if(!$this->pivot)
				$this->model = $this->toModelName($this->name);        
			else 
				$this->model = 'Ref'.$this->toModelName($this->name);        
		}

		$modelPath = self::$MODELS_PATH.$this->model;
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

    public function getForeignsData()
    {
        return $this->foreignsData;
    }
    /*
    public function getItemWithDomestics($id){
        $item = $this;
        $domestics = $this->getDomestics();
        if(isset($domestics))
        {
            foreach ($domestics as $key => $domestic) 
            {
                if(method_exists($this,$domestic->functionName))
                    $item = $item->with($domestic->functionName);
                else{
                    var_dump("ERROR: From SuperModel.php ".$domestic->functionName. ' does not exists in '.get_class($this));
                }
            }     
        } 
        $item = $item->find($id);
        return $item;
    }
*/

    public function setForeignsData(){
        if(isset($this->foreigns))
        {
            foreach ($this->foreigns as $key=>$foreign) 
            {
                $array= [];
                $foreignModelName = $foreign->foreignModelName;

				$modelPath = $this->MODELS_PATH.'\\'.$foreignModelName;
                $foreignDatas = (new $modelPath)->orderBy('id')->get();
                $array[$foreign->columnName] = $foreignDatas;
                $this->addForeignData($array);  
            }     
        } 
    }
    public function addForeignData($data)
    {   
        $key = array_keys($data)[0];
        $this->foreignsData[$key] = $data[$key];
    }

	private function toModelName($name)
	{	
		return substr($name,0, -1);

	}
	private function toTableName($name)
	{	
		return lcfirst($name);
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