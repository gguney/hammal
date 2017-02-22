<?php
namespace Hammal\ColumnHelper;

use Hammal\Contracts\ColumnHelperContract;
use Hammal\ColumnHelper\Column;

class MySqlColumnHelper extends ColumnHelper{

    protected static $ESSENTIAL_FIELD_VARS = [	'column_name'=>'name',
    											'column_default'=>'columnDefault', 
    											'is_nullable'=>'required',
    											'data_type'=>'fieldType',
    											'character_maximum_length'=>'maxLength'];
    

   	public static function setupColumns($dataModel)
    {
    	$selectFields = implode(",", array_keys(self::$ESSENTIAL_FIELD_VARS));
        $DBColumns = \DB::select( \DB::raw("SELECT {$selectFields} FROM information_schema.columns WHERE table_schema='".env('DB_DATABASE')."' AND table_name='".$dataModel->getTable()."'"));
        $formFields = $dataModel->getFormFields();
       	$tableFields = $dataModel->getTableFields();
        $fieldVars = array_keys(self::$ESSENTIAL_FIELD_VARS);

        foreach($DBColumns as $DBColumn )
        {
            $column = new Column();
            foreach($fieldVars as $fieldVar)
            {
                switch($fieldVar)
                {
                    case 'column_name':
                        $column->set('label',self::beautify($DBColumn->column_name));
                        $column->set('name',$DBColumn->column_name);
                        $placeHolder = (\Lang::has('general.Enter_'.$column->get('label')))?trans('general.Enter_'.$column->get('label')):'Enter '.$column->get('label');

                        $column->set('placeholder', $placeHolder);
                        if(isset(self::$SPECIAL_FIELD_TYPES[$DBColumn->column_name]))
                            $column->set('type',self::$SPECIAL_FIELD_TYPES[$DBColumn->column_name] );
                        else if (isset(self::$SPECIAL_FIELD_NAMES[$DBColumn->column_name])) 
                            $column->set('type',self::$SPECIAL_FIELD_NAMES[$DBColumn->column_name] );
                        else if(isset(self::$FIELD_TYPES[$DBColumn->data_type]))
                           $column->set('type',self::$FIELD_TYPES[$DBColumn->data_type] );
                        else
                            throw new \Exception ('data_type Name Could Not Found for: '.$DBColumn->data_type );

                        if(in_array($DBColumn->column_name,self::$NON_EDITABLE_FIELDS))
                        {
                            $column->set('disabled','disabled');
                            $column->setEditable(false);
                        }
                        else
                            $column->setEditable(true); 

                    case 'character_maximum_length':
                        if(!isset($DBColumn->character_maximum_length))
                        {
                            if(isset( self::$LENGHTS[$DBColumn->data_type] ))
                                $column->set( 'maxlength',self::$LENGHTS[$DBColumn->data_type] );
                            else
                                $column->set('maxlength',255);
                        }
                        else
                            $column->set('maxlength', $DBColumn->character_maximum_length );
                        break;
                    case 'is_nullable':
                        if($DBColumn->$fieldVar == 'NO')
                            $column->set('required','required' );    
                        break;
                    case 'column_default':
                        $column->setDefault( $DBColumn->$fieldVar);  
                        break;
                }
            }
            $columns[$DBColumn->column_name] = $column; 

            if(sizeof($formFields== 1) && $formFields[0]=='*' )
            {
                if(!in_array($column->get('name'),self::$NON_EDITABLE_FIELDS))
                    $tmpFormFields[] = $column->get('name');
            }
            if(sizeof($tableFields== 1) && $tableFields[0]=='*')
            {
                if(!in_array($column->get('name'),self::$NON_EDITABLE_FIELDS))
                    $tmpTableFields[] = $column->get('name');

            }

        }
            if(sizeof($formFields== 1) && $formFields[0]=='*')
            {
                $dataModel->setFormFields($tmpFormFields);
                $formFields = $tmpFormFields;
            }
            if(sizeof($tableFields== 1) && $tableFields[0]=='*')
            {
                $dataModel->setTableFields($tmpTableFields);
                $tableFields = $tmpTableFields;

            }

            foreach($columns as $column)
            {
                if(in_array($column->get('name'),$formFields))
                {
                    $rule = null;
                    $rule = ($column->get('required')=='required' && $column->getEditable() )? "required":"";
                    $rule.=($column->get('required')=='required'  && $column->getEditable() )?self::$OR.self::$VALIDATOR_TYPES[$column->get('type')] : self::$VALIDATOR_TYPES[$column->get('type')];
                    $rule.= self::$OR."max:".$column->get('maxlength');
                    $rules[$column->get('name')] = $rule;              
                }   
            }
        $dataModel->setRules($rules);
        $dataModel->setColumns($columns);
        $dataModel->setNonEditableFields(self::$NON_EDITABLE_FIELDS);  

    }
    public static function getFKs($dataModel)
    {

        $fks = \DB::select( \DB::raw(
            "SELECT
                tc.constraint_name,
                tc.table_name,
                kcu.column_name,
                ccu.REFERENCED_TABLE_NAME AS foreign_table_name,
                kcu.REFERENCED_COLUMN_NAME AS foreign_column_name
            FROM
                information_schema.table_constraints AS tc 
                JOIN information_schema.key_column_usage AS kcu
                  ON tc.constraint_name = kcu.constraint_name
                JOIN information_schema.REFERENTIAL_CONSTRAINTS AS ccu
                  ON ccu.constraint_name = tc.constraint_name
            WHERE constraint_type = 'FOREIGN KEY' AND ccu.CONSTRAINT_SCHEMA='".env('DB_DATABASE')."' AND (tc.table_name='".$dataModel->getTable()."' OR ccu.REFERENCED_TABLE_NAME='".$dataModel->getTable()."' );"
            ));
        self::setupForeignsAndDomestics($dataModel,$fks);
    }
}


 ?>

