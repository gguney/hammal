<?php
namespace Hammal\ColumnHelper;

use Hammal\Contracts\ColumnHelperContract;
use Hammal\Tider\Tider;

abstract class ColumnHelper implements ColumnHelperContract{
	
    /**
     * Type conversation for validator.
     * 
     * @var array
     */
    protected static $VALIDATOR_TYPES = ['text' => 'string', 'number'=> 'numeric', 'email' => 'email', 'password' => 'string', 'date' => 'date', 'datetime' => 'datetime', 'checkbox' => 'string', 'time' => 'time', 'file' => 'file'];

    /**
     * Validation seperator.
     * 
     * @var string
     */
    protected static $OR = '|';

    /**
     * Special HTML field types.
     * 
     * @var array
     */
    protected static $SPECIAL_FIELD_TYPES = ['password'=>'password', 'email'=>'email'];

    /**
     * Special field names.
     * 
     * @var array
     */
    protected static $SPECIAL_FIELD_NAMES = ['file_path'=>'file', 'path'=>'file'];

    /**
     * Hard-codded non-editable fields.
     * 
     * @var array
     */
    protected static $NON_EDITABLE_FIELDS = ['id','created_by', 'deleted_by', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * DB field type to HTML input type conversation.
     * 
     * @var array
     */
    protected static $FIELD_TYPES = [
        'varchar' => 'text',
        'tinyint' => 'number','int' => 'number', 'bigint' => 'number', 'smallint' => 'number', 'mediumint'=>'number',
        'int2' => 'number','int4'=>'number','int8'=>'number',
        'blob' => 'text', 'char' => 'text', 'text' => 'text', 'mediumtext' => 'text', 'enum'=>'text',
        'decimal' => 'number', 'double' => 'number',
        'bool' => 'checkbox', 'boolean' => 'checkbox',
        'file' => 'file',
        'timestamp' => 'datetime', 'date' => 'date','datetime' => 'datetime', 'time' => 'time'
        ];

    /**
     * Hard-codded lengths.
     * 
     * @var array
     */
    protected static $LENGHTS = ['text' => '1000', 'json' => '1000', 'boolean' => '1'];

    /**
     * DB foreign field variables to ColumnHelper variable.
     * 
     * @var array
     */
    protected static $FOREIGN_FIELDS = ['constraint_name'=>'constraintName', 'table_name'=> 'tableName', 'column_name'=> 'columnName', 'foreign_table_name'=>'foreignTableName', 'foreign_column_name'=>'foreignColumnName'];

    /**
     * Detect ColumnHelper for used DB.
     * 
     * @return ColumnHelper
     */
    public static function detectColumnHelper()
    {
    	$dbConnection = config('database.default');
    	if( isset($dbConnection) )
    	{
    		switch ($dbConnection){
    			case 'pgsql':
        			$columnHelper = new PgSqlColumnHelper();
        			break;
        		default:
        			$columnHelper = new MySqlColumnHelper();
        			break;
    		}
    		return $columnHelper;
    	}
    	else
    	{
    		throw new Exception('DB_CONNECTION is not set');
    	}
    }

    /**
     * Set foreigns and domestics of a Data Model.
     * 
     * @param  DataModel $dataModel 
     * @param  array $fks       
     * 
     * @return void   
     */
    public static function setupForeignsAndDomestics($dataModel, $fks)
    {
        foreach($fks as $fk)
        {   
            if($fk->table_name == $dataModel->getTable())
            {
                $foreign = new \stdClass();
                foreach(self::$FOREIGN_FIELDS as $key => $foreignField)
                {
                    $foreign->$foreignField = $fk->$key;
                }
                $foreign->functionName = Tider::toFunctionName($fk->foreign_table_name);
                $foreign->foreignModelName = Tider::toModelName($fk->foreign_table_name);
                //$foreign->dataModelName = $beautyfier->toDataModelName($fk->foreign_table_name);
                $foreigns[$fk->column_name] = $foreign;
            }
            else
            {
                $domestic = new \stdClass();

                foreach(self::$FOREIGN_FIELDS as $key => $foreignField)
                {
                    $domestic->$foreignField = $fk->$key;
                }
                $domestic->functionName = Tider::toFunctionName($fk->table_name);
                //$domestic->foreignModelName = $beautyfier->toForeignModelName($domestic->functionName);
                //$domestic->dataModelName = $beautyfier->toDataModelName($fk->table_name);
                $domestics[$domestic->functionName] = $domestic;
            }
        }
        if(isset($foreigns))
            $dataModel->setForeigns($foreigns);
        if(isset($domestics))
            $dataModel->setDomestics($domestics);
    }

}
?>