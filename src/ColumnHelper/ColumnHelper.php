<?php
namespace GGuney\Hammal\ColumnHelper;

use GGuney\Hammal\Contracts\ColumnHelperContract;
use GGuney\Hammal\Tider\Tider;
use GGuney\Hammal\Ruler\Ruler;
use Illuminate\Support\Facades\Cache;
abstract class ColumnHelper implements ColumnHelperContract
{

    /**
     * Special HTML field types.
     *
     * @var array
     */
    protected $SPECIAL_FIELD_TYPES = ['password' => 'password', 'email' => 'email'];

    /**
     * Special field names.
     *
     * @var array
     */
    protected $SPECIAL_FIELD_NAMES = ['file_path' => 'file', 'path' => 'file'];

    /**
     * Hard-codded non-editable fields.
     *
     * @var array
     */
    protected $NON_EDITABLE_FIELDS = ['id', 'created_by', 'deleted_by', 'created_at', 'updated_at', 'deleted_at'];


    /**
     * DB field type to HTML input type conversation.
     *
     * @var array
     */
    protected $FIELD_TYPES = [
        'varchar'    => 'text',
        'tinyint'    => 'number',
        'int'        => 'number',
        'bigint'     => 'number',
        'smallint'   => 'number',
        'mediumint'  => 'number',
        'int2'       => 'number',
        'int4'       => 'number',
        'int8'       => 'number',
        'blob'       => 'text',
        'char'       => 'text',
        'text'       => 'text',
        'mediumtext' => 'text',
        'enum'       => 'text',
        'decimal'    => 'number',
        'double'     => 'number',
        'bool'       => 'checkbox',
        'boolean'    => 'checkbox',
        'file'       => 'file',
        'timestamp'  => 'datetime-local',
        'date'       => 'date',
        'datetime'   => 'datetime-local',
        'time'       => 'time'
    ];

    /**
     * Hard-codded lengths.
     *
     * @var array
     */
    protected $LENGHTS = ['text' => '1000', 'json' => '1000', 'boolean' => '1'];

    /**
     * DB foreign field variables to ColumnHelper variable.
     *
     * @var array
     */
    protected $FOREIGN_FIELDS = [
        'constraint_name'     => 'constraintName',
        'table_name'          => 'tableName',
        'column_name'         => 'columnName',
        'foreign_table_name'  => 'foreignTableName',
        'foreign_column_name' => 'foreignColumnName'
    ];

    protected $dataModel;
    protected $selectFields;
    protected $DB_COLUMN_QUERY;
    protected $DB_FOREIGNS_QUERY;

    public function __construct($DM)
    {
        $this->dataModel = $DM;
        $this->selectFields = $this->getSelectFields();
    }

    /**
     * Get Non-Editable Fields.
     *
     * @return array
     */
    public function getNonEditableFields()
    {
        return $this->NON_EDITABLE_FIELDS;
    }
    /**
     * Detect ColumnHelper for used DB.
     *
     * @return ColumnHelper
     */
    public static function detectColumnHelper($DM)
    {
        $dbConnection = config('database.default');
        if (isset($dbConnection)) {
            switch ($dbConnection) {
                case 'pgsql':
                    $columnHelper = new PgSqlColumnHelper($DM);
                    break;
                default:
                    $columnHelper = new MySqlColumnHelper($DM);
                    break;
            }
            return $columnHelper;
        } else {
            throw new Exception('DB_CONNECTION is not set');
        }
    }

    public function getSelectFields()
    {
        return implode(",", array_keys($this->ESSENTIAL_FIELD_VARS));
    }

    public function getFieldVars()
    {
        return array_keys($this->ESSENTIAL_FIELD_VARS);
    }

    public function getType($columnName, $dataType)
    {
        if (isset($this->SPECIAL_FIELD_TYPES[$columnName])) {
            return $this->SPECIAL_FIELD_TYPES[$columnName];
        } else if (isset($this->SPECIAL_FIELD_NAMES[$columnName])) {
            return $this->SPECIAL_FIELD_NAMES[$columnName];
        } else if (isset($this->FIELD_TYPES[$dataType])) {
            return $this->FIELD_TYPES[$dataType];
        } else {
            throw new \Exception ('type Name Could Not Found for: ' . $columnName);
        }
    }

    public function getMaxLength($maxLength, $dataType)
    {
        if (!isset($maxLength)) {
            if (isset($this->LENGHTS[$dataType])) {
                return $this->LENGHTS[$dataType];
            } else {
                return 255;
            }
        } else {
            return $maxLength;
        }
    }

