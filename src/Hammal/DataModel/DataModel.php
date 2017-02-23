<?php 
namespace Hammal\DataModel;

use Hammal\Contracts\DataModelContract;
use Hammal\ColumnHelper\ColumnHelper;
use Hammal\Tider\Tider;

abstract class DataModel implements DataModelContract
{
	/**
	 * DataModel Name
	 * 
	 * @var string
	 */
	protected $name;

	/**
	 * DataModel's model name.
	 * 
	 * @var string
	 */
	protected $model;

	/**
	 * Model's table name.
	 * 
	 * @var string
	 */
	protected $table;

	/**
	 * Table fields array. Default, all fields.
	 * 
	 * @var array
	 */
	protected $tableFields = ['*'];
	
	/**
	 * Form fields array. Default, all fields.
	 * 
	 * @var array
	 */
	protected $formFields = ['*'];

	/**
	 * Hidden fields array.
	 * 
	 * @var array
	 */
	protected $hiddenFields;

	/**
	 * Special non editable fields.
	 * 
	 * @var array
	 */
    protected $nonEditableFields;

    /**
     * Columns of DataModel.
     * 
     * @var array
     */
    protected $columns;

    /**
     * Foreign tables of DataModel.
     *
     * @var array
     */
    protected $foreigns;

    /**
     * Domestic tables of DataModel.
     * 
     * @var array
     */
    protected $domestics;

    /**
     * Rules of columns of DataModel.
     * 
     * @var array
     */
    protected $rules;

    /**
     * Data of foreign model.
     * 
     * @var array
     */
    protected $foreignsData;

    /**
     * Is table pivot or reference table? 
     *
     * @var boolean
     */
    protected $pivot = false;

    /**
     * DataModels folder path.
     * 
     * @var string
     */
    protected static $DATA_MODELS_PATH = "\\App\\Http\\DataModels\\" ;

    /**
     * Models folder path.
     * 
     * @var string
     */
    protected static $MODELS_PATH = "\\App\\Http\\Models\\" ;

    /**
     * Constructor. Set DataModel Name, remember the DataModel.
     * 
     */
	public function __construct()
	{ 
		$this->setName();
		$this->remember();
		//SuperCacheHelper::rememberModel($this);
	}

	/**
	 * Get DataModels folder path.
	 * 
	 * @return string
	 */
	public static function getDataModelsPath()
	{
		return self::$DATA_MODELS_PATH;
	}

	/**
	 *  Get Models folder path.
	 * 
	 * @return string
	 */
	public static function getModelsPath()
	{
		return self::$MODELS_PATH;
	}

	/**
	 * Get column data if columns cached.
	 * 
	 * @return void
	 */
	private function remember()
	{
		$columnHelper = ColumnHelper::detectColumnHelper();
		$columnHelper->setupColumns($this);
	    $columnHelper->getFKs($this);
	}

	/**
	 * Set types of the columns.
	 * 
	 * @param array $array
	 *
	 * @return void
	 */
	public function setColumnType($array)
	{
        $columns = $this->getColumns();
        foreach ($array as $columnIndex => $columnNewType) {
        	$columns[$columnIndex]->set('type',$columnNewType);
        }
        $this->setColumns($columns);
	}

	/**
	 * Get name of the DataModel.
	 * 
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Set name of the DataModel
	 *
	 * @return void
	 */
	public function setName()
	{
		$name = get_class($this);
		$this->name = substr($name, strrpos($name, '\\') + 1);
		if(!isset($this->model))
		{
			if(!$this->pivot)
				$this->model = Tider::toModelNameFromDMName($this->name);        
			else 
				$this->model = 'Ref'.Tider::toModelNameFromDMName($this->name);        
		}
		$modelPath = self::$MODELS_PATH.$this->model;
		$model = (new $modelPath);
		if(!isset($this->table))
		{
			if(!$this->pivot)
				$this->table = Tider::toTableNameFromDMName($this->name);     
			else
				$this->table = 'ref_'.Tider::toTableNameFromDMName($this->name);     
		}
		if(!isset($this->hiddenFields))
		{	
			$hiddenFields = $model->getHidden();
			$this->hiddenFields = $hiddenFields;
		}
	}
	
