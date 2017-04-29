<?php
namespace Hammal\ColumnHelper;

use GGuney\Hammal\Contracts\ColumnHelperContract;
use GGuney\Hammal\ColumnHelper\Column;

class PgSqlColumnHelper extends ColumnHelper
{

    /**
     * Attirbutes to get from DB.
     *
     * @var array
     */
    protected static $ESSENTIAL_FIELD_VARS = [
        'column_name'              => 'name',
        'column_default'           => 'columnDefault',
        'is_nullable'              => 'required',
        'udt_name'                 => 'fieldType',
        'character_maximum_length' => 'maxLength'
    ];

    public function __construct($DM)
    {
        parent::__construct($DM);
        $this->DB_COLUMN_QUERY = "SELECT {$this->selectFields},CASE WHEN is_nullable='NO' THEN true ELSE false END as is_nullable FROM information_schema.columns WHERE table_schema='public' AND table_name='" . $this->dataModel->getTable() . "'";
        $this->DB_FOREIGNS_QUERY = "SELECT
                tc.constraint_name,
                tc.table_name,
                kcu.column_name, 
                ccu.table_name AS foreign_table_name,
                ccu.column_name AS foreign_column_name 
            FROM 
                information_schema.table_constraints AS tc 
                JOIN information_schema.key_column_usage AS kcu
                  ON tc.constraint_name = kcu.constraint_name
                JOIN information_schema.constraint_column_usage AS ccu
                  ON ccu.constraint_name = tc.constraint_name
            WHERE constraint_type = 'FOREIGN KEY' AND (tc.table_name='" . $this->dataModel->getTable() . "' OR ccu.table_name='" . $this->dataModel->getTable() . "' );";
    }

}