    public function isRequired($isRequired)
    {
        if ($isRequired == 'NO') {
            return 'required';
        } else {
            return null;
        }
    }

    public function isDisabled($columnName)
    {
        if (in_array($columnName, $this->NON_EDITABLE_FIELDS)) {
            return 'disabled';
        } else {
            return null;
        }
    }

    public function isEditable($columnName)
    {
        if (!in_array($columnName, $this->NON_EDITABLE_FIELDS)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Setup columns for DataModel.
     *
     * @param  DataModel $dataModel
     *
     * @return void
     */
    public function getColumns($dataModel)
    {
        $columnCacheName = '__DataModel_'.$this->dataModel->getName().'_Columns';
        if(Cache::has($columnCacheName))
            return Cache::get($columnCacheName);
        else{
            $formFields = $dataModel->getFormFields();
            $tableFields = $dataModel->getTableFields();
            $DBColumns = \DB::select(\DB::raw($this->DB_COLUMN_QUERY));
            foreach ($DBColumns as $DBColumn) {
                $column = new Column();
                $column->setLabel(Tider::beautify($DBColumn->column_name));
                $column->setName($DBColumn->column_name);
                $placeholder = (\Lang::has('general.Enter_' . $column->getLabel())) ? trans('general.Enter_' . $column->getLabel()) : 'Enter ' . $column->getLabel();
                $column->setPlaceholder($placeholder);
                $type = $this->getType($DBColumn->column_name, $DBColumn->data_type);
                $column->setType($type);
                $disabled = $this->isDisabled($DBColumn->column_name);
                $column->setDisabled($disabled);
                $editable = $this->isEditable($DBColumn->column_name);
                $column->setEditable($editable);
                $maxLength = $this->getMaxLength($DBColumn->character_maximum_length, $DBColumn->data_type);
                $column->setMaxlength($maxLength);
                $required = $this->isRequired($DBColumn->is_nullable);
                $column->setRequired($required);
                $column->setDefault($DBColumn->column_default);
                $columns[$DBColumn->column_name] = $column;
                if (sizeof($formFields) == 1 && in_array('*', $formFields) && $this->isEditable($DBColumn->column_name)) {
                    $tmpFormFields[] = $column->getName();
                }
                if (sizeof($tableFields) == 1 && in_array('*', $tableFields) && $this->isEditable($DBColumn->column_name)) {
                    $tmpTableFields[] = $column->getName();
                }
            }
            if (sizeof($formFields == 1) && $formFields[0] == '*') {
                $dataModel->setFormFields($tmpFormFields);
            }
            if (sizeof($tableFields == 1) && $tableFields[0] == '*') {
                $dataModel->setTableFields($tmpTableFields);
            }
            Cache::put($columnCacheName, $columns, 60);
            return $columns;
        }

    }

    /**
     * Get foreign keys of DataModel
     *
     * @param  DataModel $dataModel
     *
     * @return void
     */
    public function getFKs($dataModel)
    {
        $domestics = null;
        $foreigns = null;
        $fksCacheName = '__DataModel_'.$this->dataModel->getName().'_FKS';
        if(Cache::has($fksCacheName))
            return Cache::get($fksCacheName);
        else{
            $fks = \DB::select(\DB::raw($this->DB_FOREIGNS_QUERY));
            foreach ($fks as $fk) {
                if ($fk->table_name == $dataModel->getTable()) {
                    $foreign = new \stdClass();
                    foreach ($this->FOREIGN_FIELDS as $key => $foreignField) {
                        $foreign->$foreignField = $fk->$key;
                    }
                    $foreign->functionName = Tider::toFunctionName($fk->foreign_table_name);
                    $foreign->foreignModelName = Tider::toModelName($fk->foreign_table_name);
                    //$foreign->dataModelName = $beautyfier->toDataModelName($fk->foreign_table_name);
                    $foreigns[$fk->column_name] = $foreign;
                } else {
                    $domestic = new \stdClass();

                    foreach ($this->FOREIGN_FIELDS as $key => $foreignField) {
                        $domestic->$foreignField = $fk->$key;
                    }
                    $domestic->functionName = Tider::toFunctionName($fk->table_name);
                    //$domestic->foreignModelName = $beautyfier->toForeignModelName($domestic->functionName);
                    //$domestic->dataModelName = $beautyfier->toDataModelName($fk->table_name);
                    $domestics[$domestic->functionName] = $domestic;
                }
            }
            $fksArray = ['foreigns'=>$foreigns, 'domestics'=>$domestics];
            Cache::put($fksCacheName, $fksArray, 60);
            return $fksArray;
        }

    }

}

?>