	/**
	 * Get user-friendly name of the DataModel.
	 * 
	 * @return string
	 */
	public function getShowName()
	{
		if(\Lang::has('general.'.$this->name))
			return trans('general.'.$this->name);
		else
			return Tider::beautify($this->table);
	}

	/**
	 * Set special non-editable fields.
	 * 
	 * @param array $nonEditableFields
	 */
	public function setNonEditableFields($nonEditableFields)
	{
		$this->nonEditableFields = $nonEditableFields;
	}

	/**
	 * Get non-editable fields.
	 * 
	 * @return array
	 */
	public function getNonEditableFields()
	{
		return $this->nonEditableFields;
	}

	/**
	 * Get foreign model data.
	 * 
	 * @return array
	 */
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

    /**
     * Set foreign model data.
     *
	 * @return void
     */
    public function setForeignsData(){
        if(isset($this->foreigns))
        {
            foreach ($this->foreigns as $key=>$foreign) 
            {
                $array= [];
                $foreignModelName = $foreign->foreignModelName;

				$modelPath = self::$MODELS_PATH.$foreignModelName;
                $foreignDatas = (new $modelPath)->orderBy('id')->get();
                $array[$foreign->columnName] = $foreignDatas;
                $this->addForeignData($array);  
            }     
        } 
    }

    /**
     * Add foreign data.
     *
	 * @return void
     */
    public function addForeignData($data)
    {   
        $key = array_keys($data)[0];
        $this->foreignsData[$key] = $data[$key];
    }

    /**
     * Get model name.
     * 
     * @return string
     */
	public function getModelName()
	{
		return $this->model;
	}

	/**
	 * Get table name.
	 * 
	 * @return string
	 */
	public function getTable()
	{
		return $this->table;
	}

	/**
	 * Get columns of DataModel.
	 * 
	 * @return array
	 */
	public function getColumns()
	{
		return $this->columns;
	}

	/**
	 * Get rules for validation.
	 * 
	 * @return array
	 */
	public function getRules()
	{
		return $this->rules;
	}

	/**
	 * Get form fields.
	 * 
	 * @return array
	 */
	public function getFormFields()
	{
        return $this->formFields;   
	}

	/**
	 * Get table fields.
	 * 
	 * @return array
	 */
	public function getTableFields()
	{
        return $this->tableFields;
	}

	/**
	 * Get hidden fields.
	 * 
	 * @return array
	 */
	public function getHiddenFields()
	{
		return $this->hiddenFields;
	}

	/**
	 * Set rules for validation.
	 * 
	 * @param array
	 * 
	 * @return void
	 */
	public function setRules($rules){
		$this->rules = $rules;
	}

	/**
	 * Set columns information.
	 *
	 * @param array
	 * 
	 * @return void
	 */
	public function setColumns($columns){
		$this->columns = $columns;
	}

	/**
	 * Set foreign tables.
	 *
	 * @param array
	 * 
	 * @return void
	 */
    public function setForeigns($foreigns)
    {
        $this->foreigns = $foreigns;
    }

    /**
     * Set form fields.
     * 
     * @param array $formFields
     * 
	 * @return void
     */
    public function setFormFields(array $formFields)
    {
        $this->formFields = $formFields;   
    }

    /**
     * Set table fields.
     * 
     * @param array $tableFields
     * 
	 * @return void
     */
    public function setTableFields(array $tableFields)
    {
        $this->tableFields = $tableFields;   
    }

    /**
     * Get foreigns of proper table.
     * 
     * @return void
     */
    public function getForeigns()
    {                   
        return $this->foreigns;
    }

    /**
     * Set domestics of table.
     * 
     * @param array $domestics 
     *
	 * @return void
     */
    public function setDomestics($domestics)
    {
        $this->domestics = $domestics;
    }

    /**
     * Get domestics of table.
     * 
	 * @return array $domestics 
     */
    public function getDomestics()
    {
        return $this->domestics;
    }

    /**
     * Gets the value of file.
     *
     * @return string

    public function getFile()
    {
        return $this->file;
    }

    /**
     * Sets the value of file.
     *
     * @param string $file
     *
     * @return DataModel
 
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }
        */
}