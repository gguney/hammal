<?php
namespace GGuney\Hammal\ColumnHelper;

class MySqlColumnHelper extends ColumnHelper
{

    /**
     * Attirbutes to get from DB.
     *
     * @var array
     */
    protected $ESSENTIAL_FIELD_VARS = [
        'column_name'              => 'name',
        'column_default'           => 'columnDefault',
        'is_nullable'              => 'required',
        'data_type'                => 'fieldType',
        'character_maximum_length' => 'maxLength'
    ];

    public function __construct($DM)
    {
        parent::__construct($DM);
        $this->DB_COLUMN_QUERY = "SELECT {$this->selectFields} FROM information_schema.columns WHERE table_schema='" . env('DB_DATABASE') . "' AND table_name='" . $this->dataModel->getTable() . "'";
        $this->DB_FOREIGNS_QUERY = "SELECT
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
            WHERE constraint_type = 'FOREIGN KEY' AND ccu.CONSTRAINT_SCHEMA='" . env('DB_DATABASE') . "' AND (tc.table_name='" . $this->dataModel->getTable() . "' OR ccu.REFERENCED_TABLE_NAME='" . $this->dataModel->getTable() . "' );";
    }

}
